<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/about', function () {
    return view('about');
});

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
