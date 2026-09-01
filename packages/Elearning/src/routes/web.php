<?php

use Illuminate\Support\Facades\Route;
use Elearning\Http\Controllers\ElearningController;

Route::get('/elearning', [ElearningController::class, 'index'])->name('elearning.index');
Route::get('/elearning/overview', [ElearningController::class, 'overview'])->name('elearning.overview');
Route::get('/elearning/courses', [ElearningController::class, 'courses'])->name('elearning.courses');
Route::get('/elearning/category/{slug}', [ElearningController::class, 'categoryCourses'])->name('elearning.category');
Route::get('/elearning/paths', [ElearningController::class, 'paths'])->name('elearning.paths');
Route::get('/elearning/course/{id}', [ElearningController::class, 'coursePreview'])->name('elearning.course-preview');
Route::middleware('web')->group(function () {
    $clientGuard = function ($method) {
        return function ($id = null) use ($method) {
            if (!session('client_logged_in')) {
                return redirect('/client/login')->with('error', 'Only clients can access this page.');
            }
            $controller = app(ElearningController::class);
            return $id ? $controller->$method($id) : $controller->$method();
        };
    };

    Route::get('/elearning/dashboard', $clientGuard('dashboard'))->name('elearning.dashboard');
    Route::get('/elearning/my-courses', $clientGuard('myCourses'))->name('elearning.my-courses');
    Route::get('/elearning/course/{id}/content', $clientGuard('courseContent'))->name('elearning.course-content');
});

Route::get('/elearning/instructors', function () { return redirect()->route('elearning.index'); })->name('elearning.instructors');
Route::get('/elearning/pricing', [ElearningController::class, 'pricing'])->name('elearning.pricing');
Route::get('/elearning/checkout', [ElearningController::class, 'checkout'])->name('elearning.checkout');
Route::get('/elearning/faq', [ElearningController::class, 'faq'])->name('elearning.faq');
