<?php

namespace App\Http\Controllers;

use App\Models\PretestQuestion;
use App\Models\User;
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
        $questions = PretestQuestion::all();
        
        $correctCount = 0;
        $total = 10; 

        foreach ($questions as $q) {
            if (isset($answers[$q->id])) {
                $userAnswer = strtolower(trim($answers[$q->id]));
                $correctAnswer = strtolower(trim($q->correct_answer)); 
                
                if ($userAnswer === $correctAnswer) {
                    $correctCount++;
                }
            }
        }

        $score = round(($correctCount / $total) * 100);

        $kasta = 'Pemula';
        if ($score >= 80) {
            $kasta = 'Lanjutan';
        } elseif ($score >= 50) {
            $kasta = 'Menengah';
        }

        $dbUser = User::find($user->id);
        $dbUser->level = $kasta;
        $dbUser->pretest_completed = 1;
        $dbUser->save(); 

        return redirect()->route('student.pretest.result')->with('score', $score);
    }

    public function result()
    {
        $score = session('score');
        
        if ($score === null) {
            if (!Auth::user()->hasCompletedPretest()) {
                return redirect()->route('student.pretest.index');
            } else {
                return redirect()->route('student.dashboard');
            }
        }

        return view('pretest.result', compact('score'));
    }
}