<?php
use Illuminate\Support\Facades\Route;
use CounselorDashboard\Http\Controllers\{AuthController, DashboardController, ScheduleController, ClientController, ArticleController, ProfileController};

Route::middleware('web')->group(function () {
    Route::get('/counselor/login', [AuthController::class, 'showLogin'])->name('counselor.login');
    Route::post('/counselor/login', [AuthController::class, 'login']);
    Route::get('/counselor/logout', [AuthController::class, 'logout'])->name('counselor.logout');

    Route::middleware('counselor.auth')->group(function () {
        Route::get('/counselor/dashboard', [DashboardController::class, 'index'])->name('counselor.dashboard');
        Route::get('/counselor/schedule', [ScheduleController::class, 'index'])->name('counselor.schedule');
        Route::get('/counselor/clients', [ClientController::class, 'index'])->name('counselor.clients');
        Route::get('/counselor/articles', [ArticleController::class, 'index'])->name('counselor.articles.index');
        Route::get('/counselor/articles/create', [ArticleController::class, 'create'])->name('counselor.articles.create');
        Route::get('/counselor/profile', [ProfileController::class, 'index'])->name('counselor.profile');
    });
});