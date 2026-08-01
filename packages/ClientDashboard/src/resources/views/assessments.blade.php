@extends('clientdashboard::layout')

@section('content')
<div>
    <div class="mb-6 sm:mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-[#2C2416]">My Assessments</h1>
        <p class="text-sm text-gray-500 mt-1">View your completed assessments and take new ones.</p>
    </div>

    <div class="bg-white rounded-2xl p-12 border border-[#C4A840]/20 shadow-sm text-center">
        <div class="w-20 h-20 rounded-full bg-[#F5EFE0] flex items-center justify-center mx-auto mb-4">
            <i class="bi bi-clipboard-check text-4xl text-[#C4A840]"></i>
        </div>
        <h3 class="text-lg font-bold text-[#2C2416] mb-2">No Assessments Taken</h3>
        <p class="text-sm text-gray-500 max-w-md mx-auto mb-6">You haven't completed any assessments yet. Taking an assessment is a great first step.</p>
        <a href="{{ url('/assessment') }}" class="inline-flex items-center gap-2 bg-[#C4A840] hover:bg-[#7B6B35] text-white px-6 py-2.5 rounded-lg font-semibold transition-colors shadow-sm">
            <i class="bi bi-arrow-right"></i> Take an Assessment
        </a>
    </div>
</div>
@endsection
