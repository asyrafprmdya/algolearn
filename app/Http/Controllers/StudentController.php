<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Material;
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
        
        $materials = Material::with(['quizzes' => function($query) {
                    $query->where('category', 'practice');
                }])
                ->orderByRaw("FIELD(level, 'Pemula', 'Menengah', 'Lanjutan')")
                ->get()
                ->groupBy('level');

        $completedMaterialIds = is_array($user->completed_contents) ? $user->completed_contents : [];

        return view('student.tasks.index', compact('materials', 'completedMaterialIds'));
    }

    public function showMaterial(Material $material)
    {
        $material->load(['quizzes' => function($query) {
            $query->where('category', 'evaluation')->with('questions');
        }]);
        $quiz = $material->quizzes->first();
        return view('student.material.show', compact('material', 'quiz'));
    }

    public function completeMaterial(Material $material)
    {
        $user = Auth::user();
        $completed = is_array($user->completed_contents) ? $user->completed_contents : [];

        if (!in_array($material->id, $completed)) {
            $completed[] = $material->id;
            $user->update(['completed_contents' => $completed]);
        }

        return response()->json(['status' => 'success']);
    }
}