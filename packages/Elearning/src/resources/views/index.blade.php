@extends('elearning::layout')

{{-- The landing page is intentionally composed from the same module sections
     used by the e-learning tabs, so the public entry point feels like a real
     learning catalogue instead of a single promotional screen. --}}
@section('elearning-body')
    @include('elearning::partials.categories')
    <hr class="el-divider">
    @include('elearning::partials.featured-courses')
    @include('elearning::partials.learning-paths')
    @include('elearning::partials.testimonials')
@endsection
