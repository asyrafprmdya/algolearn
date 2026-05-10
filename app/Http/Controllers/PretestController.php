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
        $total = 10;

        foreach ($questions as $q) {
            if (isset($answers[$q->id]) && strtolower($answers[$q->id]) === strtolower($q->correct_option)) {
                $correctCount++;
            }
        }

        $score = round(($correctCount / $total) * 100);

        if ($score >= 80) {
            $user->level = 'Lanjutan';
        } elseif ($score >= 50) {
            $user->level = 'Menengah';
        } else {
            $user->level = 'Pemula';
        }

        $user->pretest_completed = true;
        $user->save();

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