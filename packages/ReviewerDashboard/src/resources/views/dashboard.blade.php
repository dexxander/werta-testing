@extends('reviewerdashboard::layout')

@section('title', __('Review queue'))

@section('content')
@php use Articles\Support\MockBilling as MB; @endphp
<div>
    <div class="mb-6 sm:mb-8 flex flex-wrap justify-between items-end gap-3">
        <div>
            <p class="text-xs font-bold uppercase tracking-[3px] text-primary mb-1">{{ __('Reviewer Portal') }}</p>
            <h1 class="font-display text-3xl sm:text-4xl text-dark">{{ __('Welcome, :name', ['name' => $reviewer->name]) }}</h1>
            <p class="text-sm text-gray-500 mt-1">{{ __('Evaluate each manuscript and say whether it is ready to publish, and whether it is good enough to sell.') }}</p>
        </div>
        <a href="{{ route('reviewer.guidelines') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-white ring-1 ring-primary/40 text-primary-dark text-sm font-semibold hover:bg-primary hover:text-white transition-colors"><i class="bi bi-clipboard-check"></i> {{ __('Evaluation guidelines') }}</a>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        @foreach([
            [__('Assigned to you'), $stats['assigned'], 'bi-inbox', __(':n due within 7 days', ['n' => $stats['dueSoon']])],
            [__('Completed'), $stats['completed'], 'bi-journal-check', __('all time')],
            [__('Average turnaround'), trans_choice(':count day|:count days', (int) round($stats['avgDays']), ['count' => $stats['avgDays']]), 'bi-clock-history', __('assigned to submitted')],
            [__('Honorarium earned'), MB::rm($stats['earned']), 'bi-cash-coin', __('proposed RM 40 per review')],
        ] as [$label, $value, $icon, $sub])
            <div class="bg-white rounded-2xl p-5 card-ring">
                <div class="flex items-center justify-between">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">{{ $label }}</p>
                    <i class="bi {{ $icon }} text-gold text-lg"></i>
                </div>
                <div class="text-2xl font-bold text-dark mt-1">{{ $value }}</div>
                <div class="text-xs text-gray-400 mt-0.5">{{ $sub }}</div>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">
        <div class="xl:col-span-2 bg-white rounded-2xl p-5 sm:p-6 card-ring">
            <h2 class="font-display text-2xl text-dark mb-4">{{ __('Review queue') }}</h2>
            @forelse($queue as $review)
                @php
                    $daysLeft = $review->due_at ? (int) now()->startOfDay()->diffInDays($review->due_at->copy()->startOfDay(), false) : null;
                @endphp
                <a href="{{ route('reviewer.reviews.show', $review) }}" class="flex flex-wrap sm:flex-nowrap items-center gap-4 p-4 rounded-xl hover:bg-cream/70 transition-colors border-b border-gray-100 last:border-0 group">
                    <div class="h-11 w-11 shrink-0 rounded-xl bg-gold/15 text-primary flex items-center justify-center"><i class="bi {{ $review->submission->category_icon }} text-lg"></i></div>
                    <div class="min-w-0 flex-1">
                        <div class="font-bold text-dark group-hover:text-primary truncate">{{ $review->submission->title }}</div>
                        <div class="text-xs text-gray-500 mt-0.5 flex flex-wrap gap-x-3">
                            <span>{{ $review->submission->category_label ? __($review->submission->category_label) : '' }}</span>
                            <span>{{ $review->submission->access_type === 'paid' ? __('Author requested: Premium') : __('Author requested: Free') }}</span>
                            <span>{{ __('Assigned :date', ['date' => $review->assigned_at->translatedFormat('d M')]) }}</span>
                        </div>
                    </div>
                    <div class="text-right shrink-0">
                        @if($daysLeft !== null)
                            <span class="text-[10px] font-bold uppercase px-2 py-1 rounded-full {{ $daysLeft <= 3 ? 'bg-red-50 text-red-700' : ($daysLeft <= 7 ? 'bg-yellow-50 text-yellow-700' : 'bg-gray-100 text-gray-600') }}">
                                {{ $daysLeft < 0 ? __('Overdue') : trans_choice('Due in :count day|Due in :count days', $daysLeft, ['count' => $daysLeft]) }}
                            </span>
                        @endif
                        <div class="text-xs font-semibold text-primary mt-2">{{ __('Start review') }} <i class="bi bi-arrow-right"></i></div>
                    </div>
                </a>
            @empty
                <div class="text-center text-gray-400 py-12">
                    <i class="bi bi-inbox text-4xl block mb-2 text-gold/70"></i>
                    {{ __('No manuscripts waiting. New assignments will appear here.') }}
                </div>
            @endforelse
        </div>

        <div class="bg-white rounded-2xl p-5 sm:p-6 card-ring">
            <h2 class="font-display text-2xl text-dark mb-4">{{ __('Reviews per month') }}</h2>
            <div class="h-56"><canvas id="reviewsChart" role="img" aria-label="{{ __('Reviews per month') }}"></canvas></div>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-5 sm:p-6 card-ring">
        <div class="flex justify-between items-center mb-4">
            <h2 class="font-display text-2xl text-dark">{{ __('Recently completed') }}</h2>
            <a href="{{ route('reviewer.completed') }}" class="text-sm font-semibold text-primary hover:text-primary-dark">{{ __('See all') }} <i class="bi bi-arrow-right"></i></a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-cream text-primary font-bold border-b border-gold/20 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-4 py-3 rounded-tl-lg">{{ __('Manuscript') }}</th>
                        <th class="px-4 py-3">{{ __('Recommendation') }}</th>
                        <th class="px-4 py-3">{{ __('Sale verdict') }}</th>
                        <th class="px-4 py-3">{{ __('Score') }}</th>
                        <th class="px-4 py-3 rounded-tr-lg">{{ __('Submitted') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($completed->take(5) as $review)
                        <tr>
                            <td class="px-4 py-3 font-semibold text-dark"><a href="{{ route('reviewer.reviews.show', $review) }}" class="hover:text-primary">{{ $review->submission->title }}</a></td>
                            <td class="px-4 py-3">{{ __($review->recommendationLabel() ?? '—') }}</td>
                            <td class="px-4 py-3">{{ $review->suitable_for_sale === null ? '—' : ($review->suitable_for_sale ? __('Sell as premium') : __('Publish free')) }}</td>
                            <td class="px-4 py-3">{{ $review->overallScore() ?? '—' }}/5</td>
                            <td class="px-4 py-3">{{ optional($review->submitted_at)->translatedFormat('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-10 text-center text-gray-400">{{ __('No completed reviews yet.') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    (function () {
        const el = document.getElementById('reviewsChart');
        if (!el || !window.Chart) return;
        Chart.defaults.font.family = "'Lato', sans-serif";
        Chart.defaults.color = '#6B6455';
        new Chart(el, {
            type: 'bar',
            data: { labels: @json(array_keys($months)), datasets: [{ label: @json(__('Reviews')), data: @json(array_values($months)), backgroundColor: '#C4A840', borderRadius: 6, maxBarThickness: 32 }] },
            options: { maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: 'rgba(123,107,53,0.1)' } }, x: { grid: { display: false } } } }
        });
    })();
</script>
@endsection
