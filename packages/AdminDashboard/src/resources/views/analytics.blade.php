@extends('admindashboard::layout')

@section('content')
<div>
    <div class="mb-6 sm:mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-dark">{{ __('Analytics') }}</h1>
        <p class="text-sm text-gray-500 mt-1">{{ __('Platform usage trends, engagement, and performance metrics.') }}</p>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <div class="bg-white rounded-2xl p-6 border border-gold/20 shadow-sm">
            <h2 class="text-lg font-bold text-dark mb-6">{{ __('User Growth Over Time') }}</h2>
            <div class="h-72 w-full flex items-center justify-center">
                <div class="text-center text-gray-400">
                    <i class="bi bi-graph-up-arrow text-5xl"></i>
                    <p class="text-sm mt-2">{{ __('No data available yet.') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-gold/20 shadow-sm">
            <h2 class="text-lg font-bold text-dark mb-6">{{ __('Sessions Completed (Monthly)') }}</h2>
            <div class="h-72 w-full flex items-center justify-center">
                <div class="text-center text-gray-400">
                    <i class="bi bi-bar-chart text-5xl"></i>
                    <p class="text-sm mt-2">{{ __('No data available yet.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="bg-white rounded-2xl p-4 sm:p-6 border border-gold/20 shadow-sm">
        <h2 class="text-lg font-bold text-dark mb-4 sm:mb-6">{{ __('Platform Summary') }}</h2>
        <div class="text-center text-gray-400 py-10">
            <i class="bi bi-clipboard-data text-4xl block mb-2"></i>
            {{ __('Analytics will populate once platform activity data is available.') }}
        </div>
    </div>
</div>
@endsection