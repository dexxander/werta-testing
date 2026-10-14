<?php

use Illuminate\Support\Facades\Route;
use Articles\Http\Controllers\ArticleController;
use Articles\Http\Controllers\MockBillingController;

Route::middleware('web')->group(function () {
    Route::get('/articles', [ArticleController::class, 'index'])->name('public.articles');
    Route::get('/articles/{slug}', [ArticleController::class, 'show'])->name('public.articles.show');
    Route::get('/articles/{slug}/subscribe', [ArticleController::class, 'subscribe'])->name('public.articles.subscribe');

    // DEMO ONLY: fake, session-backed billing (no real payments)
    Route::get('/wallet', [MockBillingController::class, 'wallet'])->name('mock.wallet');
    Route::post('/wallet/topup', [MockBillingController::class, 'topUp'])->name('mock.topup');
    Route::post('/subscription/start', [MockBillingController::class, 'subscribe'])->name('mock.subscribe');
    Route::post('/subscription/expire', [MockBillingController::class, 'expire'])->name('mock.expire');
    Route::post('/mock/reset', [MockBillingController::class, 'reset'])->name('mock.reset');
    Route::post('/articles/{slug}/buy', [MockBillingController::class, 'buy'])->name('mock.buy');
    Route::post('/articles/{slug}/use-allowance', [MockBillingController::class, 'allowance'])->name('mock.allowance');
});
