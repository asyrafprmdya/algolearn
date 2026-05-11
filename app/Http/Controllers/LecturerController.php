<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Material;
use App\Models\Quiz;
use App\Models\QuizResult;
use App\Models\Pretest;
use Illuminate\Support\Facades\Storage;

class LecturerController extends Controller
{
    // ==========================================
    // 1. BAGIAN DASHBOARD & LAPORAN
    // ==========================================
    public function dashboard()
    {
        $totalStudents = User::where('role', 'student')->count();
        $totalMaterials = Material::count();
        $recentResults = QuizResult::with(['user', 'quiz'])->latest()->take(5)->get();
        $materials = Material::latest()->get();
        
        return view('lecturer.dashboard', compact('totalStudents', 'totalMaterials', 'recentResults', 'materials'));
    }

    public function studentProgress()
    {
        $students = User::where('role', 'student')->get();
        return view('lecturer.progress', compact('students'));
    }


    // ==========================================
    // 2. BAGIAN KELOLA MATERI (PABRIK QUEST)
    // ==========================================
    public function indexMaterial()
    {
        $materials = Material::orderByRaw("FIELD(level, 'Pemula', 'Menengah', 'Lanjutan')")->get(); 
        return view('lecturer.materials.index', compact('materials'));
    }

    public function createMaterial()
    {
        return view('lecturer.materials.create'); 
    }

    public function storeMaterial(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'level' => 'required|string',
            'content' => 'required|string',
            'video_url' => 'nullable|url',
            'code_visualization' => 'nullable|string',
            'pdf_file' => 'nullable|mimes:pdf|max:10240',
        ]);

        $data = $request->except(['pdf_file', 'is_published']);
        $data['is_published'] = $request->has('is_published');

        if ($request->hasFile('pdf_file')) {
            $data['pdf_path'] = $request->file('pdf_file')->store('materials/pdfs', 'public');
        }

        Material::create($data);

        return redirect()->route('lecturer.materials.index');
    }

    public function editMaterial(Material $material)
    {
        return view('lecturer.materials.edit', compact('material'));
    }

    public function updateMaterial(Request $request, Material $material)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'level' => 'required|string',
            'content' => 'required|string',
            'video_url' => 'nullable|url',
            'code_visualization' => 'nullable|string',
            'pdf_file' => 'nullable|mimes:pdf|max:10240',
        ]);

        $data = $request->except(['pdf_file', 'is_published']);
        $data['is_published'] = $request->has('is_published');

        if ($request->hasFile('pdf_file')) {
            if ($material->pdf_path) {
                Storage::disk('public')->delete($material->pdf_path);
            }
            $data['pdf_path'] = $request->file('pdf_file')->store('materials/pdfs', 'public');
        }

        $material->update($data);

        return redirect()->route('lecturer.materials.index');
    }


    // ==========================================
    // 3. BAGIAN KELOLA KUIS (BANK KUIS)
    // ==========================================
    public function indexQuiz()
    {
        $materials = Material::with('quizzes')
                        ->orderByRaw("FIELD(level, 'Pemula', 'Menengah', 'Lanjutan')")
                        ->get(); 
        
        return view('lecturer.quizzes.index', compact('materials'));
    }

    public function createQuiz(Material $material)
    {
        return view('lecturer.quizzes.create', compact('material'));
    }

    public function storeQuiz(Request $request, Material $material)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'passing_grade' => 'required|integer|min:0|max:100',
            'questions' => 'required|array|min:1',
            'questions.*.question_text' => 'required|string',
            'questions.*.option_a' => 'required|string',
            'questions.*.option_b' => 'required|string',
            'questions.*.option_c' => 'required|string',
            'questions.*.option_d' => 'required|string',
            'questions.*.correct_option' => 'required|in:a,b,c,d',
        ]);

        $quiz = $material->quizzes()->create([
            'title' => $request->title,
            'passing_grade' => $request->passing_grade,
        ]);

        foreach ($request->questions as $q) {
            $quiz->questions()->create($q);
        }

        return redirect()->route('lecturer.quiz.index');
    }

    public function showQuiz(Quiz $quiz)
    {
        $quiz->load('questions');
        return view('lecturer.quizzes.show', compact('quiz'));
    }

    public function editQuiz(Quiz $quiz)
    {
        $quiz->load('questions');
        $material = $quiz->material;
        return view('lecturer.quizzes.edit', compact('quiz', 'material'));
    }

    public function updateQuiz(Request $request, Quiz $quiz)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'passing_grade' => 'required|integer|min:0|max:100',
            'questions' => 'required|array|min:1',
            'questions.*.question_text' => 'required|string',
            'questions.*.option_a' => 'required|string',
            'questions.*.option_b' => 'required|string',
            'questions.*.option_c' => 'required|string',
            'questions.*.option_d' => 'required|string',
            'questions.*.correct_option' => 'required|in:a,b,c,d',
        ]);

        $quiz->update([
            'title' => $request->title,
            'passing_grade' => $request->passing_grade,
        ]);

        $quiz->questions()->delete();
        foreach ($request->questions as $q) {
            $quiz->questions()->create($q);
        }

        return redirect()->route('lecturer.quiz.index');
    }

    public function destroyQuiz(Quiz $quiz)
    {
        $quiz->questions()->delete(); 
        $quiz->delete();
        
        return redirect()->route('lecturer.quiz.index');
    }

    // ==========================================
    // 4. BAGIAN KELOLA PRETEST
    // ==========================================
    public function indexPretest()
    {
        $pretests = \App\Models\Pretest::latest()->get();
        return view('lecturer.pretest.index', compact('pretests'));
    }

    public function storePretest(Request $request)
    {
        $request->validate([
            'question_text' => 'required|string',
            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'option_c' => 'required|string',
            'option_d' => 'required|string',
            'correct_option' => 'required|in:a,b,c,d',
        ]);

        \App\Models\Pretest::create($request->all());

        return back()->with('success', 'Soal pretest berhasil ditambahkan, maba siap tersiksa!');
    }

    public function updatePretest(Request $request, \App\Models\Pretest $pretest)
    {
        $request->validate([
            'question_text' => 'required|string',
            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'option_c' => 'required|string',
            'option_d' => 'required|string',
            'correct_option' => 'required|in:a,b,c,d',
        ]);

        $pretest->update($request->all());

        return back()->with('success', 'Soal pretest berhasil di-update lek!');
    }

    public function destroyPretest(\App\Models\Pretest $pretest)
    {
        $pretest->delete();
        return back()->with('success', 'Soal pretest berhasil dimusnahkan!');
    }
}