@extends('admindashboard::layout')

@section('content')
@php
    use Articles\Support\MockBilling as MB;
    $last = end($monthly);
    $card = 'bg-white rounded-2xl p-5 sm:p-6 card-ring';
@endphp
<div>
    <div class="mb-6 sm:mb-8 flex flex-wrap justify-between items-end gap-3">
        <div>
            <h1 class="font-display text-3xl sm:text-4xl text-dark">{{ __('Analytics') }}</h1>
            <p class="text-sm text-gray-500 mt-1">{{ __('Platform usage trends, engagement, and performance metrics.') }}</p>
        </div>
        <span class="text-[10px] font-bold tracking-wider text-primary bg-cream-light border border-gold rounded-full px-3 py-1">{{ __('DEMO DATA — live session activity on top of baseline figures') }}</span>
    </div>

    <!-- Headline numbers -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        @foreach([
            [__('Copies sold this month'), number_format($last['copies']), __('premium papers at RM 5')],
            [__('Tokens outstanding'), number_format($last['tokens_outstanding']), __(':rm liability', ['rm' => MB::rm(MB::tokensToSen($last['tokens_outstanding']))])],
            [__('Average review time'), trans_choice(':count day|:count days', (int) round($avgDays), ['count' => $avgDays]), __('assigned to submitted')],
            [__('Author payouts owed'), MB::rm($totals['owedAllTime']), __('RM 1 per copy, all time')],
        ] as [$label, $value, $sub])
            <div class="{{ $card }}">
                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">{{ $label }}</p>
                <div class="text-2xl font-bold text-dark mt-1">{{ $value }}</div>
                <div class="text-xs text-gray-400 mt-0.5">{{ $sub }}</div>
            </div>
        @endforeach
    </div>

    <!-- Revenue + tokens -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-6">
        <div class="{{ $card }}">
            <h2 class="font-display text-2xl text-dark">{{ __('Revenue by source') }}</h2>
            <p class="text-xs text-gray-400 mb-4">{{ __('Net platform revenue shown as a line') }}</p>
            <div class="h-72"><canvas id="revenueChart" role="img" aria-label="{{ __('Revenue by source') }}"></canvas></div>
        </div>
        <div class="{{ $card }}">
            <h2 class="font-display text-2xl text-dark">{{ __('Tokens sold, spent and outstanding') }}</h2>
            <p class="text-xs text-gray-400 mb-4">{{ __('Outstanding tokens are money readers have paid but not yet used') }}</p>
            <div class="h-72"><canvas id="tokensChart" role="img" aria-label="{{ __('Tokens sold, spent and outstanding') }}"></canvas></div>
        </div>
    </div>

    <!-- Content performance -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-6">
        <div class="{{ $card }}">
            <h2 class="font-display text-2xl text-dark">{{ __('Sales by category') }}</h2>
            <p class="text-xs text-gray-400 mb-4">{{ __('Copies sold, all time') }}</p>
            <div class="h-72"><canvas id="categoryChart" role="img" aria-label="{{ __('Sales by category') }}"></canvas></div>
        </div>
        <div class="{{ $card }}">
            <h2 class="font-display text-2xl text-dark">{{ __('Top articles') }}</h2>
            <p class="text-xs text-gray-400 mb-4">{{ __('By copies sold') }}</p>
            <div class="h-72"><canvas id="articlesChart" role="img" aria-label="{{ __('Top articles') }}"></canvas></div>
        </div>
        <div class="{{ $card }}">
            <h2 class="font-display text-2xl text-dark">{{ __('Top authors') }}</h2>
            <p class="text-xs text-gray-400 mb-4">{{ __('By copies sold') }}</p>
            <div class="h-72"><canvas id="authorsChart" role="img" aria-label="{{ __('Top authors') }}"></canvas></div>
        </div>
    </div>

    <!-- Review -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-6">
        <div class="{{ $card }}">
            <h2 class="font-display text-2xl text-dark">{{ __('Reviewer workload') }}</h2>
            <p class="text-xs text-gray-400 mb-4">{{ __('Assigned and completed reviews per reviewer') }}</p>
            <div class="h-72"><canvas id="workloadChart" role="img" aria-label="{{ __('Reviewer workload') }}"></canvas></div>
        </div>
        <div class="{{ $card }}">
            <h2 class="font-display text-2xl text-dark">{{ __('Review turnaround') }}</h2>
            <p class="text-xs text-gray-400 mb-4">{{ __('Average days from assignment to evaluation') }}</p>
            <div class="h-72"><canvas id="turnaroundChart" role="img" aria-label="{{ __('Review turnaround') }}"></canvas></div>
        </div>
    </div>

    <!-- Growth -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-6">
        <div class="{{ $card }}">
            <h2 class="font-display text-2xl text-dark">{{ __('Promos, referrals and gifts') }}</h2>
            <p class="text-xs text-gray-400 mb-4">{{ __('Redemptions, all time') }}</p>
            <div class="h-64"><canvas id="promoChart" role="img" aria-label="{{ __('Promos, referrals and gifts') }}"></canvas></div>
        </div>
        <div class="{{ $card }}">
            <h2 class="font-display text-2xl text-dark">{{ __('Top-up pack mix') }}</h2>
            <p class="text-xs text-gray-400 mb-4">{{ __('Which packs readers buy') }}</p>
            <div class="h-64"><canvas id="packChart" role="img" aria-label="{{ __('Top-up pack mix') }}"></canvas></div>
        </div>
        <div class="{{ $card }}">
            <h2 class="font-display text-2xl text-dark">{{ __('Author payouts') }}</h2>
            <p class="text-xs text-gray-400 mb-4">{{ __('Owed vs paid out per month') }}</p>
            <div class="h-64"><canvas id="payoutChart" role="img" aria-label="{{ __('Author payouts') }}"></canvas></div>
        </div>
    </div>

    <div class="{{ $card }}">
        <h2 class="font-display text-2xl text-dark">{{ __('New users by role') }}</h2>
        <p class="text-xs text-gray-400 mb-4">{{ __('Sign-ups per month') }}</p>
        <div class="h-64"><canvas id="usersChart" role="img" aria-label="{{ __('New users by role') }}"></canvas></div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    (function () {
        const c = window.WertaChartColors;
        const grid = { color: 'rgba(123,107,53,0.1)' };
        const m = @json($monthly);
        const labels = m.map(r => r.label);
        const rmTick = { callback: v => 'RM ' + Number(v).toLocaleString() };
        const rmTip = { callbacks: { label: ctx => ctx.dataset.label + ': RM ' + Number(ctx.parsed.y ?? ctx.parsed).toLocaleString() } };

        wertaChart('revenueChart', {
            type: 'bar',
            data: { labels, datasets: [
                { label: @json(__('Article sales')), data: m.map(r => r.sales / 100), backgroundColor: c[0], stack: 's', borderRadius: 4 },
                { label: @json(__('Subscriptions')), data: m.map(r => r.subs / 100), backgroundColor: c[1], stack: 's', borderRadius: 4 },
                { label: @json(__('Publish fees')), data: m.map(r => r.fees / 100), backgroundColor: c[2], stack: 's', borderRadius: 4 },
                { label: @json(__('Net revenue')), data: m.map(r => r.net / 100), type: 'line', borderColor: c[3], backgroundColor: c[3], tension: .35, pointRadius: 3 },
            ] },
            options: { plugins: { legend: { position: 'bottom' }, tooltip: rmTip }, scales: { x: { stacked: true, grid: { display: false } }, y: { stacked: true, grid, ticks: rmTick } } }
        });

        wertaChart('tokensChart', {
            type: 'bar',
            data: { labels, datasets: [
                { label: @json(__('Tokens sold')), data: m.map(r => r.tokens_sold), backgroundColor: c[0], borderRadius: 4 },
                { label: @json(__('Tokens spent')), data: m.map(r => r.tokens_spent), backgroundColor: c[1], borderRadius: 4 },
                { label: @json(__('Outstanding')), data: m.map(r => r.tokens_outstanding), type: 'line', borderColor: c[2], backgroundColor: c[2], tension: .35, pointRadius: 3 },
            ] },
            options: { plugins: { legend: { position: 'bottom' } }, scales: { x: { grid: { display: false } }, y: { beginAtZero: true, grid } } }
        });

        const cats = @json(collect($categories)->map(fn ($x) => ['label' => __($x['label']), 'copies' => $x['copies']])->values());
        wertaChart('categoryChart', {
            type: 'doughnut',
            data: { labels: cats.map(x => x.label), datasets: [{ data: cats.map(x => x.copies), backgroundColor: c, borderWidth: 2, borderColor: '#fff' }] },
            options: { cutout: '58%', plugins: { legend: { position: 'bottom' } } }
        });

        const arts = @json($topArticles->map(fn ($r) => ['t' => \Illuminate\Support\Str::limit($r->paper->title, 28), 'n' => $r->copies])->values());
        wertaChart('articlesChart', {
            type: 'bar',
            data: { labels: arts.map(a => a.t), datasets: [{ label: @json(__('Copies')), data: arts.map(a => a.n), backgroundColor: c[0], borderRadius: 4 }] },
            options: { indexAxis: 'y', plugins: { legend: { display: false } }, scales: { x: { grid }, y: { grid: { display: false }, ticks: { color: '#2C2416' } } } }
        });

        const auth = @json($topAuthors);
        wertaChart('authorsChart', {
            type: 'bar',
            data: { labels: auth.map(a => a.name), datasets: [{ label: @json(__('Copies')), data: auth.map(a => a.copies), backgroundColor: c[1], borderRadius: 4 }] },
            options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, grid }, x: { grid: { display: false } } } }
        });

        const w = @json($workload);
        wertaChart('workloadChart', {
            type: 'bar',
            data: { labels: w.map(r => r.name), datasets: [
                { label: @json(__('Assigned')), data: w.map(r => r.assigned), backgroundColor: c[5], stack: 'w', borderRadius: 4 },
                { label: @json(__('Completed')), data: w.map(r => r.completed), backgroundColor: c[1], stack: 'w', borderRadius: 4 },
            ] },
            options: { plugins: { legend: { position: 'bottom' } }, scales: { x: { stacked: true, grid: { display: false } }, y: { stacked: true, beginAtZero: true, ticks: { precision: 0 }, grid } } }
        });
        wertaChart('turnaroundChart', {
            type: 'bar',
            data: { labels: w.map(r => r.name), datasets: [{ label: @json(__('Days')), data: w.map(r => r.avgDays), backgroundColor: c[0], borderRadius: 4 }] },
            options: { indexAxis: 'y', plugins: { legend: { display: false } }, scales: { x: { beginAtZero: true, grid }, y: { grid: { display: false }, ticks: { color: '#2C2416' } } } }
        });

        const promos = @json(collect($promos)->mapWithKeys(fn ($v, $k) => [__($k) => $v]));
        wertaChart('promoChart', {
            type: 'bar',
            data: { labels: Object.keys(promos), datasets: [{ data: Object.values(promos), backgroundColor: [c[0], c[1], c[3], c[4]], borderRadius: 4 }] },
            options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, grid }, x: { grid: { display: false } } } }
        });

        const packs = @json(collect($packs)->mapWithKeys(fn ($v, $k) => [__($k) => $v]));
        wertaChart('packChart', {
            type: 'doughnut',
            data: { labels: Object.keys(packs), datasets: [{ data: Object.values(packs), backgroundColor: [c[5], c[0], c[1], c[2]], borderWidth: 2, borderColor: '#fff' }] },
            options: { cutout: '58%', plugins: { legend: { position: 'bottom' } } }
        });

        const pay = @json($payouts);
        wertaChart('payoutChart', {
            type: 'bar',
            data: { labels, datasets: [
                { label: @json(__('Owed')), data: pay.owed.map(v => v / 100), backgroundColor: c[0], borderRadius: 4 },
                { label: @json(__('Paid out')), data: pay.paid.map(v => v / 100), backgroundColor: c[1], borderRadius: 4 },
            ] },
            options: { plugins: { legend: { position: 'bottom' }, tooltip: rmTip }, scales: { y: { beginAtZero: true, grid, ticks: rmTick }, x: { grid: { display: false } } } }
        });

        const u = @json($users);
        wertaChart('usersChart', {
            type: 'line',
            data: { labels, datasets: [
                { label: @json(__('Clients')), data: u.client, borderColor: c[0], backgroundColor: 'rgba(196,168,64,0.15)', fill: true, tension: .35 },
                { label: @json(__('Parents')), data: u.parent, borderColor: c[1], tension: .35 },
                { label: @json(__('Counselors')), data: u.counselor, borderColor: c[3], tension: .35 },
            ] },
            options: { plugins: { legend: { position: 'bottom' } }, scales: { y: { beginAtZero: true, grid }, x: { grid: { display: false } } } }
        });
    })();
</script>
@endsection
