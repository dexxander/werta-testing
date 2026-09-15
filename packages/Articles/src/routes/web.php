<?php

use Illuminate\Support\Facades\Route;
use Articles\Http\Controllers\ArticleController;

Route::get('/articles', [ArticleController::class, 'index'])->name('public.articles');
Route::get('/articles/{slug}', [ArticleController::class, 'show'])->name('public.articles.show');