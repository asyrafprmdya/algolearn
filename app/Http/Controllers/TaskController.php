<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\QuizResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index()
    {
        // Peta skor level buat ngecek akses
        $levelScores = ['Pemula' => 1, 'Menengah' => 2, 'Lanjutan' => 3];
        $userLevelScore = $levelScores[Auth::user()->getRawLevel()] ?? 1;

        // Ambil semua kuis beserta materinya, filter sesuai level mahasiswa
        $quizzes = Quiz::with('material')->get()->filter(function ($quiz) use ($levelScores, $userLevelScore) {
            $matScore = $levelScores[$quiz->material->level] ?? 1;
            return $matScore <= $userLevelScore; 
        });

        // Ambil riwayat nilai kuis mahasiswa ini
        $results = QuizResult::where('user_id', Auth::id())->pluck('score', 'quiz_id')->toArray();

        return view('student.tasks.index', compact('quizzes', 'results'));
    }
}