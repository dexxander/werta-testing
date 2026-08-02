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

    if ($username === 'parent' && $password === 'parent') {
        session()->forget('client_logged_in');
        session(['parent_logged_in' => true]);
        session(['parent_profile' => ['username' => 'Parent User', 'picture' => 'bi-person-heart']]);
        return redirect('/');
    }

    return redirect('/parent/login')->with('error', 'Invalid username or password.');
});

// Logout
Route::get('/parent/logout', function () {
    session()->forget('parent_logged_in');
    session()->forget('parent_profile');
    return redirect('/');
});

// Handle Profile Update
Route::post('/parent/profile', function (Request $request) {
    session(['parent_profile' => [
        'username' => $request->input('username', 'Parent User'),
        'picture' => $request->input('picture', 'bi-person-heart')
    ]]);
    return redirect('/parent/dashboard');
});

// Protected dashboard routes — redirect to login if not authenticated
Route::middleware('web')->group(function () {
    $guard = function ($view) {
        return function () use ($view) {
            if (session('client_logged_in') && !session('parent_logged_in')) {
                return redirect('/client/dashboard');
            }
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
