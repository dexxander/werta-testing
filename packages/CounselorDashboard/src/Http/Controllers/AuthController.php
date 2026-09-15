<?php

namespace CounselorDashboard\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (session('counselor_logged_in')) {
            return redirect()->route('counselor.dashboard');
        }
        return view('counselor-dashboard::login');
    }

    public function login(Request $request)
    {
        /* DEV BYPASS: Development-only one-click authentication bypass */
        if (\App\Support\DevAuth::isBypassActive() && $request->has('dev_bypass')) {
            \App\Support\SessionRoles::clearAllRoles();
            session()->regenerate();
            session([
                'counselor_logged_in' => true,
                'counselor_name'      => 'Counselor User',
                'dev_bypass_session'  => true,
            ]);
            return redirect()->route('counselor.dashboard');
        }

        $username = $request->input('username');
        $password = $request->input('password');

        // TODO: replace with real credential check (Auth::attempt) once a
        // Counselor/User model + migration exist.
        if ($username === 'counselor' && $password === 'counselor') {
            \App\Support\SessionRoles::clearAllRoles();
            session()->regenerate();
            session([
                'counselor_logged_in' => true,
                'counselor_name'      => $username, // TODO: use real display name from DB once auth is real
            ]);
            return redirect()->route('counselor.dashboard');
        }

        return redirect()->route('counselor.login')
            ->with('error', 'Invalid username or password.');
    }

    public function logout()
    {
        \App\Support\SessionRoles::clearAllRoles();
        session()->regenerate();
        return redirect('/');
    }
}