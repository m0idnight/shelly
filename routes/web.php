
<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudySetController;
use Illuminate\Support\Facades\Route;

// Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Upload
Route::get('/upload', [StudySetController::class, 'create'])->name('sets.create');
Route::post('/sets', [StudySetController::class, 'store'])->name('sets.store');

// Study set CRUD
Route::get('/sets/{set}', [StudySetController::class, 'show'])->name('sets.show');
Route::get('/sets/{set}/edit', [StudySetController::class, 'edit'])->name('sets.edit');
Route::put('/sets/{set}', [StudySetController::class, 'update'])->name('sets.update');
Route::delete('/sets/{set}', [StudySetController::class, 'destroy'])->name('sets.destroy');

// Study progress
Route::post('/study-log', [DashboardController::class, 'log'])->name('study-log.store');

// Summary
Route::post('/sets/{set}/summarize', [StudySetController::class, 'summarize'])->name('sets.summarize');

// Flashcards
Route::get('/sets/{set}/flashcards', [StudySetController::class, 'flashcards'])->name('sets.flashcards');
Route::post('/sets/{set}/flashcards/generate', [StudySetController::class, 'makeFlashcards'])->name('sets.flashcards.make');

// Quiz
Route::get('/sets/{set}/quiz', [StudySetController::class, 'quiz'])->name('sets.quiz');
Route::post('/sets/{set}/quiz/generate', [StudySetController::class, 'makeQuiz'])->name('sets.quiz.make');
