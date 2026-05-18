<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Material;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\QuizResult;
use App\Models\PretestQuestion;
use Illuminate\Support\Facades\Storage;

class LecturerController extends Controller
{
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

   public function indexMaterial()
    {
        $materials = Material::orderByRaw("FIELD(level, 'Pemula', 'Menengah', 'Lanjutan')")
                             ->orderBy('created_at', 'asc')
                             ->get(); 
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

    public function destroyMaterial(Material $material)
    {
        if ($material->pdf_path) {
            Storage::disk('public')->delete($material->pdf_path);
        }

        $quizzes = Quiz::where('material_id', $material->id)->get();
        
        foreach ($quizzes as $quiz) {
            QuizResult::where('quiz_id', $quiz->id)->delete();
            $quiz->questions()->delete();
            $quiz->delete();
        }

        $material->delete();

        return redirect()->route('lecturer.materials.index');
    }

    public function indexQuiz()
    {
        $materials = Material::with('quizzes')
                        ->orderByRaw("FIELD(level, 'Pemula', 'Menengah', 'Lanjutan')")
                        ->get(); 
        
        return view('lecturer.quizzes.index', compact('materials'));
    }

    public function createQuiz(Request $request, Material $material)
    {
        $category = $request->query('category', 'practice');
        return view('lecturer.quizzes.create', compact('material', 'category'));
    }

    public function storeQuiz(Request $request, Material $material)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'passing_grade' => 'required|integer|min:0|max:100',
            'category' => 'required|in:practice,evaluation',
            'questions' => 'required|array|min:1',
            'questions.*.question_text' => 'required|string',
        ]);

        $quiz = Quiz::create([
            'material_id' => $material->id,
            'title' => $request->title,
            'category' => $request->category ?? 'practice',
            'passing_grade' => $request->passing_grade,
        ]);

        if ($request->has('questions')) {
            foreach ($request->questions as $q) {
                $isArrange = isset($q['type']) && $q['type'] === 'arrange';

                Question::create([
                    'quiz_id' => $quiz->id,
                    'question_text' => $q['question_text'],
                    'type' => $q['type'] ?? 'multiple_choice',
                    'option_a' => $isArrange ? '-' : ($q['option_a'] ?? '-'),
                    'option_b' => $isArrange ? '-' : ($q['option_b'] ?? '-'),
                    'option_c' => $isArrange ? '-' : ($q['option_c'] ?? '-'),
                    'option_d' => $isArrange ? '-' : ($q['option_d'] ?? '-'),
                    'options' => $isArrange ? ($q['options_arrange'] ?? null) : null,
                    'correct_option' => $isArrange ? ($q['correct_option_arrange'] ?? null) : ($q['correct_option_mc'] ?? null),
                    'explanation' => $q['explanation'] ?? null,
                ]);
            }
        }

        return redirect()->route('lecturer.materials.index');
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
        ]);

        $quiz->update([
            'title' => $request->title,
            'category' => $request->category ?? $quiz->category,
            'passing_grade' => $request->passing_grade,
        ]);

        $quiz->questions()->delete();
        
        foreach ($request->questions as $q) {
            $isArrange = isset($q['type']) && $q['type'] === 'arrange';

            Question::create([
                'quiz_id' => $quiz->id,
                'question_text' => $q['question_text'],
                'type' => $q['type'] ?? 'multiple_choice',
                'option_a' => $isArrange ? '-' : ($q['option_a'] ?? '-'),
                'option_b' => $isArrange ? '-' : ($q['option_b'] ?? '-'),
                'option_c' => $isArrange ? '-' : ($q['option_c'] ?? '-'),
                'option_d' => $isArrange ? '-' : ($q['option_d'] ?? '-'),
                'options' => $isArrange ? ($q['options_arrange'] ?? null) : null,
                'correct_option' => $isArrange ? ($q['correct_option_arrange'] ?? null) : ($q['correct_option_mc'] ?? $q['correct_option'] ?? null),
                'explanation' => $q['explanation'] ?? null,
            ]);
        }

        return redirect()->route('lecturer.quiz.index');
    }

    public function indexPretest()
    {
        $pretests = PretestQuestion::latest()->get();
        return view('lecturer.pretest.index', compact('pretests'));
    }

    public function storePretest(Request $request)
    {
        $request->validate([
            'question' => 'required|string',
            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'option_c' => 'required|string',
            'option_d' => 'required|string',
            'correct_answer' => 'required|in:a,b,c,d',
        ]);

        PretestQuestion::create($request->all());

        return back();
    }

    public function updatePretest(Request $request, PretestQuestion $pretest)
    {
        $request->validate([
            'question' => 'required|string',
            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'option_c' => 'required|string',
            'option_d' => 'required|string',
            'correct_answer' => 'required|in:a,b,c,d',
        ]);

        $pretest->update($request->all());

        return back();
    }

    public function destroyPretest(PretestQuestion $pretest)
    {
        $pretest->delete();
        return back();
    }

    public function resetStudent($id)
    {
        $student = \App\Models\User::findOrFail($id);
        
        \App\Models\QuizResult::where('user_id', $student->id)->delete();
        
        $student->update([
            'level' => 'Belum',
            'has_completed_pretest' => false,
            'completed_contents' => null
        ]);

        return redirect()->back()->with('success', 'Dosa maba berhasil di-reset mutlak. Suruh dia pretest ulang!');
    }
}