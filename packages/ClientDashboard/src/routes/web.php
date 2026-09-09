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
    /* DEV BYPASS: Development-only one-click authentication bypass */
    if (\App\Support\DevAuth::isBypassActive() && $request->has('dev_bypass')) {
        session()->forget('parent_logged_in');
        session([
            'client_logged_in' => true,
            'client_profile' => ['username' => 'Client User', 'picture' => 'bi-person-circle'],
            'dev_bypass_session' => true,
        ]);
        return redirect('/client/dashboard');
    }

    $username = $request->input('username');
    $password = $request->input('password');

    if ($username === 'client' && $password === 'client') {
        session()->forget('parent_logged_in');
        session(['client_logged_in' => true]);
        session(['client_profile' => ['username' => 'Client User', 'picture' => 'bi-person-circle']]);
        return redirect('/');
    }

    return redirect('/client/login')->with('error', 'Invalid username or password.');
});

// Logout
Route::get('/client/logout', function () {
    session()->forget('client_logged_in');
    session()->forget('client_profile');
    return redirect('/');
});

// Custom Package Registration Route
Route::get('/auth/register', function (\Illuminate\Http\Request $request) {
    if (session('client_logged_in') || session('parent_logged_in')) {
        return redirect('/');
    }
    return view('clientdashboard::register');
});

Route::post('/auth/register', function (Request $request) {
    $role = $request->input('role');
    
    if ($role === 'client') {
        $ic = $request->input('ic_number');
        if (strlen($ic) >= 2) {
            $yearStr = substr($ic, 0, 2);
            $year = (int)$yearStr;
            $year = $year > 50 ? 1900 + $year : 2000 + $year;
            $currentYear = (int)date('Y');
            
            if (($currentYear - $year) < 17) {
                return redirect('/auth/register')->with('error', 'You must be at least 17 years old to register as a client.');
            }
        }
        
        session()->forget('parent_logged_in');
        session(['client_logged_in' => true]);
        session(['client_profile' => ['username' => 'New Client', 'picture' => 'bi-person']]);
        return redirect('/');
    } else {
        session()->forget('client_logged_in');
        session(['parent_logged_in' => true]);
        session(['parent_profile' => ['username' => 'New Parent', 'picture' => 'bi-person-heart']]);
        return redirect('/');
    }
});

// Handle Profile Update
Route::post('/client/profile', function (Request $request) {
    session(['client_profile' => [
        'username' => $request->input('username', 'Client User'),
        'picture' => $request->input('picture', 'bi-person-circle')
    ]]);
    return redirect('/client/dashboard');
});

// Protected dashboard routes
Route::middleware('web')->group(function () {
    $guard = function ($view) {
        return function () use ($view) {
            if (session('parent_logged_in') && !session('client_logged_in')) {
                return redirect('/parent/dashboard');
            }
            if (!session('client_logged_in')) {
                return redirect('/client/login');
            }
            return view('clientdashboard::' . $view);
        };
    };

    Route::get('/client/dashboard', $guard('dashboard'))->name('client.dashboard');
    Route::get('/client/assessments', $guard('assessments'))->name('client.assessments');
    Route::get('/client/learning', $guard('learning'))->name('client.learning');
    Route::get('/client/appointments', $guard('appointments'))->name('client.appointments');
    Route::get('/client/messages', $guard('messages'))->name('client.messages');
});
