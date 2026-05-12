<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Material;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $totalMaterials = Material::count();
        $completedCount = is_array($user->completed_contents) ? count($user->completed_contents) : 0;
        
        return view('student.dashboard', compact('user', 'totalMaterials', 'completedCount'));
    }

    public function indexTasks()
    {
        $user = Auth::user();
        
        $materials = Material::orderByRaw("FIELD(level, 'Pemula', 'Menengah', 'Lanjutan')")
                    ->get()
                    ->groupBy('level');

        $completedMaterialIds = is_array($user->completed_contents) ? $user->completed_contents : [];

        return view('student.tasks.index', compact('materials', 'completedMaterialIds'));
    }

    public function showMaterial(\App\Models\Material $material)
    {

        $material->load(['quizzes.questions']);
        $quiz = $material->quizzes->first();
        
        return view('student.material.show', compact('material', 'quiz'));
    }

    public function completeMaterial(\App\Models\Material $material)
{
    $user = auth()->user();
    
    // Ambil data lama, kalau masih null jadiin array kosong
    $completed = is_array($user->completed_contents) ? $user->completed_contents : [];

    // Kalau ID materi ini belum ada di daftar "insaf", kita masukin
    if (!in_array($material->id, $completed)) {
        $completed[] = $material->id;
        $user->update([
            'completed_contents' => $completed
        ]);
    }

    return response()->json(['status' => 'success', 'message' => 'Misi kelar lek!']);
}
}