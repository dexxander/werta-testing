<?php

use Illuminate\Support\Facades\Route;
use Articles\Http\Controllers\ArticleController;
use Articles\Http\Controllers\AuthorController;
use Articles\Http\Controllers\MockBillingController;
use Articles\Http\Controllers\ReviewerProfileController;

Route::middleware('web')->group(function () {
    Route::get('/articles', [ArticleController::class, 'index'])->name('public.articles');
    Route::get('/articles/{slug}', [ArticleController::class, 'show'])->name('public.articles.show');
    Route::get('/articles/{slug}/subscribe', [ArticleController::class, 'subscribe'])->name('public.articles.subscribe');

    // Public author and reviewer pages
    Route::get('/authors/{role}/{author}', [AuthorController::class, 'show'])->name('public.authors.show');
    Route::get('/reviewers/{slug}', [ReviewerProfileController::class, 'show'])->name('public.reviewers.show');

    // DEMO ONLY: fake, session-backed token wallet (no real payments)
    Route::get('/wallet', [MockBillingController::class, 'wallet'])->name('mock.wallet');
    Route::post('/wallet/topup', [MockBillingController::class, 'topUp'])->name('mock.topup');
    Route::post('/wallet/promo', [MockBillingController::class, 'promo'])->name('mock.promo');
    Route::post('/wallet/referral', [MockBillingController::class, 'referral'])->name('mock.referral');
    Route::get('/r/{code}', [MockBillingController::class, 'referralLink'])->name('mock.referral.link');
    Route::post('/subscription/start', [MockBillingController::class, 'subscribe'])->name('mock.subscribe');
    Route::post('/subscription/expire', [MockBillingController::class, 'expire'])->name('mock.expire');
    Route::post('/mock/reset', [MockBillingController::class, 'reset'])->name('mock.reset');
    Route::post('/articles/{slug}/buy', [MockBillingController::class, 'buy'])->name('mock.buy');
    Route::post('/articles/{slug}/gift', [MockBillingController::class, 'gift'])->name('mock.gift');
    Route::post('/articles/{slug}/use-allowance', [MockBillingController::class, 'allowance'])->name('mock.allowance');
});
