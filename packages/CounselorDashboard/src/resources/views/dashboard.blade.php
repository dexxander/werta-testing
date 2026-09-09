@extends('counselor-dashboard::layout')

@section('content')
<div  @open-session-report.window="isModalOpen = true">
    
    <div class="mb-6 sm:mb-8 flex justify-between items-end">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-dark">Counselor Workspace</h1>
            <p class="text-sm text-gray-500 mt-1">Manage your appointments, client progress, and clinical reports.</p>
        </div>
        <div class="flex gap-3">
            <button @click="isModalOpen = true" class="hidden sm:flex items-center gap-2 bg-gold hover:bg-primary text-white px-4 py-2 rounded-lg font-semibold transition-colors shadow-sm">
                <i class="bi bi-file-earmark-plus"></i> Quick Report
            </button>
        </div>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
        <div class="bg-white rounded-2xl p-4 sm:p-6 border border-gold/20 shadow-sm flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4">
            <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-xl flex items-center justify-center shrink-0 bg-gold/10 text-gold">
                <i class="bi bi-camera-video text-xl sm:text-3xl"></i>
            </div>
            <div>
                <p class="text-[10px] sm:text-sm font-bold text-gray-500 uppercase tracking-wide">Today's Sessions</p>
                <h3 class="text-lg sm:text-2xl font-extrabold text-dark mt-1">{{ $stats['todays_sessions'] ?? '0' }}</h3>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl p-4 sm:p-6 border border-gold/20 shadow-sm flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4">
            <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-xl flex items-center justify-center shrink-0 bg-gold/10 text-gold">
                <i class="bi bi-people text-xl sm:text-3xl"></i>
            </div>
            <div>
                <p class="text-[10px] sm:text-sm font-bold text-gray-500 uppercase tracking-wide">Active Clients</p>
                <h3 class="text-lg sm:text-2xl font-extrabold text-dark mt-1">{{ $stats['active_clients'] ?? '0' }}</h3>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-4 sm:p-6 border border-gold/20 shadow-sm flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4">
            <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-xl flex items-center justify-center shrink-0 bg-primary/10 text-primary">
                <i class="bi bi-journal-text text-xl sm:text-3xl"></i>
            </div>
            <div>
                <p class="text-[10px] sm:text-sm font-bold text-gray-500 uppercase tracking-wide">Articles Published</p>
                <h3 class="text-lg sm:text-2xl font-extrabold text-dark mt-1">{{ $stats['published_articles'] ?? '0' }}</h3>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-4 sm:p-6 border border-gold/20 shadow-sm flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4">
            <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-xl flex items-center justify-center shrink-0 bg-red-50 text-red-500">
                <i class="bi bi-exclamation-triangle text-xl sm:text-3xl"></i>
            </div>
            <div>
                <p class="text-[10px] sm:text-sm font-bold text-gray-500 uppercase tracking-wide">Pending Reports</p>
                <h3 class="text-lg sm:text-2xl font-extrabold text-dark mt-1">{{ $stats['pending_reports'] ?? '0' }}</h3>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <div class="bg-white rounded-2xl p-6 border border-gold/20 shadow-sm">
            <h2 class="text-lg font-bold text-dark mb-6">Aggregate Client Mood Trends</h2>
            <div class="h-72 w-full flex items-center justify-center">
                <div class="text-center text-gray-400">
                    <i class="bi bi-graph-up text-5xl"></i>
                    <p class="text-sm mt-2">Insufficient check-in data to map mood trends.</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-gold/20 shadow-sm">
            <h2 class="text-lg font-bold text-dark mb-6">Consultation Hours (This Week)</h2>
            <div class="h-72 w-full flex items-center justify-center">
                <div class="text-center text-gray-400">
                    <i class="bi bi-clock-history text-5xl"></i>
                    <p class="text-sm mt-2">No completed sessions this week.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-4 sm:p-6 border border-gold/20 shadow-sm">
        <h2 class="text-lg font-bold text-dark mb-4 sm:mb-6">Upcoming Appointments</h2>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-cream text-primary font-bold border-b border-gold/20 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-4 py-3 rounded-tl-lg">Time</th>
                        <th class="px-4 py-3">Client ID</th>
                        <th class="px-4 py-3">Type</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right rounded-tr-lg">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($appointments as $appointment)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-4 font-medium text-gray-900">{{ $appointment->time }}</td>
                            <td class="px-4 py-4">{{ $appointment->client_label }}</td>
                            <td class="px-4 py-4">
                                <span class="px-2 py-1 bg-blue-50 text-blue-700 text-xs font-semibold rounded">{{ $appointment->type }}</span>
                            </td>
                            <td class="px-4 py-4">
                                <span class="px-2 py-1 bg-green-50 text-green-700 text-xs font-semibold rounded">{{ $appointment->status }}</span>
                            </td>
                            <td class="px-4 py-4 text-right">
                                <button class="text-sm font-medium text-gold hover:text-primary mr-3">View History</button>
                                <button class="text-sm font-medium bg-gold text-white px-3 py-1 rounded hover:bg-primary">Join Room</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-12 text-center text-gray-400">
                                <i class="bi bi-calendar-x text-4xl block mb-2"></i>
                                No upcoming appointments.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection