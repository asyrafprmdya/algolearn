<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Material;
use App\Models\Quiz;
use App\Models\PretestQuestion;
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

    public function indexPretest()
    {
        $questions = PretestQuestion::all();
        return view('student.pretest', compact('questions'));
    }

    public function submitPretest(Request $request)
    {
        $user = Auth::user();
        $questions = PretestQuestion::all();
        $score = 0;
        $total = $questions->count();

        if ($request->has('answers')) {
            foreach ($request->answers as $qId => $ans) {
                $q = $questions->where('id', $qId)->first();
                if ($q && strtolower($q->correct_answer) === strtolower($ans)) {
                    $score++;
                }
            }
        }

        $finalScore = $total > 0 ? round(($score / $total) * 100) : 0;

        if ($finalScore >= 80) {
            $user->level = 'Lanjutan';
        } elseif ($finalScore >= 50) {
            $user->level = 'Menengah';
        } else {
            $user->level = 'Pemula';
        }

        $user->has_completed_pretest = true;
        $user->save();

        return redirect()->route('student.pretest.result')->with([
            'score' => $finalScore,
            'level' => $user->level,
            'correct' => $score,
            'total' => $total
        ]);
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
        $quiz = Quiz::where('material_id', $material->id)
                    ->where('category', 'evaluation')
                    ->with('questions')
                    ->first();
        
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