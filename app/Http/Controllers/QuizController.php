<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\Material;
use App\Models\Question;
use App\Models\QuizResult;
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
        return $this->processSubmission($request, $quiz);
    }

    public function submitEvaluasi(Request $request, Quiz $quiz)
    {
        return $this->processSubmission($request, $quiz);
    }

    private function processSubmission(Request $request, Quiz $quiz)
    {
        $user = Auth::user();
        $questions = $quiz->questions;
        $score = 0;
        $total = $questions->count();

        if ($request->has('answers')) {
            foreach ($questions as $q) {
                $userAns = $request->input("answers.{$q->id}");
                if ($q->type === 'arrange') {
                    if (trim(strtolower($userAns)) === trim(strtolower($q->correct_option))) {
                        $score++;
                    }
                } else {
                    if (strtolower($userAns) === strtolower($q->correct_option)) {
                        $score++;
                    }
                }
            }
        }

        $finalScore = $total > 0 ? round(($score / $total) * 100) : 0;

        $result = QuizResult::create([
            'user_id' => $user->id,
            'quiz_id' => $quiz->id,
            'score' => $finalScore,
            'is_passed' => ($finalScore >= $quiz->passing_grade && $finalScore > 0)
        ]);

        if ($result->is_passed) {
            $oldLevel = $user->getRawLevel();
            $this->handleLevelUp($user, $quiz);
            
            $user->refresh();
            $newLevel = $user->getRawLevel();

            if ($oldLevel !== $newLevel) {
                session()->flash('level_up', [
                    'old' => $oldLevel,
                    'new' => $newLevel
                ]);
            }
        }

        return redirect()->route('student.quiz.result', $quiz->id);
    }

    private function handleLevelUp($user, $quiz)
    {
        $currentLevel = $user->getRawLevel();
        $materialLevel = $quiz->material->level;

        if ($currentLevel === $materialLevel && $quiz->category === 'practice') {
            
            $materialIds = Material::where('level', $currentLevel)->pluck('id');
            
            $practiceQuizIds = Quiz::whereIn('material_id', $materialIds)
                                     ->where('category', 'practice')
                                     ->pluck('id');

            if ($practiceQuizIds->isEmpty()) {
                return;
            }

            $passedPracticeCount = QuizResult::where('user_id', $user->id)
                                            ->whereIn('quiz_id', $practiceQuizIds)
                                            ->where('is_passed', true)
                                            ->pluck('quiz_id')
                                            ->unique()
                                            ->count();

            if ($passedPracticeCount >= $practiceQuizIds->count()) {
                if ($currentLevel === 'Pemula') {
                    $user->update(['level' => 'Menengah']);
                } elseif ($currentLevel === 'Menengah') {
                    $user->update(['level' => 'Lanjutan']);
                }
            }
        }
    }

    public function result(Quiz $quiz)
    {
        $result = QuizResult::where('user_id', Auth::id())
            ->where('quiz_id', $quiz->id)
            ->latest()
            ->first();

        return view('student.quizzes.result', compact('quiz', 'result'));
    }
}