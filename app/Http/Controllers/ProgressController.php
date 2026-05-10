<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\QuizResult;
use App\Models\Quiz;

class ProgressController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $results = QuizResult::with('quiz.material')
            ->where('user_id', $user->id)
            ->orderBy('updated_at', 'desc')
            ->get();
            
        $totalQuizzes = Quiz::count();
        
        $passedQuizzes = $results->filter(function($result) {
            return $result->score >= ($result->quiz->passing_grade ?? 70);
        })->count();
        
        $progressPercentage = $totalQuizzes > 0 ? round(($passedQuizzes / $totalQuizzes) * 100) : 0;

        return view('student.progress.index', compact('user', 'results', 'progressPercentage'));
    }
}