<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QuizResult;
use Illuminate\Support\Facades\Auth;

class ProgressController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Ambil semua hasil kuis mahasiswa
        $results = QuizResult::with('quiz.material')
                    ->where('user_id', $user->id)
                    ->latest()
                    ->get();

        // Mutlak: Pake kolom is_passed langsung dari database (kaga ngitung manual lagi)
        $passedQuizzes = $results->where('is_passed', true)->count();
        
        return view('student.progress.index', compact('results', 'passedQuizzes'));
    }
}