@extends('parentdashboard::layout')

@section('content')
<div>
    <div class="mb-6 sm:mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-dark">Monitor Progress</h1>
        <p class="text-sm text-gray-500 mt-1">Track your children's wellness journey and assessment results.</p>
    </div>

    <!-- Empty State -->
    <div class="bg-white rounded-2xl p-12 border border-gold/20 shadow-sm text-center">
        <div class="w-20 h-20 rounded-full bg-cream flex items-center justify-center mx-auto mb-4">
            <i class="bi bi-bar-chart-line text-4xl text-gold"></i>
        </div>
        <h3 class="text-lg font-bold text-dark mb-2">No Progress Data Yet</h3>
        <p class="text-sm text-gray-500 max-w-md mx-auto">Once your child completes assessments or e-learning modules, their progress will appear here for you to monitor.</p>
    </div>
</div>
@endsection
