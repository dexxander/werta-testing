@extends('parentdashboard::layout')

@section('content')
<div>
    <div class="mb-6 sm:mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-[#2C2416]">Monitor Progress</h1>
        <p class="text-sm text-gray-500 mt-1">Track your children's wellness journey and assessment results.</p>
    </div>

    <!-- Empty State -->
    <div class="bg-white rounded-2xl p-12 border border-[#C4A840]/20 shadow-sm text-center">
        <div class="w-20 h-20 rounded-full bg-[#F5EFE0] flex items-center justify-center mx-auto mb-4">
            <i class="bi bi-bar-chart-line text-4xl text-[#C4A840]"></i>
        </div>
        <h3 class="text-lg font-bold text-[#2C2416] mb-2">No Progress Data Yet</h3>
        <p class="text-sm text-gray-500 max-w-md mx-auto">Once your child completes assessments or e-learning modules, their progress will appear here for you to monitor.</p>
    </div>
</div>
@endsection
