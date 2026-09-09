@extends('clientdashboard::layout')

@section('content')
<div>
    <div class="mb-6 sm:mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-dark">My Assessments</h1>
        <p class="text-sm text-gray-500 mt-1">View your completed assessments and take new ones.</p>
    </div>

    <div class="bg-white rounded-2xl p-12 border border-gold/20 shadow-sm text-center">
        <div class="w-20 h-20 rounded-full bg-cream flex items-center justify-center mx-auto mb-4">
            <i class="bi bi-clipboard-check text-4xl text-gold"></i>
        </div>
        <h3 class="text-lg font-bold text-dark mb-2">No Assessments Taken</h3>
        <p class="text-sm text-gray-500 max-w-md mx-auto mb-6">You haven't completed any assessments yet. Taking an assessment is a great first step.</p>
        <a href="{{ url('/assessment') }}" class="inline-flex items-center gap-2 bg-gold hover:bg-primary text-white px-6 py-2.5 rounded-lg font-semibold transition-colors shadow-sm">
            <i class="bi bi-arrow-right"></i> Take an Assessment
        </a>
    </div>
</div>
@endsection
