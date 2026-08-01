@extends('clientdashboard::layout')

@section('content')
<div>
    <div class="mb-6 sm:mb-8 flex justify-between items-end">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-[#2C2416]">Client Overview</h1>
            <p class="text-sm text-gray-500 mt-1">Welcome back. Here's a summary of your mental wellness journey.</p>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
        <div class="bg-white rounded-2xl p-4 sm:p-6 border border-[#C4A840]/20 shadow-sm flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4">
            <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-xl flex items-center justify-center shrink-0 bg-[#C4A840]/10 text-[#C4A840]">
                <i class="bi bi-clipboard-check text-xl sm:text-3xl"></i>
            </div>
            <div>
                <p class="text-[10px] sm:text-sm font-bold text-gray-500 uppercase tracking-wide">Assessments Done</p>
                <h3 class="text-lg sm:text-2xl font-extrabold text-[#2C2416] mt-1">0</h3>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl p-4 sm:p-6 border border-[#C4A840]/20 shadow-sm flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4">
            <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-xl flex items-center justify-center shrink-0 bg-[#C4A840]/10 text-[#C4A840]">
                <i class="bi bi-book text-xl sm:text-3xl"></i>
            </div>
            <div>
                <p class="text-[10px] sm:text-sm font-bold text-gray-500 uppercase tracking-wide">Modules Finished</p>
                <h3 class="text-lg sm:text-2xl font-extrabold text-[#2C2416] mt-1">0</h3>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-4 sm:p-6 border border-[#C4A840]/20 shadow-sm flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4">
            <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-xl flex items-center justify-center shrink-0 bg-[#7B6B35]/10 text-[#7B6B35]">
                <i class="bi bi-calendar-check text-xl sm:text-3xl"></i>
            </div>
            <div>
                <p class="text-[10px] sm:text-sm font-bold text-gray-500 uppercase tracking-wide">Next Appointment</p>
                <h3 class="text-lg sm:text-2xl font-extrabold text-[#2C2416] mt-1">0</h3>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-4 sm:p-6 border border-[#C4A840]/20 shadow-sm flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4">
            <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-xl flex items-center justify-center shrink-0 bg-[#7B6B35]/10 text-[#7B6B35]">
                <i class="bi bi-bell text-xl sm:text-3xl"></i>
            </div>
            <div>
                <p class="text-[10px] sm:text-sm font-bold text-gray-500 uppercase tracking-wide">Notifications</p>
                <h3 class="text-lg sm:text-2xl font-extrabold text-[#2C2416] mt-1">0</h3>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <div class="bg-white rounded-2xl p-6 border border-[#C4A840]/20 shadow-sm">
            <h2 class="text-lg font-bold text-[#2C2416] mb-6">My Assessment Score Trend</h2>
            <div class="h-72 w-full flex items-center justify-center">
                <div class="text-center text-gray-400">
                    <i class="bi bi-bar-chart text-5xl"></i>
                    <p class="text-sm mt-2">No assessment data available yet.</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-[#C4A840]/20 shadow-sm">
            <h2 class="text-lg font-bold text-[#2C2416] mb-6">My Platform Activity (Hours / Week)</h2>
            <div class="h-72 w-full flex items-center justify-center">
                <div class="text-center text-gray-400">
                    <i class="bi bi-graph-up text-5xl"></i>
                    <p class="text-sm mt-2">No activity data available yet.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities Table -->
    <div class="bg-white rounded-2xl p-4 sm:p-6 border border-[#C4A840]/20 shadow-sm">
        <h2 class="text-lg font-bold text-[#2C2416] mb-4 sm:mb-6">My Recent Activities</h2>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-[#F5EFE0] text-[#7B6B35] font-bold border-b border-[#C4A840]/20 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-4 py-3 rounded-tl-lg">Date</th>
                        <th class="px-4 py-3">Activity Type</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right rounded-tr-lg">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr>
                        <td colspan="4" class="px-4 py-12 text-center text-gray-400">
                            <i class="bi bi-inbox text-4xl block mb-2"></i>
                            No activities recorded yet. Start exploring assessments or learning modules.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
