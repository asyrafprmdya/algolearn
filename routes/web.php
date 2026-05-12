<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\PretestController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LecturerController;
use App\Http\Controllers\ProgressController; // <-- Obat biar Progress kaga gaib

Route::get('/', function () { return redirect()->route('login'); });

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ─── GERBANG UTAMA (Wajib Login) ─────────────────────────────────
Route::middleware(['auth'])->group(function () {

    // ─── ADMIN ───────────────────────────────────────────────────
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // Manajemen Pengguna
        Route::get('/users', [AdminController::class, 'users'])->name('users.index');
        Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
        Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
        Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
        Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
        Route::patch('/users/{user}/toggle-status', [AdminController::class, 'toggleUserStatus'])->name('users.toggle');
        Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');
        Route::post('/users/import', [AdminController::class, 'importUsers'])->name('users.import');

        // Pengaturan & Laporan
        Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
        Route::put('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');
        Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
        Route::get('/reports/export', [AdminController::class, 'exportReport'])->name('reports.export');
    });

    // ─── LECTURER ─────────────────────────────────────────────────
    Route::middleware(['role:lecturer'])->prefix('lecturer')->name('lecturer.')->group(function () {
        Route::get('/dashboard', [LecturerController::class, 'dashboard'])->name('dashboard');
        Route::get('/students/progress', [LecturerController::class, 'studentProgress'])->name('students.progress');
        
        // Pabrik Quest (Materi)
        Route::get('/materials', [LecturerController::class, 'indexMaterial'])->name('materials.index');
        Route::get('/materials/create', [LecturerController::class, 'createMaterial'])->name('materials.create');
        Route::post('/materials', [LecturerController::class, 'storeMaterial'])->name('materials.store');
        Route::get('/materials/{material}/edit', [LecturerController::class, 'editMaterial'])->name('materials.edit');
        Route::put('/materials/{material}', [LecturerController::class, 'updateMaterial'])->name('materials.update');
        
        // Markas Pretest
        Route::get('/pretest', [LecturerController::class, 'indexPretest'])->name('pretest.index');
        Route::post('/pretest', [LecturerController::class, 'storePretest'])->name('pretest.store');
        Route::put('/pretest/{pretest}', [LecturerController::class, 'updatePretest'])->name('pretest.update');
        Route::delete('/pretest/{pretest}', [LecturerController::class, 'destroyPretest'])->name('pretest.destroy');
        
        // Bank Kuis
        Route::get('/quizzes', [LecturerController::class, 'indexQuiz'])->name('quiz.index');
        Route::get('/materials/{material}/quiz/create', [LecturerController::class, 'createQuiz'])->name('quiz.create');
        Route::post('/materials/{material}/quiz', [LecturerController::class, 'storeQuiz'])->name('quiz.store');
        Route::get('/quizzes/{quiz}/edit', [LecturerController::class, 'editQuiz'])->name('quiz.edit');
        Route::put('/quizzes/{quiz}', [LecturerController::class, 'updateQuiz'])->name('quiz.update');
        Route::get('/quizzes/{quiz}', [LecturerController::class, 'showQuiz'])->name('quiz.show');
        Route::delete('/quizzes/{quiz}', [LecturerController::class, 'destroyQuiz'])->name('quiz.destroy');
    });

    // ─── STUDENT ──────────────────────────────────────────────────
    Route::middleware(['role:student'])->prefix('student')->name('student.')->group(function () {
        
        // Pretest kaga perlu gembok "pretest.completed" karena ini ruang ujiannya
        Route::get('/pretest', [PretestController::class, 'index'])->name('pretest.index');
        Route::post('/pretest', [PretestController::class, 'store'])->name('pretest.store');
        Route::get('/pretest/result', [PretestController::class, 'result'])->name('pretest.result');

        // Gembok khusus: Cuma bisa diakses kalau udah kelar pretest
        Route::middleware(['pretest.completed'])->group(function () {
            
            Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');
            Route::get('/tasks', [StudentController::class, 'indexTasks'])->name('tasks.index');
            Route::get('/material/{material}', [StudentController::class, 'showMaterial'])->name('material.show');
            
            Route::get('/materials', [MaterialController::class, 'index'])->name('material.index');
            
            Route::get('/quizzes/{quiz}', [QuizController::class, 'show'])->name('quiz.show');
            Route::post('/quizzes/{quiz}', [QuizController::class, 'submit'])->name('quiz.submit');
            Route::get('/quizzes/{quiz}/result', [QuizController::class, 'result'])->name('quiz.result');
            
            Route::get('/progress', [ProgressController::class, 'index'])->name('progress.index');
            Route::post('/quizzes/{quiz}/evaluasi', [QuizController::class, 'submitEvaluasi'])->name('quiz.evaluasi.submit');
            Route::post('/material/{material}/complete', [StudentController::class, 'completeMaterial'])->name('material.complete');
        });
    });

}); // <-- INI DIA KURUNG TUTUP KERAMAT YANG LU LUPAIN!