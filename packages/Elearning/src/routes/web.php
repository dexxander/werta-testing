<?php

use Illuminate\Support\Facades\Route;
use Elearning\Http\Controllers\ElearningController;

Route::get('/elearning', [ElearningController::class, 'index'])->name('elearning.index');
Route::get('/elearning/overview', [ElearningController::class, 'overview'])->name('elearning.overview');
Route::get('/elearning/courses', [ElearningController::class, 'courses'])->name('elearning.courses');
Route::get('/elearning/paths', [ElearningController::class, 'paths'])->name('elearning.paths');
Route::get('/elearning/dashboard', [ElearningController::class, 'dashboard'])->name('elearning.dashboard');
Route::get('/elearning/instructors', [ElearningController::class, 'instructors'])->name('elearning.instructors');
Route::get('/elearning/pricing', [ElearningController::class, 'pricing'])->name('elearning.pricing');
Route::get('/elearning/faq', [ElearningController::class, 'faq'])->name('elearning.faq');