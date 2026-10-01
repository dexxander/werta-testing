<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/about', function () {
    return view('about');
});

use AdminDashboard\Models\Counselor;
use Illuminate\Http\Request;

Route::get('/counselors', function (Request $request) {
    $specialty = $request->query('specialty');
    $language  = $request->query('language');
    $mode      = $request->query('mode');
    $state     = $request->query('state');

    $query = Counselor::where('status', 'approved');

    if (!empty($specialty)) {
        $query->whereJsonContains('specialties', $specialty);
    }
    if (!empty($language)) {
        $query->whereJsonContains('languages', $language);
    }
    if (!empty($mode)) {
        $query->whereJsonContains('session_modes', $mode);
    }
    if (!empty($state)) {
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
