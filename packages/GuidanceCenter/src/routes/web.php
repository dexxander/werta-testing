<?php

use GuidanceCenter\Http\Controllers\GuidanceCenterController;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::get('/guidance-center', [GuidanceCenterController::class, 'index'])
        ->name('guidance-center.index');
    Route::get('/guidance-center/locations/{index}', [GuidanceCenterController::class, 'location'])
        ->middleware('throttle:30,1')
        ->whereNumber('index')
        ->name('guidance-center.location');
});
