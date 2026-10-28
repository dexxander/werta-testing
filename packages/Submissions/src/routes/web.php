<?php

use Illuminate\Support\Facades\Route;
use Submissions\Http\Controllers\SubmissionController;
use Submissions\Http\Controllers\EarningsController;

Route::middleware('web')->group(function () {
    Route::get('/submissions', [SubmissionController::class, 'index'])->name('submissions.index');
    // must stay BEFORE /submissions/{submission}
    Route::get('/submissions/earnings', [EarningsController::class, 'index'])->name('submissions.earnings');
    Route::post('/submissions/earnings/withdraw', [EarningsController::class, 'withdraw'])->name('submissions.earnings.withdraw');
    Route::get('/submissions/create', [SubmissionController::class, 'create'])->name('submissions.create');
    Route::post('/submissions', [SubmissionController::class, 'store'])->name('submissions.store');
    Route::get('/submissions/{submission}', [SubmissionController::class, 'show'])->name('submissions.show');
    Route::post('/submissions/{submission}/pay-fee', [SubmissionController::class, 'payFee'])->name('submissions.pay-fee');
});
