<?php

use App\Http\Controllers\StudySetController;
use Illuminate\Support\Facades\Route;

Route::get('/', [StudySetController::class, 'create'])->name('sets.create');
Route::post('/sets', [StudySetController::class, 'store'])->name('sets.store');
Route::get('/sets/{set}', [StudySetController::class, 'show'])->name('sets.show');
Route::post('/sets/{set}/summarize', [StudySetController::class, 'summarize'])->name('sets.summarize');
