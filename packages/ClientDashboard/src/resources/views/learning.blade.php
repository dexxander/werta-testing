@extends('clientdashboard::layout')

@section('content')
<div>
    <div class="mb-6 sm:mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-dark">E-Learning Modules</h1>
        <p class="text-sm text-gray-500 mt-1">Access educational resources to support your mental wellness.</p>
    </div>

    <div class="bg-white rounded-2xl p-12 border border-gold/20 shadow-sm text-center">
        <div class="w-20 h-20 rounded-full bg-cream flex items-center justify-center mx-auto mb-4">
            <i class="bi bi-book text-4xl text-gold"></i>
        </div>
        <h3 class="text-lg font-bold text-dark mb-2">No Modules Enrolled</h3>
        <p class="text-sm text-gray-500 max-w-md mx-auto mb-6">Browse our library of e-learning modules to start learning.</p>
        <a href="{{ url('/e-learning') }}" class="inline-flex items-center gap-2 bg-gold hover:bg-primary text-white px-6 py-2.5 rounded-lg font-semibold transition-colors shadow-sm">
            <i class="bi bi-arrow-right"></i> Browse Modules
        </a>
    </div>
</div>
@endsection
