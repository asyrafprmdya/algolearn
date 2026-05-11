<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Material;
use App\Models\QuizResult;
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
        $validated = $request->validate([
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
        $validated = $request->validate([
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

    public function studentProgress()
    {
        $students = User::where('role', 'student')->get();
        
        return view('lecturer.progress', compact('students'));
    }

    public function createQuiz($id)
    {
        $material = Material::findOrFail($id);
        
        return view('lecturer.quizzes.create', compact('material'));
    }

    public function storeQuiz(Request $request, $id)
    {
        $material = Material::findOrFail($id);

        return redirect()->route('lecturer.dashboard')->with('success', 'Kuis berhasil dibuat (boongan dulu)!');
    }
}