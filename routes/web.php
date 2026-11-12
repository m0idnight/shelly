
<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudySetController;
use Illuminate\Support\Facades\Route;

// Dashboard
Route::get('/', [DashboardController::class, 'index'])
    ->name('dashboard');

// Upload
Route::get('/upload', [StudySetController::class, 'create'])
    ->name('sets.create');

Route::post('/sets', [StudySetController::class, 'store'])
    ->name('sets.store');

// Study set CRUD
Route::get('/sets/{set}', [StudySetController::class, 'show'])
    ->name('sets.show');

Route::get('/sets/{set}/edit', [StudySetController::class, 'edit'])
    ->name('sets.edit');

Route::put('/sets/{set}', [StudySetController::class, 'update'])
    ->name('sets.update');

Route::delete('/sets/{set}', [StudySetController::class, 'destroy'])
    ->name('sets.destroy');

// Study progress
Route::post('/study-log', [DashboardController::class, 'log'])
    ->name('study-log.store');

// Summary
Route::get('/sets/{set}/summary', [StudySetController::class, 'summary'])
    ->name('sets.summary');

Route::get('/sets/{set}/summary/setup', [StudySetController::class, 'summarySetup'])
    ->name('sets.summary.setup');

Route::post('/sets/{set}/summary/prepare', [StudySetController::class, 'prepareSummary'])
    ->name('sets.summary.prepare');

Route::get('/sets/{set}/summary/generating', [StudySetController::class, 'summaryGenerating'])
    ->name('sets.summary.generating');

Route::post('/sets/{set}/summarize', [StudySetController::class, 'summarize'])
    ->name('sets.summarize');

// Flashcards
// Flashcard creation hub
Route::get('/sets/{set}/flashcards/create', [StudySetController::class, 'flashcardCreate'])
    ->name('sets.flashcards.create');
// AI flashcard setup
Route::get('/sets/{set}/flashcards/setup', [StudySetController::class, 'flashcardSetup'])
    ->name('sets.flashcards.setup');

// Save AI flashcard settings
Route::post('/sets/{set}/flashcards/prepare', [StudySetController::class, 'prepareFlashcards'])
    ->name('sets.flashcards.prepare');

// AI flashcard generating page
Route::get('/sets/{set}/flashcards/generating', [StudySetController::class, 'flashcardGenerating'])
    ->name('sets.flashcards.generating');

// Flashcards study page
Route::get('/sets/{set}/flashcards', [StudySetController::class, 'flashcards'])
    ->name('sets.flashcards');
    // Manual flashcard management page
Route::get('/sets/{set}/flashcards/manual', [StudySetController::class, 'flashcardManual'])
    ->name('sets.flashcards.manual');

Route::post('/sets/{set}/flashcards/generate', [StudySetController::class, 'makeFlashcards'])
    ->name('sets.flashcards.make');

Route::post('/sets/{set}/flashcards/{flashcard}/mark', [StudySetController::class, 'markCard'])
    ->name('sets.flashcards.mark');
    // Manual flashcards
Route::post('/sets/{set}/flashcards', [StudySetController::class, 'storeManualFlashcard'])
    ->name('sets.flashcards.store');

Route::put('/sets/{set}/flashcards/{flashcard}', [StudySetController::class, 'updateManualFlashcard'])
    ->name('sets.flashcards.update');

Route::delete('/sets/{set}/flashcards/{flashcard}', [StudySetController::class, 'destroyManualFlashcard'])
    ->name('sets.flashcards.destroy');

// Quiz settings
Route::get('/sets/{set}/quiz', [StudySetController::class, 'quiz'])
    ->name('sets.quiz');

// Quiz gameplay
Route::get('/sets/{set}/quiz/play', [StudySetController::class, 'quizPlay'])
    ->name('sets.quiz.play');

// Prepare quiz settings
Route::post('/sets/{set}/quiz/prepare', [StudySetController::class, 'prepareQuiz'])
    ->name('sets.quiz.prepare');

// Quiz generating page
Route::get('/sets/{set}/quiz/generating', [StudySetController::class, 'quizGenerating'])
    ->name('sets.quiz.generating');

// Generate quiz
Route::post('/sets/{set}/quiz/generate', [StudySetController::class, 'makeQuiz'])
    ->name('sets.quiz.make');

    