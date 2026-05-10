<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\QuizResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    public function show(Quiz $quiz)
    {
        $quiz->load('questions');
        return view('student.quizzes.show', compact('quiz'));
    }

    public function submit(Request $request, Quiz $quiz)
    {
        $user = Auth::user();
        $answers = $request->input('answers', []);
        $correctCount = 0;
        
        $quiz->load('questions', 'material');
        $totalQuestions = $quiz->questions->count();

        foreach ($quiz->questions as $question) {
            if (isset($answers[$question->id]) && $answers[$question->id] == $question->correct_option) {
                $correctCount++;
            }
        }

        $score = $totalQuestions > 0 ? round(($correctCount / $totalQuestions) * 100) : 0;

        QuizResult::updateOrCreate(
            ['user_id' => $user->id, 'quiz_id' => $quiz->id],
            ['score' => $score]
        );

        $isPassed = $score >= $quiz->passing_grade;

        if ($isPassed) {
            $currentLevel = strtolower($user->getLevel());
            $quizLevel = strtolower($quiz->material->level);

            if ($currentLevel === $quizLevel) {
                $passedCount = \App\Models\QuizResult::with('quiz.material')
                    ->where('user_id', $user->id)
                    ->get()
                    ->filter(function ($result) use ($currentLevel) {
                        $matLevel = strtolower($result->quiz->material->level ?? '');
                        $isLulus = $result->score >= ($result->quiz->passing_grade ?? 70);
                        
                        return $matLevel === $currentLevel && $isLulus;
                    })
                    ->count();

                if ($currentLevel === 'pemula' && $passedCount >= 3) {
                    $user->update(['level' => 'Menengah']);
                } elseif ($currentLevel === 'menengah' && $passedCount >= 6) {
                    $user->update(['level' => 'Lanjutan']);
                }
            }
        }

        return redirect()->route('student.quiz.result', $quiz->id)->with([
            'answers' => $answers,
            'score' => $score
        ]);
    }

    public function result(Quiz $quiz)
    {
        $answers = session('answers');
        $score = session('score');

        // Kalau mahasiswanya maksa akses URL result tapi belum ngerjain, tendang ke daftar tugas
        if ($answers === null) {
            return redirect()->route('student.tasks.index');
        }

        $quiz->load('questions');
        return view('student.quizzes.result', compact('quiz', 'answers', 'score'));
    }
}