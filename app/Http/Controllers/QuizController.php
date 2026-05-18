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
        $user = Auth::user();

        // Satpam Anti-Cheat (Bypass URL)
        // Mengecek apakah materi sebelumnya di level yang sama udah tuntas
        if ($quiz->category === 'practice') {
            $materialsInLevel = Material::where('level', $quiz->material->level)
                                        ->orderBy('created_at', 'asc')
                                        ->pluck('id')->toArray();
            
            $currentIndex = array_search($quiz->material_id, $materialsInLevel);
            
            if ($currentIndex > 0) {
                $prevMaterialId = $materialsInLevel[$currentIndex - 1];
                $completed = json_decode($user->completed_contents, true) ?? [];
                
                // Kalau materi/kuis sebelumnya belum kelar, tendang balik ke Arena Latihan!
                if (!in_array($prevMaterialId, $completed)) {
                    return redirect()->route('student.tasks.index');
                }
            }
        }

        $hasTaken = QuizResult::where('user_id', $user->id)->where('quiz_id', $quiz->id)->exists();
        
        $viewName = view()->exists('student.quizzes.show') ? 'student.quizzes.show' : 'student.quiz.show';

        if ($hasTaken && !$request->has('confirm')) {
            $quiz->load('questions');
            $askRepeat = true;
            return view($viewName, compact('quiz', 'askRepeat'));
        }

        $quiz->load('questions');
        $askRepeat = false;
        return view($viewName, compact('quiz', 'askRepeat'));
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
                    // Sapu bersih spasi dan koma biar kebal dari typo maba
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
            // FIX FATAL BUG: Catat ID Materi ke database biar node selanjutnya bisa kebuka!
            $completed = json_decode($user->completed_contents, true) ?? [];
            if (!in_array($quiz->material_id, $completed)) {
                $completed[] = $quiz->material_id;
                $user->completed_contents = json_encode($completed);
                $user->save();
            }

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

        $viewName = view()->exists('student.quizzes.result') ? 'student.quizzes.result' : 'student.quiz.result';
        return view($viewName, compact('quiz', 'result'));
    }
}