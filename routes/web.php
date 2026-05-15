<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\PretestController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LecturerController;
use App\Http\Controllers\ProgressController;

Route::get('/', function () { return redirect()->route('login'); });

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {

    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/users', [AdminController::class, 'users'])->name('users.index');
        Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
        Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
        Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
        Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
        Route::patch('/users/{user}/toggle-status', [AdminController::class, 'toggleUserStatus'])->name('users.toggle');
        Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');
        Route::post('/users/import', [AdminController::class, 'importUsers'])->name('users.import');
        Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
        Route::put('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');
        Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
        Route::get('/reports/export', [AdminController::class, 'exportReport'])->name('reports.export');
    });

    Route::middleware(['role:lecturer'])->prefix('lecturer')->name('lecturer.')->group(function () {
        Route::get('/dashboard', [LecturerController::class, 'dashboard'])->name('dashboard');
        Route::get('/students/progress', [LecturerController::class, 'studentProgress'])->name('students.progress');
        Route::get('/materials', [LecturerController::class, 'indexMaterial'])->name('materials.index');
        Route::get('/materials/create', [LecturerController::class, 'createMaterial'])->name('materials.create');
        Route::post('/materials', [LecturerController::class, 'storeMaterial'])->name('materials.store');
        Route::get('/materials/{material}/edit', [LecturerController::class, 'editMaterial'])->name('materials.edit');
        Route::put('/materials/{material}', [LecturerController::class, 'updateMaterial'])->name('materials.update');
        Route::delete('/materials/{material}', [LecturerController::class, 'destroyMaterial'])->name('materials.destroy');
        Route::get('/pretest', [LecturerController::class, 'indexPretest'])->name('pretest.index');
        Route::post('/pretest', [LecturerController::class, 'storePretest'])->name('pretest.store');
        Route::put('/pretest/{pretest}', [LecturerController::class, 'updatePretest'])->name('pretest.update');
        Route::delete('/pretest/{pretest}', [LecturerController::class, 'destroyPretest'])->name('pretest.destroy');
        Route::get('/quizzes', [LecturerController::class, 'indexQuiz'])->name('quiz.index');
        Route::get('/materials/{material}/quiz/create', [LecturerController::class, 'createQuiz'])->name('quiz.create');
        Route::post('/materials/{material}/quiz', [LecturerController::class, 'storeQuiz'])->name('quiz.store');
        Route::get('/quizzes/{quiz}/edit', [LecturerController::class, 'editQuiz'])->name('quiz.edit');
        Route::put('/quizzes/{quiz}', [LecturerController::class, 'updateQuiz'])->name('quiz.update');
        Route::get('/quizzes/{quiz}', [LecturerController::class, 'showQuiz'])->name('quiz.show');
        Route::delete('/quizzes/{quiz}', [LecturerController::class, 'destroyQuiz'])->name('quiz.destroy');
    });

    Route::middleware(['role:student'])->prefix('student')->name('student.')->group(function () {
        Route::get('/pretest', [PretestController::class, 'index'])->name('pretest.index');
        Route::post('/pretest', [StudentController::class, 'submitPretest'])->name('pretest.store');
        Route::get('/pretest/result', [PretestController::class, 'result'])->name('pretest.result');
        
        Route::middleware(['pretest.completed'])->group(function () {
            Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');
            Route::get('/tasks', [StudentController::class, 'indexTasks'])->name('tasks.index');
            Route::get('/material/{material}', [StudentController::class, 'showMaterial'])->name('material.show');
            Route::post('/material/{material}/complete', [StudentController::class, 'completeMaterial'])->name('material.complete');
            Route::get('/materials', [MaterialController::class, 'index'])->name('material.index');
            Route::get('/quiz/{quiz}', [QuizController::class, 'show'])->name('quiz.show');
            Route::post('/quiz/{quiz}', [QuizController::class, 'submit'])->name('quiz.submit');
            Route::post('/quiz/{quiz}/evaluasi', [QuizController::class, 'submitEvaluasi'])->name('quiz.evaluasi.submit');
            Route::get('/quiz/{quiz}/result', [QuizController::class, 'result'])->name('quiz.result');
            Route::get('/progress', [ProgressController::class, 'index'])->name('progress.index');
        });
    });
});