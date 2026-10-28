@extends('admindashboard::layout')

@section('content')
@php use Articles\Support\MockBilling as MB; @endphp
<div>
    <div class="mb-6 sm:mb-8 flex flex-wrap justify-between items-end gap-3">
        <div>
            <p class="text-xs font-bold uppercase tracking-[3px] text-primary mb-1">{{ __(ucfirst($role).' Portal') }}</p>
            <h1 class="font-display text-3xl sm:text-4xl text-dark">
                {{ \App\Support\SiteText::translate($role === 'superadmin' ? 'Platform Administration' : 'Admin Overview') }}
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                {{ \App\Support\SiteText::translate($role === 'superadmin' ? 'System-wide oversight and administrator management.' : 'Operational summary for counselors, clients, and platform activity.') }}
            </p>
        </div>
        @if($stats)
            <span class="text-[10px] font-bold tracking-wider text-primary bg-cream-light border border-gold rounded-full px-3 py-1">{{ __('DEMO DATA — live session activity on top of baseline figures') }}</span>
        @endif
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-2 {{ $role === 'superadmin' ? 'lg:grid-cols-5' : 'lg:grid-cols-4' }} gap-4 sm:gap-6 mb-6">

        @if($role === 'superadmin')
        <!-- Superadmin-only leading card -->
        <div class="bg-white rounded-2xl p-4 sm:p-6 card-ring flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4">
            <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-xl flex items-center justify-center shrink-0 bg-primary/10 text-primary">
                <i class="bi bi-person-badge text-xl sm:text-3xl"></i>
            </div>
            <div>
                <p class="text-[10px] sm:text-sm font-bold text-gray-500 uppercase tracking-wide">{{ __('Total Admins') }}</p>
                <h3 class="text-lg sm:text-2xl font-extrabold text-dark mt-1">{{ \App\Support\SiteText::translate($totalAdmins) }}</h3>
            </div>
        </div>
        @endif

        <div class="bg-white rounded-2xl p-4 sm:p-6 card-ring flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4">
            <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-xl flex items-center justify-center shrink-0 bg-gold/10 text-primary">
                <i class="bi bi-people text-xl sm:text-3xl"></i>
            </div>
            <div>
                <p class="text-[10px] sm:text-sm font-bold text-gray-500 uppercase tracking-wide">{{ __('Active Clients') }}</p>
                <h3 class="text-lg sm:text-2xl font-extrabold text-dark mt-1">{{ \App\Support\SiteText::translate($activeClients) }}</h3>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-4 sm:p-6 card-ring flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4">
            <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-xl flex items-center justify-center shrink-0 bg-gold/10 text-primary">
                <i class="bi bi-patch-check text-xl sm:text-3xl"></i>
            </div>
            <div>
                <p class="text-[10px] sm:text-sm font-bold text-gray-500 uppercase tracking-wide">{{ __('Verified Counselors') }}</p>
                <h3 class="text-lg sm:text-2xl font-extrabold text-dark mt-1">{{ \App\Support\SiteText::translate($verifiedCounselors) }}</h3>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-4 sm:p-6 card-ring flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4">
            <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-xl flex items-center justify-center shrink-0 bg-primary/10 text-primary">
                <i class="bi bi-calendar-check text-xl sm:text-3xl"></i>
            </div>
            <div>
                <p class="text-[10px] sm:text-sm font-bold text-gray-500 uppercase tracking-wide">{{ __('Sessions (MTD)') }}</p>
                <h3 class="text-lg sm:text-2xl font-extrabold text-dark mt-1">0</h3>
            </div>
        </div>

        <div class="bg-red-50 rounded-2xl p-4 sm:p-6 border border-red-200 shadow-sm flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4">
            <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-xl flex items-center justify-center shrink-0 bg-red-100 text-red-500">
                <i class="bi bi-exclamation-triangle text-xl sm:text-3xl"></i>
            </div>
            <div>
                <p class="text-[10px] sm:text-sm font-bold text-red-500 uppercase tracking-wide">{{ __('Active Crisis Flags') }}</p>
                <h3 class="text-lg sm:text-2xl font-extrabold text-red-600 mt-1">0</h3>
            </div>
        </div>
    </div>

    @if($stats)
        @php
            $cur = $stats['current'];
            $inReview = $stats['pipeline']['submitted'] + $stats['pipeline']['under_review'];
        @endphp
        <!-- Articles & money -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
            <a href="{{ url('/' . $role . '/revenue') }}" class="bg-dark text-cream rounded-2xl p-5 shadow-sm hover:shadow-md transition-shadow">
                <p class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-cream/60">{{ __('Net revenue this month') }}</p>
                <div class="text-2xl font-extrabold text-gold mt-1">{{ MB::rm($cur['net']) }}</div>
                <div class="text-xs text-cream/60 mt-0.5">{{ __(':copies copies · :subs subscribers', ['copies' => $cur['copies'], 'subs' => $cur['subscribers']]) }}</div>
            </a>
            <a href="{{ url('/' . $role . '/analytics') }}" class="bg-white rounded-2xl p-5 card-ring hover:shadow-md transition-shadow">
                <p class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-gray-400">{{ __('Tokens outstanding') }}</p>
                <div class="text-2xl font-extrabold text-dark mt-1">{{ number_format($cur['tokens_outstanding']) }}</div>
                <div class="text-xs text-gray-400 mt-0.5">{{ __(':rm owed to readers as unspent tokens', ['rm' => MB::rm(MB::tokensToSen($cur['tokens_outstanding']))]) }}</div>
            </a>
            <a href="{{ url('/' . $role . '/reviews') }}" class="bg-white rounded-2xl p-5 card-ring hover:shadow-md transition-shadow">
                <p class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-gray-400">{{ __('Papers in review') }}</p>
                <div class="text-2xl font-extrabold text-dark mt-1">{{ $inReview }}</div>
                <div class="text-xs text-gray-400 mt-0.5">{{ __('average review :d days', ['d' => $stats['avgDays']]) }}</div>
            </a>
            <a href="{{ url('/' . $role . '/reviewers?tab=pending') }}" class="bg-white rounded-2xl p-5 card-ring hover:shadow-md transition-shadow">
                <p class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-gray-400">{{ __('Reviewer applications') }}</p>
                <div class="text-2xl font-extrabold {{ $stats['pendingReviewers'] ? 'text-primary' : 'text-dark' }} mt-1">{{ $stats['pendingReviewers'] }}</div>
                <div class="text-xs text-gray-400 mt-0.5">{{ __('waiting for verification') }}</div>
            </a>
        </div>

        <!-- Charts -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">
            <div class="xl:col-span-2 bg-white rounded-2xl p-5 sm:p-6 card-ring">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h2 class="font-display text-2xl text-dark">{{ __('Revenue by source') }}</h2>
                        <p class="text-xs text-gray-400">{{ __('Last six months, before author payouts and fees') }}</p>
                    </div>
                    <a href="{{ url('/' . $role . '/revenue') }}" class="text-sm font-semibold text-primary hover:text-primary-dark">{{ __('Details') }} <i class="bi bi-arrow-right"></i></a>
                </div>
                <div class="h-72"><canvas id="revenueChart" role="img" aria-label="{{ __('Revenue by source') }}"></canvas></div>
            </div>
            <div class="bg-white rounded-2xl p-5 sm:p-6 card-ring">
                <h2 class="font-display text-2xl text-dark">{{ __('Review pipeline') }}</h2>
                <p class="text-xs text-gray-400 mb-4">{{ __('All manuscripts by status') }}</p>
                <div class="h-72"><canvas id="pipelineChart" role="img" aria-label="{{ __('Review pipeline') }}"></canvas></div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 sm:p-6 card-ring mb-8">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h2 class="font-display text-2xl text-dark">{{ __('New users by role') }}</h2>
                    <p class="text-xs text-gray-400">{{ __('Sign-ups per month') }}</p>
                </div>
                <a href="{{ url('/' . $role . '/analytics') }}" class="text-sm font-semibold text-primary hover:text-primary-dark">{{ __('All analytics') }} <i class="bi bi-arrow-right"></i></a>
            </div>
            <div class="h-64"><canvas id="usersChart" role="img" aria-label="{{ __('New users by role') }}"></canvas></div>
        </div>
    @endif

    <!-- Two-column panels: Applications + Alerts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">

        <!-- Counselor Applications -->
        <div class="bg-white rounded-2xl p-4 sm:p-6 card-ring">
            <div class="flex justify-between items-center mb-4 sm:mb-6">
                <h2 class="font-display text-2xl text-dark">{{ __('Counselor Applications') }}</h2>
                <span class="text-xs font-bold uppercase tracking-wide bg-cream text-primary px-2.5 py-1 rounded-full">{{ \App\Support\SiteText::translate($pendingCounselors) }} {{ __('Pending') }}</span>
            </div>
            <div class="text-center text-gray-400 py-10">
                <i class="bi bi-inbox text-4xl block mb-2"></i>
                {{ __('No pending applications.') }}
            </div>
        </div>

        <!-- System Alerts -->
        <div class="bg-white rounded-2xl p-4 sm:p-6 card-ring">
            <h2 class="font-display text-2xl text-dark mb-4 sm:mb-6">{{ __('System Alerts') }}</h2>
            <div class="text-center text-gray-400 py-10">
                <i class="bi bi-shield-check text-4xl block mb-2"></i>
                {{ __('No active alerts.') }}
            </div>
        </div>
    </div>

    @if($role === 'superadmin')
    <!-- Administrators quick panel — superadmin only -->
    <div class="bg-white rounded-2xl p-4 sm:p-6 card-ring mb-8">
        <div class="flex justify-between items-center mb-4 sm:mb-6">
            <h2 class="font-display text-2xl text-dark">{{ __('Administrator Accounts') }}</h2>
            <a href="{{ url('/superadmin/administrators') }}" class="text-sm font-semibold text-primary hover:text-primary-dark">
                {{ __('Manage') }} <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        <div class="text-center text-gray-400 py-10">
            <i class="bi bi-person-badge text-4xl block mb-2"></i>
            {{ __('No administrator accounts yet.') }}
        </div>
    </div>
    @endif

</div>
@endsection

@section('scripts')
@if($stats)
<script>
    (function () {
        const c = window.WertaChartColors;
        const m = @json($stats['monthly']);
        wertaChart('revenueChart', {
            type: 'bar',
            data: {
                labels: m.map(r => r.label),
                datasets: [
                    { label: @json(__('Article sales')), data: m.map(r => r.sales / 100), backgroundColor: c[0], stack: 's', borderRadius: 4 },
                    { label: @json(__('Subscriptions')), data: m.map(r => r.subs / 100), backgroundColor: c[1], stack: 's', borderRadius: 4 },
                    { label: @json(__('Publish fees')), data: m.map(r => r.fees / 100), backgroundColor: c[2], stack: 's', borderRadius: 4 },
                ]
            },
            options: {
                plugins: { legend: { position: 'bottom' }, tooltip: { callbacks: { label: ctx => ctx.dataset.label + ': RM ' + ctx.parsed.y.toLocaleString() } } },
                scales: { x: { stacked: true, grid: { display: false } }, y: { stacked: true, grid: { color: 'rgba(123,107,53,0.1)' }, ticks: { callback: v => 'RM ' + v.toLocaleString() } } }
            }
        });
        const p = @json($stats['pipeline']);
        wertaChart('pipelineChart', {
            type: 'doughnut',
            data: {
                labels: [@json(__('Submitted')), @json(__('Under Review')), @json(__('Revisions Requested')), @json(__('Accepted')), @json(__('Published')), @json(__('Not Approved'))],
                datasets: [{ data: [p.submitted, p.under_review, p.revisions_requested, p.accepted, p.published, p.rejected], backgroundColor: [c[5], c[0], c[3], c[4], c[1], c[2]], borderWidth: 2, borderColor: '#fff' }]
            },
            options: { cutout: '62%', plugins: { legend: { position: 'bottom' } } }
        });
        const u = @json($stats['users']);
        wertaChart('usersChart', {
            type: 'line',
            data: {
                labels: @json($stats['labels']),
                datasets: [
                    { label: @json(__('Clients')), data: u.client, borderColor: c[0], backgroundColor: 'rgba(196,168,64,0.15)', fill: true, tension: .35 },
                    { label: @json(__('Parents')), data: u.parent, borderColor: c[1], tension: .35 },
                    { label: @json(__('Counselors')), data: u.counselor, borderColor: c[3], tension: .35 },
                ]
            },
            options: { plugins: { legend: { position: 'bottom' } }, scales: { y: { beginAtZero: true, grid: { color: 'rgba(123,107,53,0.1)' } }, x: { grid: { display: false } } } }
        });
    })();
</script>
@endif
@endsection
