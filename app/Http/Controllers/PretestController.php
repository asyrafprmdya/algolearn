<?php

namespace App\Http\Controllers;

use App\Models\PretestQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PretestController extends Controller
{
    public function index()
    {
        if (Auth::user()->hasCompletedPretest()) {
            return redirect()->route('student.dashboard');
        }
        
        $questions = PretestQuestion::inRandomOrder()->limit(10)->get();
        
        return view('pretest.index', compact('questions'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $answers = $request->input('answers', []);
        
        $questionIds = array_keys($answers);
        $questions = PretestQuestion::whereIn('id', $questionIds)->get();
        
        $correctCount = 0;
        $total = $questions->count();

        foreach ($questions as $q) {
            if (isset($answers[$q->id]) && $answers[$q->id] == $q->correct_option) {
                $correctCount++;
            }
        }

        $score = $total > 0 ? round(($correctCount / $total) * 100) : 0;

        if ($score >= 80) {
            $user->update(['level' => 'Lanjutan', 'pretest_completed' => true]);
        } elseif ($score >= 50) {
            $user->update(['level' => 'Menengah', 'pretest_completed' => true]);
        } else {
            $user->update(['level' => 'Pemula', 'pretest_completed' => true]);
        }

        return redirect()->route('student.pretest.result')->with('score', $score);
    }

    public function result()
    {
        $score = session('score');
        
        if ($score === null && !Auth::user()->pretest_completed) {
            return redirect()->route('student.dashboard');
        }

        return view('pretest.result', compact('score'));
    }
}