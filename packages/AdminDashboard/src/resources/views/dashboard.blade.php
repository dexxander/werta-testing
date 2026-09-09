@extends('admindashboard::layout')

@section('content')
<div>
    <div class="mb-6 sm:mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-dark">
            {{ $role === 'superadmin' ? 'Platform Administration' : 'Admin Overview' }}
        </h1>
        <p class="text-sm text-gray-500 mt-1">
            {{ $role === 'superadmin' ? 'System-wide oversight and administrator management.' : 'Operational summary for counselors, clients, and platform activity.' }}
        </p>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-2 {{ $role === 'superadmin' ? 'lg:grid-cols-5' : 'lg:grid-cols-4' }} gap-4 sm:gap-6 mb-8">

        @if($role === 'superadmin')
        <!-- Superadmin-only leading card -->
        <div class="bg-white rounded-2xl p-4 sm:p-6 border border-gold/20 shadow-sm flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4">
            <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-xl flex items-center justify-center shrink-0 bg-primary/10 text-primary">
                <i class="bi bi-person-badge text-xl sm:text-3xl"></i>
            </div>
            <div>
                <p class="text-[10px] sm:text-sm font-bold text-gray-500 uppercase tracking-wide">Total Admins</p>
                <h3 class="text-lg sm:text-2xl font-extrabold text-dark mt-1">{{ $totalAdmins }}</h3>
            </div>
        </div>
        @endif

        <div class="bg-white rounded-2xl p-4 sm:p-6 border border-gold/20 shadow-sm flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4">
            <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-xl flex items-center justify-center shrink-0 bg-gold/10 text-gold">
                <i class="bi bi-people text-xl sm:text-3xl"></i>
            </div>
            <div>
                <p class="text-[10px] sm:text-sm font-bold text-gray-500 uppercase tracking-wide">Active Clients</p>
                <h3 class="text-lg sm:text-2xl font-extrabold text-dark mt-1">{{ $activeClients }}</h3>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-4 sm:p-6 border border-gold/20 shadow-sm flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4">
            <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-xl flex items-center justify-center shrink-0 bg-gold/10 text-gold">
                <i class="bi bi-patch-check text-xl sm:text-3xl"></i>
            </div>
            <div>
                <p class="text-[10px] sm:text-sm font-bold text-gray-500 uppercase tracking-wide">Verified Counselors</p>
                <h3 class="text-lg sm:text-2xl font-extrabold text-dark mt-1">{{ $verifiedCounselors }}</h3>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-4 sm:p-6 border border-gold/20 shadow-sm flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4">
            <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-xl flex items-center justify-center shrink-0 bg-primary/10 text-primary">
                <i class="bi bi-calendar-check text-xl sm:text-3xl"></i>
            </div>
            <div>
                <p class="text-[10px] sm:text-sm font-bold text-gray-500 uppercase tracking-wide">Sessions (MTD)</p>
                <h3 class="text-lg sm:text-2xl font-extrabold text-dark mt-1">0</h3>
            </div>
        </div>

        <div class="bg-red-50 rounded-2xl p-4 sm:p-6 border border-red-200 shadow-sm flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4">
            <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-xl flex items-center justify-center shrink-0 bg-red-100 text-red-500">
                <i class="bi bi-exclamation-triangle text-xl sm:text-3xl"></i>
            </div>
            <div>
                <p class="text-[10px] sm:text-sm font-bold text-red-500 uppercase tracking-wide">Active Crisis Flags</p>
                <h3 class="text-lg sm:text-2xl font-extrabold text-red-600 mt-1">0</h3>
            </div>
        </div>
    </div>

    <!-- Two-column panels: Applications + Alerts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">

        <!-- Counselor Applications -->
        <div class="bg-white rounded-2xl p-4 sm:p-6 border border-gold/20 shadow-sm">
            <div class="flex justify-between items-center mb-4 sm:mb-6">
                <h2 class="text-lg font-bold text-dark">Counselor Applications</h2>
                <span class="text-xs font-bold uppercase tracking-wide bg-cream text-primary px-2.5 py-1 rounded-full">{{ $pendingCounselors }} Pending</span>
            </div>
            <div class="text-center text-gray-400 py-10">
                <i class="bi bi-inbox text-4xl block mb-2"></i>
                No pending applications.
            </div>
        </div>

        <!-- System Alerts -->
        <div class="bg-white rounded-2xl p-4 sm:p-6 border border-gold/20 shadow-sm">
            <h2 class="text-lg font-bold text-dark mb-4 sm:mb-6">System Alerts</h2>
            <div class="text-center text-gray-400 py-10">
                <i class="bi bi-shield-check text-4xl block mb-2"></i>
                No active alerts.
            </div>
        </div>
    </div>

    @if($role === 'superadmin')
    <!-- Administrators quick panel — superadmin only -->
    <div class="bg-white rounded-2xl p-4 sm:p-6 border border-gold/20 shadow-sm mb-8">
        <div class="flex justify-between items-center mb-4 sm:mb-6">
            <h2 class="text-lg font-bold text-dark">Administrator Accounts</h2>
            <a href="{{ url('/superadmin/administrators') }}" class="text-sm font-semibold text-primary hover:text-gold">
                Manage <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        <div class="text-center text-gray-400 py-10">
            <i class="bi bi-person-badge text-4xl block mb-2"></i>
            No administrator accounts yet.
        </div>
    </div>
    @endif

</div>
@endsection