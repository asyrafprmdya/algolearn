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
    public function show(Request $request, Quiz $quiz)
    {
        // Cek dosa: Apakah maba ini udah pernah ngerjain kuisnya?
        $hasTaken = QuizResult::where('user_id', Auth::id())->where('quiz_id', $quiz->id)->exists();

        // Kalo udah pernah dan belum ngasih konfirmasi, lempar variabel askRepeat
        if ($hasTaken && !$request->has('confirm')) {
            $quiz->load('questions');
            $askRepeat = true;
            return view('student.quizzes.show', compact('quiz', 'askRepeat'));
        }

        $quiz->load('questions');
        $askRepeat = false;
        return view('student.quizzes.show', compact('quiz', 'askRepeat'));
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
                    // Anti-typo: bersihin koma dan spasi sebelum dicocokin
                    $cleanUser = str_replace([' ', ','], '', strtolower(trim($userAns)));
                    $cleanCorrect = str_replace([' ', ','], '', strtolower(trim($q->correct_option)));
                    if ($cleanUser === $cleanCorrect) {
                        $score++;
                    }
                } else {
                    if (strtolower(trim($userAns)) === strtolower(trim($q->correct_option))) {
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
        $materialLevel = $quiz->material->level ?? 'Pemula';

        if ($currentLevel === $materialLevel && $quiz->category === 'practice') {
            $materialIds = Material::where('level', $currentLevel)->pluck('id');
            $practiceQuizIds = Quiz::whereIn('material_id', $materialIds)
                                     ->where('category', 'practice')
                                     ->pluck('id');

            if ($practiceQuizIds->isEmpty()) return;

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

        // Fallback pinter biar lu kaga nangis karena view error
        $viewName = view()->exists('student.quizzes.result') ? 'student.quizzes.result' : 'student.quizzes.result';
        return view($viewName, compact('quiz', 'result'));
    }
}