<?php

use Illuminate\Support\Facades\Route;
use ReviewerDashboard\Http\Controllers\AuthController;
use ReviewerDashboard\Http\Controllers\ReviewController;

Route::middleware('web')->group(function () {
    // Sign in / out and public application form
    Route::get('/reviewer/login', [AuthController::class, 'showLogin'])->name('reviewer.login');
    Route::post('/reviewer/login', [AuthController::class, 'login']);
    Route::get('/reviewer/logout', [AuthController::class, 'logout'])->name('reviewer.logout');
    Route::get('/reviewer/apply', [AuthController::class, 'showApply'])->name('reviewer.apply');
    Route::post('/reviewer/apply', [AuthController::class, 'apply'])->name('reviewer.apply.store');

    // Approved reviewers only (checked in the controller)
    Route::get('/reviewer/dashboard', [ReviewController::class, 'dashboard'])->name('reviewer.dashboard');
    Route::get('/reviewer/completed', [ReviewController::class, 'completed'])->name('reviewer.completed');
    Route::get('/reviewer/guidelines', [ReviewController::class, 'guidelines'])->name('reviewer.guidelines');
    Route::get('/reviewer/reviews/{review}', [ReviewController::class, 'show'])->name('reviewer.reviews.show');
    Route::post('/reviewer/reviews/{review}', [ReviewController::class, 'submit'])->name('reviewer.reviews.submit');
    Route::post('/reviewer/reviews/{review}/decline', [ReviewController::class, 'decline'])->name('reviewer.reviews.decline');
});
