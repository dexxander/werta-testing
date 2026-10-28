<?php

use AdminDashboard\Models\Counselor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/language/{locale}', function (Request $request, string $locale) {
    $request->session()->put('locale', $locale);

    return redirect()->back();
})->whereIn('locale', ['en', 'ms', 'zh'])->name('language.switch');

Route::get('/', function () {
    return view('welcome');
});

/*
| DEMO ONLY: one-click sign-in as any persona for the monetisation demo.
| Off unless APP_ENV=local (or DEMO_LOGIN=true). See config/dev.php.
*/
Route::get('/demo', function () {
    abort_unless(\App\Support\DemoAuth::enabled(), 404);
    $reviewers = \Illuminate\Support\Facades\Schema::hasTable('reviewers')
        ? \AdminDashboard\Models\Reviewer::approved()->orderBy('name')->get()
        : collect();

    return view('demo', compact('reviewers'));
})->name('demo.index');

Route::post('/demo/{persona}', function (Request $request, string $persona) {
    abort_unless(\App\Support\DemoAuth::enabled(), 404);
    $reviewer = $persona === 'reviewer'
        ? \AdminDashboard\Models\Reviewer::approved()->find($request->input('reviewer_id'))
        : null;
    $to = \App\Support\DemoAuth::loginAs($persona, $reviewer);
    abort_if($to === null, 404);

    return redirect($to)->with('mock_ok', __('Signed in as a demo account.'));
})->whereIn('persona', ['client', 'parent', 'counselor', 'reviewer', 'admin', 'superadmin'])->name('demo.login');

Route::get('/about', function () {
    return view('about');
});

Route::get('/counselors', function (Request $request) {
    $specialty = $request->query('specialty');
    $language = $request->query('language');
    $mode = $request->query('mode');
    $state = $request->query('state');

    $query = Counselor::where('status', 'approved');

    if (! empty($specialty)) {
        $query->whereJsonContains('specialties', $specialty);
    }
    if (! empty($language)) {
        $query->whereJsonContains('languages', $language);
    }
    if (! empty($mode)) {
        $query->whereJsonContains('session_modes', $mode);
    }
    if (! empty($state)) {
        $query->where('state', $state);
    }

    $counselors = $query->orderBy('name')->get();
    $totalApprovedCount = Counselor::where('status', 'approved')->count();

    $allSpecialties = [
        'Academic Anxiety',
        'Depression & Mood',
        'Relationship Counseling',
        'Career Transitions',
    ];

    $allLanguages = [
        'English',
        'Bahasa Melayu',
        'Mandarin',
    ];

    $allModes = [
        'Online',
        'In person',
    ];

    $allStates = Counselor::where('status', 'approved')
        ->whereNotNull('state')
        ->distinct()
        ->orderBy('state')
        ->pluck('state')
        ->toArray();

    return view('counselors', compact(
        'counselors',
        'specialty',
        'language',
        'mode',
        'state',
        'totalApprovedCount',
        'allSpecialties',
        'allLanguages',
        'allModes',
        'allStates'
    ));
})->name('public.counselors');

Route::get('/counselors/{id}', function ($id) {
    $counselor = Counselor::where('id', $id)
        ->where('status', 'approved')
        ->firstOrFail();

    return view('counselor-profile', compact('counselor'));
})->name('public.counselors.show');

Route::get('/assessment', function () {
    return view('assessment');
});

Route::get('/assessment/questions', function () {
    return view('assessment-questions');
});

Route::get('/assessment/processing', function () {
    return view('assessment-processing');
});

Route::get('/assessment/email', function () {
    return view('assessment-email');
});

Route::get('/assessment/results', function () {
    return view('assessment-results');
});
