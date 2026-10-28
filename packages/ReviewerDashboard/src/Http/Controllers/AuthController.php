<?php

namespace ReviewerDashboard\Http\Controllers;

use AdminDashboard\Models\Reviewer;
use App\Support\DemoAuth;
use App\Support\DevAuth;
use App\Support\SessionRoles;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Submissions\Models\Submission;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (DemoAuth::reviewer()) {
            return redirect()->route('reviewer.dashboard');
        }

        return view('reviewerdashboard::login');
    }

    public function login(Request $request)
    {
        // TODO: replace with real credentials (Auth::attempt) once reviewers have user accounts.
        // Demo: reviewer / reviewer signs in as the first approved reviewer.
        $devBypass = DevAuth::isBypassActive() && $request->has('dev_bypass');
        $valid = $request->input('username') === 'reviewer' && $request->input('password') === 'reviewer';

        if (! $devBypass && ! $valid) {
            return redirect()->route('reviewer.login')->with('error', 'Invalid username or password.');
        }

        $reviewer = Reviewer::approved()->orderBy('name')->first();
        if (! $reviewer) {
            return redirect()->route('reviewer.login')->with('error', 'No approved reviewer accounts yet. Run the seeders first.');
        }

        DemoAuth::loginAs('reviewer', $reviewer);
        if ($devBypass) {
            session(['dev_bypass_session' => true]);
        }

        return redirect()->route('reviewer.dashboard');
    }

    public function logout()
    {
        SessionRoles::clearAllRoles();
        session()->regenerate();

        return redirect('/');
    }

    /** Public application form for professionals who want to review. */
    public function showApply()
    {
        return view('reviewerdashboard::apply');
    }

    public function apply(Request $request)
    {
        $data = $request->validate([
            'name'            => 'required|string|max:120',
            'email'           => 'required|email|max:160|unique:reviewers,email',
            'title'           => 'required|string|max:120',
            'field'           => 'required|in:' . implode(',', array_keys(Submission::CATEGORIES)),
            'institution'     => 'nullable|string|max:160',
            'qualifications'  => 'required|string|max:160',
            'registration_no' => 'nullable|string|max:60',
            'bio'             => 'nullable|string|max:1000',
            'proof'           => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'agree'           => 'accepted',
        ]);

        $slug = Str::slug($data['name']);
        $base = $slug;
        $i = 1;
        while (Reviewer::where('slug', $slug)->exists()) {
            $slug = $base . '-' . ++$i;
        }

        Reviewer::create([
            'name' => $data['name'], 'slug' => $slug, 'email' => $data['email'], 'title' => $data['title'],
            'field' => $data['field'], 'institution' => $data['institution'] ?? null,
            'qualifications' => $data['qualifications'], 'registration_no' => $data['registration_no'] ?? null,
            'bio' => $data['bio'] ?? null,
            // Credentials stay on the private disk; only admins should see them.
            'proof_path' => $request->hasFile('proof') ? $request->file('proof')->store('reviewer-proofs', 'local') : null,
            'status' => 'pending', 'show_publicly' => true,
        ]);

        return redirect()->route('reviewer.apply')
            ->with('mock_ok', __('Thank you. Your application was received. An admin will verify your credentials before you can review.'));
    }
}
