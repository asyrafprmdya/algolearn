<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PretestController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\Admin\AdminController;

Route::get('/', function () { return redirect()->route('login'); });

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

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
        Route::patch('/users/{user}/toggle-status', [AdminController::class, 'toggleUserStatus'])->name('users.toggle-status');
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
        Route::get('/dashboard', [\App\Http\Controllers\LecturerController::class, 'dashboard'])->name('dashboard');

        Route::get('/materials/create', [\App\Http\Controllers\LecturerController::class, 'createMaterial'])->name('materials.create');
        Route::post('/materials', [\App\Http\Controllers\LecturerController::class, 'storeMaterial'])->name('materials.store');
        Route::get('/materials/{material}/edit', [\App\Http\Controllers\LecturerController::class, 'editMaterial'])->name('materials.edit');
        Route::put('/materials/{material}', [\App\Http\Controllers\LecturerController::class, 'updateMaterial'])->name('materials.update');
        Route::get('/students/progress', [\App\Http\Controllers\LecturerController::class, 'studentProgress'])->name('students.progress');
        Route::get('/materials/{material}/quiz/create', [\App\Http\Controllers\LecturerController::class, 'createQuiz'])->name('quiz.create');
        Route::post('/materials/{material}/quiz', [\App\Http\Controllers\LecturerController::class, 'storeQuiz'])->name('quiz.store');
    });

    // ─── STUDENT ──────────────────────────────────────────────────
    Route::middleware(['role:student'])->prefix('student')->name('student.')->group(function () {
        Route::get('/pretest', [PretestController::class, 'index'])->name('pretest.index');
        Route::post('/pretest', [PretestController::class, 'store'])->name('pretest.store');
        Route::get('/pretest/result', [PretestController::class, 'result'])->name('pretest.result');

        Route::middleware(['pretest.completed'])->group(function () {
            Route::view('/dashboard', 'student.dashboard')->name('dashboard');
            Route::post('/quiz/{quiz}', [QuizController::class, 'submit'])->name('quiz.submit');
            Route::get('/tasks', [\App\Http\Controllers\TaskController::class, 'index'])->name('tasks.index');
            Route::get('/materials', [MaterialController::class, 'index'])->name('material.index');
            Route::get('/material/{material}', [MaterialController::class, 'show'])->name('material.show');
            Route::get('/quiz/{quiz}', [\App\Http\Controllers\QuizController::class, 'show'])->name('quiz.show');
            Route::get('/quiz/{quiz}/result', [QuizController::class, 'result'])->name('quiz.result');
            Route::get('/progress', [\App\Http\Controllers\ProgressController::class, 'index'])->name('progress.index');
        });
    });
});