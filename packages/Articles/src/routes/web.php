<?php

use Illuminate\Support\Facades\Route;
use Articles\Http\Controllers\ArticleController;

Route::middleware('web')->group(function () {
    Route::get('/articles', [ArticleController::class, 'index'])->name('public.articles');
    Route::get('/articles/{slug}', [ArticleController::class, 'show'])->name('public.articles.show');
    Route::get('/articles/{slug}/subscribe', [ArticleController::class, 'subscribe'])->name('public.articles.subscribe');
});