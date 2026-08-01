<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Login page
Route::get('/client/login', function () {
    if (session('client_logged_in')) {
        return redirect('/client/dashboard');
    }
    return view('clientdashboard::login');
});

// Handle login POST
Route::post('/client/login', function (Request $request) {
    $username = $request->input('username');
    $password = $request->input('password');

    // Simple hardcoded auth: username "client", password "client"
    if ($username === 'client' && $password === 'client') {
        session(['client_logged_in' => true]);
        return redirect('/client/dashboard');
    }

    return redirect('/client/login')->with('error', 'Invalid username or password.');
});

// Logout
Route::get('/client/logout', function () {
    session()->forget('client_logged_in');
    return redirect('/');
});

// Protected dashboard routes
Route::middleware('web')->group(function () {
    $guard = function ($view) {
        return function () use ($view) {
            if (!session('client_logged_in')) {
                return redirect('/client/login');
            }
            return view('clientdashboard::' . $view);
        };
    };

    Route::get('/client/dashboard', $guard('dashboard'));
    Route::get('/client/assessments', $guard('assessments'));
    Route::get('/client/learning', $guard('learning'));
    Route::get('/client/appointments', $guard('appointments'));
    Route::get('/client/messages', $guard('messages'));
});
