<?php

use Illuminate\Support\Facades\Route;
use Submissions\Http\Controllers\SubmissionController;

Route::middleware('web')->group(function () {
    Route::get('/submissions', [SubmissionController::class, 'index'])->name('submissions.index');
    Route::get('/submissions/create', [SubmissionController::class, 'create'])->name('submissions.create');
    Route::post('/submissions', [SubmissionController::class, 'store'])->name('submissions.store');
    Route::get('/submissions/{submission}', [SubmissionController::class, 'show'])->name('submissions.show');
});