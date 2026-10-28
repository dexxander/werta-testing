@extends('admindashboard::layout')

@section('content')
@php
    use Articles\Support\MockBilling as MB;
    $stages = [__('Submitted'), __('Under review'), __('Accepted'), __('Fee pending'), __('Published')];
    $card = 'bg-white rounded-2xl p-5 sm:p-6 card-ring';
@endphp
<div>
    <div class="mb-6 sm:mb-8 flex flex-wrap justify-between items-end gap-3">
        <div>
            <h1 class="font-display text-3xl sm:text-4xl text-dark">{{ __('Revenue') }}</h1>
            <p class="text-sm text-gray-500 mt-1">{{ __('Token sales, paper sales, subscriptions, publish fees and author payouts.') }}</p>
        </div>
        <span class="text-[10px] font-bold tracking-wider text-primary bg-cream-light border border-gold rounded-full px-3 py-1">{{ __('DEMO DATA — session sales are live, the rest is baseline') }}</span>
    </div>

    <!-- Pricing reminder -->
    <div class="mb-6 p-4 rounded-2xl bg-cream-light ring-1 ring-gold/40 text-sm text-primary-dark flex flex-wrap gap-x-6 gap-y-1">
        <span><i class="bi bi-coin text-gold"></i> {{ __('1 token = :sen sen', ['sen' => MB::TOKEN_SEN]) }}</span>
        <span>{{ __('Premium paper: :n tokens (:rm)', ['n' => MB::ARTICLE_TOKENS, 'rm' => MB::rm(MB::articlePrice())]) }}</span>
        <span>{{ __('Author: :rm per copy', ['rm' => MB::rm(MB::AUTHOR_PER_COPY)]) }}</span>
        <span>{{ __('Subscription: :n tokens (:rm), :cap papers', ['n' => MB::SUB_TOKENS, 'rm' => MB::rm(MB::subPrice()), 'cap' => MB::SUB_CAP]) }}</span>
        <span>{{ __('Publish fee: :rm', ['rm' => MB::rm(MB::PUBLISH_FEE)]) }}</span>
    </div>

    <!-- This month -->
    <h2 class="text-xs font-bold uppercase tracking-wider text-primary mb-3">{{ __('This month (:month)', ['month' => $current['label']]) }}</h2>
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        @foreach([
            [__('Token top-ups (cash in)'), MB::rm($current['topups']), __('packs sold through the gateway'), 'text-dark'],
            [__('Article sales'), MB::rm($current['sales']), __(':n copies at :rm', ['n' => $current['copies'], 'rm' => MB::rm(MB::articlePrice())]), 'text-dark'],
            [__('Subscriptions'), MB::rm($current['subs']), __(':n subscribers', ['n' => $current['subscribers']]), 'text-dark'],
            [__('Publish fees'), MB::rm($current['fees']), __(':rm per accepted premium paper', ['rm' => MB::rm(MB::PUBLISH_FEE)]), 'text-dark'],
            [__('Author payouts owed'), '− ' . MB::rm($current['payouts']), __(':rm per copy, never from subscriptions', ['rm' => MB::rm(MB::AUTHOR_PER_COPY)]), 'text-red-700'],
            [__('Est. gateway fees'), '− ' . MB::rm($current['gateway']), __(':p% of cash taken', ['p' => (int) (MB::GATEWAY_RATE * 100)]), 'text-red-700'],
            [__('Promo and bonus cost'), '− ' . MB::rm($current['promo']), __('bonus tokens spent'), 'text-red-700'],
            [__('Net platform revenue'), MB::rm($current['net']), __('after payouts, fees and promos'), 'text-green-700'],
        ] as [$label, $val, $sub, $cls])
            <div class="{{ $card }}">
                <div class="text-[11px] font-bold uppercase tracking-wider text-gray-400">{{ $label }}</div>
                <div class="text-2xl font-bold mt-1 {{ $cls }}">{{ $val }}</div>
                <div class="text-xs text-gray-400 mt-0.5">{{ $sub }}</div>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">
        <div class="xl:col-span-2 {{ $card }}">
            <h2 class="font-display text-2xl text-dark">{{ __('Monthly revenue') }}</h2>
            <p class="text-xs text-gray-400 mb-4">{{ __('Six-month net: :rm', ['rm' => MB::rm($totals['net'])]) }}</p>
            <div class="h-72"><canvas id="revenueChart" role="img" aria-label="{{ __('Monthly revenue') }}"></canvas></div>
        </div>
        <div class="{{ $card }}">
            <h2 class="font-display text-2xl text-dark">{{ __('Where the money goes') }}</h2>
            <p class="text-xs text-gray-400 mb-4">{{ __('Six months of takings') }}</p>
            <div class="h-72"><canvas id="splitChart" role="img" aria-label="{{ __('Where the money goes') }}"></canvas></div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-8">
        <div class="{{ $card }}">
            <h2 class="font-display text-2xl text-dark">{{ __('Author payouts') }}</h2>
            <p class="text-xs text-gray-400 mb-4">{{ __('Owed vs paid out. Payouts are held :d days, minimum :min.', ['d' => MB::HOLD_DAYS, 'min' => MB::rm(MB::MIN_WITHDRAW)]) }}</p>
            <div class="h-64"><canvas id="payoutChart" role="img" aria-label="{{ __('Author payouts') }}"></canvas></div>
        </div>

        <div class="{{ $card }}">
            <h2 class="font-display text-2xl text-dark mb-4">{{ __('Top-earning papers') }}</h2>
            <div class="divide-y divide-gray-100">
                @foreach($top as $t)
                    <div class="py-3 flex justify-between gap-3">
                        <div class="min-w-0">
                            <div class="font-semibold text-dark truncate">{{ $t['title'] }}</div>
                            <div class="text-xs text-gray-400">{{ $t['author'] }}@if($t['category']) · {{ __($t['category']) }}@endif · {{ __(':n copies', ['n' => $t['copies']]) }}</div>
                        </div>
                        <div class="text-right shrink-0">
                            <div class="font-bold text-primary">{{ MB::rm($t['gross']) }}</div>
                            <div class="text-[11px] text-gray-400">{{ __('author :rm', ['rm' => MB::rm($t['author_share'])]) }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Review queue --}}
    <div class="{{ $card }}">
        <div class="flex justify-between items-start mb-1">
            <h2 class="font-display text-2xl text-dark">{{ __('Premium review queue') }}</h2>
            <a href="{{ url('/' . $role . '/reviews') }}" class="text-sm font-semibold text-primary hover:text-primary-dark">{{ __('Open reviews') }} <i class="bi bi-arrow-right"></i></a>
        </div>
        <p class="text-xs text-gray-400 mb-4">{{ __('The :fee fee is only requested after acceptance.', ['fee' => MB::rm(MB::PUBLISH_FEE)]) }}</p>
        <div class="space-y-4">
            @forelse($queue as $q)
                <div>
                    <div class="flex justify-between text-sm mb-1.5">
                        <span class="font-semibold text-dark">{{ $q['title'] }}</span>
                        <span class="text-xs text-gray-400">{{ $q['author'] }}</span>
                    </div>
                    <div class="flex items-center gap-1">
                        @foreach($stages as $i => $label)
                            <div class="flex-1">
                                <div class="h-1.5 rounded-full {{ $i < $q['stage'] ? 'bg-gold' : ($i === $q['stage'] ? 'bg-primary' : 'bg-gray-200') }}"></div>
                                <div class="text-[10px] mt-1 {{ $i === $q['stage'] ? 'font-bold text-primary' : 'text-gray-400' }}">{{ $label }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-400">{{ __('No premium papers waiting.') }}</p>
            @endforelse
        </div>
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

        const t = @json($totals);
        wertaChart('splitChart', {
            type: 'doughnut',
            data: {
                labels: [@json(__('Platform net')), @json(__('Author payouts')), @json(__('Gateway fees')), @json(__('Promo and bonus cost'))],
                datasets: [{ data: [t.net / 100, t.payouts / 100, t.gateway / 100, t.promo / 100], backgroundColor: [c[0], c[1], c[2], c[3]], borderWidth: 2, borderColor: '#fff' }]
            },
            options: { cutout: '60%', plugins: { legend: { position: 'bottom' }, tooltip: { callbacks: { label: ctx => ctx.label + ': RM ' + Number(ctx.parsed).toLocaleString() } } } }
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
    })();
</script>
@endsection
