@extends('elearning::layout')

@section('elearning-body')
    @include('elearning::partials.learning-paths')
    <hr class="el-divider">
    @include('elearning::partials.certificates')
@endsection