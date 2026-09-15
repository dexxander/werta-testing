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
    /* DEV BYPASS: Development-only one-click authentication bypass */
    if (\App\Support\DevAuth::isBypassActive() && $request->has('dev_bypass')) {
        \App\Support\SessionRoles::clearAllRoles();
        session()->regenerate();
        session([
            'parent_logged_in' => true,
            'parent_profile' => ['username' => 'Parent User', 'picture' => 'bi-person-heart'],
            'dev_bypass_session' => true,
        ]);
        return redirect('/parent/dashboard');
    }

    $username = $request->input('username');
    $password = $request->input('password');

    if ($username === 'parent' && $password === 'parent') {
        \App\Support\SessionRoles::clearAllRoles();
        session()->regenerate();
        session(['parent_logged_in' => true]);
        session(['parent_profile' => ['username' => 'Parent User', 'picture' => 'bi-person-heart']]);
        return redirect('/');
    }

    return redirect('/parent/login')->with('error', 'Invalid username or password.');
});

// Logout
Route::get('/parent/logout', function () {
    \App\Support\SessionRoles::clearAllRoles();
    session()->regenerate();
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
            if (!session('parent_logged_in')) {
                return redirect('/parent/login');
            }
            return view('parentdashboard::' . $view);
        };
    };

    Route::get('/parent/dashboard', $guard('dashboard'))->name('parent.dashboard');
    Route::get('/parent/progress', $guard('progress'))->name('parent.progress');
    Route::get('/parent/appointments', $guard('appointments'))->name('parent.appointments');
    Route::get('/parent/messages', $guard('messages'))->name('parent.messages');
});
