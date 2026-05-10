<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Material;
use App\Models\QuizResult;

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

   public function createMaterial()
{
    return view('lecturer.materials.create'); 
}

    public function storeMaterial(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'level' => 'required|string',
            'content' => 'required|string',
            'video_url' => 'nullable|url',
            'code_visualization' => 'nullable|string',
        ]);

        $validated['is_published'] = $request->has('is_published');
        
        Material::create($validated);
        
        return redirect()->route('lecturer.dashboard');
    }

    public function editMaterial(Material $material)
    {
        return view('lecturer.materials.edit', compact('material'));
    }

    public function updateMaterial(Request $request, Material $material)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'level' => 'required|string',
            'content' => 'required|string',
            'video_url' => 'nullable|url',
            'code_visualization' => 'nullable|string',
        ]);

        $validated['is_published'] = $request->has('is_published');
        
        $material->update($validated);
        
        return redirect()->route('lecturer.dashboard');
    }

   public function studentProgress()
    {
        // Ambil semua data mahasiswa
        $students = \App\Models\User::where('role', 'student')->get();
        
        // KUNCINYA DI SINI: Hapus kata 'students.' biar dia nyari di folder yang bener!
        return view('lecturer.progress', compact('students'));
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

        return redirect()->route('lecturer.dashboard');
    }

    public function indexMaterial()
{
    // Narik semua materi buatan dosen yang lagi login
    $materials = \App\Models\Material::all(); 
    return view('lecturer.materials.index', compact('materials'));
}
}