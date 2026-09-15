@extends('clientdashboard::layout')

@section('content')
@php
    // Safe defaults keep the dashboard's empty states working without demo data.
    $dashboard_stats = [];
    $assessment_scores = [];
    $weekly_activity = [];
    $recent_activities = [];
@endphp

{{-- ============================================================
     TEMP DUMMY DATA — FOR PREVIEW ONLY
     Delete this entire block to return the dashboard to its empty states.
     The safe defaults above ensure the view still works after removal.
     ============================================================ --}}
@php
    // TEMP DUMMY DATA START
    $dashboard_stats = [
        'assessments_done' => 8,
        'modules_finished' => 4,
        'next_appointment' => '12 Sep',
        'notifications' => 3,
    ];
    $assessment_scores = [72, 78, 81, 88, 91];
    $weekly_activity = [2.5, 4, 3, 5.5, 4.5, 1, 0];
    $recent_activities = [
        ['date' => '01 Sep 2026', 'type' => 'Wellness Assessment', 'status' => 'Completed'],
        ['date' => '30 Aug 2026', 'type' => 'E-Learning Module', 'status' => 'In Progress'],
        ['date' => '28 Aug 2026', 'type' => 'Counselling Appointment', 'status' => 'Confirmed'],
    ];
    // TEMP DUMMY DATA END
@endphp

<div>
    <div class="mb-6 sm:mb-8 flex justify-between items-end">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-dark">Client Overview</h1>
            <p class="text-sm text-gray-500 mt-1">Welcome back, {{ session('client_profile.username', 'Client User') }}. Here's a summary of your mental wellness journey.</p>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
        <div class="bg-white rounded-2xl p-4 sm:p-6 border border-gold/20 shadow-sm flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4">
            <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-xl flex items-center justify-center shrink-0 bg-gold/10 text-gold">
                <i class="bi bi-clipboard-check text-xl sm:text-3xl"></i>
            </div>
            <div>
                <p class="text-[10px] sm:text-sm font-bold text-gray-500 uppercase tracking-wide">Assessments Done</p>
                <h3 class="text-lg sm:text-2xl font-extrabold text-dark mt-1">{{ $dashboard_stats['assessments_done'] ?? 0 }}</h3>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl p-4 sm:p-6 border border-gold/20 shadow-sm flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4">
            <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-xl flex items-center justify-center shrink-0 bg-gold/10 text-gold">
                <i class="bi bi-book text-xl sm:text-3xl"></i>
            </div>
            <div>
                <p class="text-[10px] sm:text-sm font-bold text-gray-500 uppercase tracking-wide">Modules Finished</p>
                <h3 class="text-lg sm:text-2xl font-extrabold text-dark mt-1">{{ $dashboard_stats['modules_finished'] ?? 0 }}</h3>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-4 sm:p-6 border border-gold/20 shadow-sm flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4">
            <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-xl flex items-center justify-center shrink-0 bg-primary/10 text-primary">
                <i class="bi bi-calendar-check text-xl sm:text-3xl"></i>
            </div>
            <div>
                <p class="text-[10px] sm:text-sm font-bold text-gray-500 uppercase tracking-wide">Next Appointment</p>
                <h3 class="text-lg sm:text-2xl font-extrabold text-dark mt-1">{{ $dashboard_stats['next_appointment'] ?? 0 }}</h3>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-4 sm:p-6 border border-gold/20 shadow-sm flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4">
            <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-xl flex items-center justify-center shrink-0 bg-primary/10 text-primary">
                <i class="bi bi-bell text-xl sm:text-3xl"></i>
            </div>
            <div>
                <p class="text-[10px] sm:text-sm font-bold text-gray-500 uppercase tracking-wide">Notifications</p>
                <h3 class="text-lg sm:text-2xl font-extrabold text-dark mt-1">{{ $dashboard_stats['notifications'] ?? 0 }}</h3>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <div class="bg-white rounded-2xl p-6 border border-gold/20 shadow-sm">
            <h2 class="text-lg font-bold text-dark mb-6">My Assessment Score Trend</h2>
            <div class="h-72 w-full flex items-center justify-center">
                @if(!empty($assessment_scores))
                    <div class="w-full flex items-end justify-center gap-2 h-48">
                        @foreach($assessment_scores as $score)
                            <div class="w-8 bg-gold rounded-t" style="height:{{ min(100, max(0, $score)) }}%;" title="{{ $score }}%"></div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-gray-400"><i class="bi bi-bar-chart text-5xl"></i><p class="text-sm mt-2">No assessment data available yet.</p></div>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-gold/20 shadow-sm">
            <h2 class="text-lg font-bold text-dark mb-6">My Platform Activity (Hours / Week)</h2>
            <div class="h-72 w-full flex items-center justify-center">
                @if(!empty($weekly_activity))
                    <div class="w-full flex items-end justify-center gap-2 h-48">
                        @foreach($weekly_activity as $hours)
                            <div class="w-8 bg-primary rounded-t" style="height:{{ min(100, max(0, $hours * 15)) }}%;" title="{{ $hours }} hours"></div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-gray-400"><i class="bi bi-graph-up text-5xl"></i><p class="text-sm mt-2">No activity data available yet.</p></div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Activities Table -->
    <div class="bg-white rounded-2xl p-4 sm:p-6 border border-gold/20 shadow-sm">
        <h2 class="text-lg font-bold text-dark mb-4 sm:mb-6">My Recent Activities</h2>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-cream text-primary font-bold border-b border-gold/20 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-4 py-3 rounded-tl-lg">Date</th>
                        <th class="px-4 py-3">Activity Type</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right rounded-tr-lg">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($recent_activities as $activity)
                        <tr><td class="px-4 py-3">{{ $activity['date'] ?? '' }}</td><td class="px-4 py-3">{{ $activity['type'] ?? '' }}</td><td class="px-4 py-3">{{ $activity['status'] ?? '' }}</td><td class="px-4 py-3 text-right">View</td></tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-12 text-center text-gray-400"><i class="bi bi-inbox text-4xl block mb-2"></i>No activities recorded yet. Start exploring assessments or learning modules.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
