<?php

namespace App\Http\Controllers;

use App\Models\PretestQuestion;
use App\Models\Pretest;
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
        $questions = PretestQuestion::whereIn('id', array_keys($request->answers ?? []))->get();
        $correctAnswers = 0;
        $evaluation = [];

        foreach ($questions as $q) {
            $userAnswer = $request->answers[$q->id] ?? null;
            $isCorrect = $q->correct_answer === $userAnswer;

            if ($isCorrect) {
                $correctAnswers++;
            }

            $evaluation[] = [
                'question' => $q->question,
                'user_answer' => $userAnswer ? $q->{'option_'.$userAnswer} : 'Tidak dijawab',
                'correct_answer' => $q->{'option_'.$q->correct_answer},
                'is_correct' => $isCorrect,
            ];
        }

        $score = count($questions) > 0 ? ($correctAnswers / count($questions)) * 100 : 0;

        $level = match (true) {
            $score > 70 => 'Level 3 - Lanjutan',
            $score > 40 => 'Level 2 - Menengah',
            default => 'Level 1 - Pemula',
        };

        Pretest::create([
            'user_id' => Auth::id(),
            'score' => $score,
            'level' => $level,
            'completed_at' => now(),
        ]);

        session()->flash('pretest_result', [
            'score' => $score,
            'level' => $level,
            'evaluation' => $evaluation
        ]);

        return redirect()->route('student.pretest.result');
    }

    public function result()
    {
        if (!session('pretest_result')) {
            return redirect()->route('student.dashboard');
        }

        return view('pretest.result', ['result' => session('pretest_result')]);
    }
}