@extends('elearning::layout')

@section('elearning-body')
    @include('elearning::partials.categories')
    <hr class="el-divider">
    @include('elearning::partials.featured-courses')
@endsection