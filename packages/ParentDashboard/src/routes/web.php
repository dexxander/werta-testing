<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Login page
Route::get('/parent/login', function () {
    // If already "logged in" via session, redirect to dashboard
    if (session('parent_logged_in')) {
        return redirect('/parent/dashboard');
    }
    return view('parentdashboard::login');
});

// Handle login POST
Route::post('/parent/login', function (Request $request) {
    $username = $request->input('username');
    $password = $request->input('password');

    // Simple hardcoded auth: username "parent", password "parent"
    if ($username === 'parent' && $password === 'parent') {
        session(['parent_logged_in' => true]);
        return redirect('/parent/dashboard');
    }

    return redirect('/parent/login')->with('error', 'Invalid username or password.');
});

// Logout
Route::get('/parent/logout', function () {
    session()->forget('parent_logged_in');
    return redirect('/');
});

// Protected dashboard routes — redirect to login if not authenticated
Route::middleware('web')->group(function () {
    $guard = function ($view) {
        return function () use ($view) {
            if (!session('parent_logged_in')) {
                return redirect('/parent/login');
            }
            return view('parentdashboard::' . $view);
        };
    };

    Route::get('/parent/dashboard', $guard('dashboard'));
    Route::get('/parent/progress', $guard('progress'));
    Route::get('/parent/appointments', $guard('appointments'));
    Route::get('/parent/messages', $guard('messages'));
});
