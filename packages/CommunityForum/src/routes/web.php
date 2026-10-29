<?php

use CommunityForum\Http\Controllers\CommunityController;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->prefix('community')->name('community.')->group(function () {
    Route::get('/', [CommunityController::class, 'index'])->name('index');
    Route::get('/{post}', [CommunityController::class, 'show'])->whereNumber('post')->name('show');
    Route::post('/', [CommunityController::class, 'store'])->middleware('throttle:10,1')->name('store');
    Route::post('/{post}/replies', [CommunityController::class, 'reply'])->whereNumber('post')->middleware('throttle:20,1')->name('reply');
    Route::get('/{post}/replies/{reply}/edit', [CommunityController::class, 'editReply'])->whereNumber(['post', 'reply'])->name('reply.edit');
    Route::patch('/{post}/replies/{reply}', [CommunityController::class, 'updateReply'])->whereNumber(['post', 'reply'])->middleware('throttle:20,1')->name('reply.update');
    Route::delete('/{post}/replies/{reply}', [CommunityController::class, 'deleteReply'])->whereNumber(['post', 'reply'])->middleware('throttle:20,1')->name('reply.delete');
});
