@extends('layouts.app')

@section('content')
    @include('partials.navbar')
    @include('partials.werta-ui')
    @php
        use Submissions\Models\Submission;
        $categoryLabels = $papers->pluck('category')->filter()->unique()->map(fn ($c) => __(Submission::categoryLabel($c)))->values();
    @endphp

    <div class="wt-page">
        <a href="{{ route('public.articles') }}" class="wt-back"><i class="bi bi-arrow-left"></i> {{ __('Back to Articles') }}</a>

        {{-- Profile header --}}
        <div class="wt-card wt-card--cream wt-mb author-hero">
            <img src="https://ui-avatars.com/api/?name={{ urlencode($name) }}&background=C4A840&color=fff&size=192" alt="{{ $name }}" class="wt-avatar wt-avatar--lg">
            <div class="flex-grow-1 min-w-0">
                <p class="wt-eyebrow mb-1">{{ __('Author') }} · {{ __('nav.roles.' . $role) }}</p>
                <h1 class="wt-title" style="margin-bottom: .3rem;">{{ $name }}</h1>
                <p class="wt-subtitle">
                    @if($categoryLabels->isNotEmpty())
                        {{ __('Writes about :topics.', ['topics' => $categoryLabels->implode(', ')]) }}
                    @endif
                    {{ __('Every paper here passed review by verified professionals before publication.') }}
                </p>
            </div>
        </div>

        {{-- Stats --}}
        <div class="wt-grid wt-grid-4 wt-mb">
            <div class="wt-card wt-card--flat">
                <div class="wt-card-label">{{ __('Published papers') }}</div>
                <div class="wt-stat-value">{{ $stats['papers'] }}</div>
                <div class="wt-stat-sub">{{ __(':n premium · :f free', ['n' => $stats['premium'], 'f' => $stats['papers'] - $stats['premium']]) }}</div>
            </div>
            <div class="wt-card wt-card--flat">
                <div class="wt-card-label">{{ __('Copies bought') }}</div>
                <div class="wt-stat-value is-gold">{{ number_format($stats['copies']) }}</div>
                <div class="wt-stat-sub">{{ __('premium papers, including gifts') }}</div>
            </div>
            <div class="wt-card wt-card--flat">
                <div class="wt-card-label">{{ __('Total readers') }}</div>
                <div class="wt-stat-value">{{ number_format($stats['readers']) }}</div>
                <div class="wt-stat-sub">{{ __('copies, subscription and free reads') }}</div>
            </div>
            <div class="wt-card wt-card--flat">
                <div class="wt-card-label">{{ __('Peer reviewed') }}</div>
                <div class="wt-stat-value">{{ $stats['reviewed'] }}/{{ $stats['papers'] }}</div>
                <div class="wt-stat-sub">{{ trans_choice(':count category|:count categories', $stats['categories'], ['count' => $stats['categories']]) }}</div>
            </div>
        </div>

        <div class="wt-grid author-grid wt-mb">
            {{-- Chart --}}
            <div class="wt-card">
                <h2 class="wt-section-title" style="font-size: var(--text-xl);">{{ __('Readership by paper') }}</h2>
                <p class="wt-section-sub">{{ __('Copies bought earn the author :rm each. Subscription and free reads are shown for reach.', ['rm' => \Articles\Support\MockBilling::rm(\Articles\Support\MockBilling::AUTHOR_PER_COPY)]) }}</p>
                <div class="wt-chart" style="height: {{ max(220, count($chart['labels']) * 46 + 60) }}px;">
                    <canvas id="authorChart" aria-label="{{ __('Readership by paper') }}" role="img"></canvas>
                </div>
            </div>

            {{-- Best sellers --}}
            <div class="wt-card">
                <h2 class="wt-section-title" style="font-size: var(--text-xl);"><i class="bi bi-trophy" style="color: var(--gold);"></i> {{ __('Best sellers') }}</h2>
                @forelse($bestSellers as $i => $row)
                    <a href="{{ route('public.articles.show', $row->paper->slug) }}" class="best-row">
                        <span class="best-rank">{{ $i + 1 }}</span>
                        <span class="min-w-0 flex-grow-1">
                            <span class="d-block fw-bold text-truncate">{{ $row->paper->title }}</span>
                            <span class="wt-small wt-muted">{{ $row->paper->category_label ? __($row->paper->category_label) : '' }}</span>
                        </span>
                        <span class="text-end">
                            <span class="d-block fw-bold" style="color: var(--primary-dark);">{{ number_format($row->copies) }}</span>
                            <span class="wt-small wt-muted">{{ __('copies') }}</span>
                        </span>
                    </a>
                @empty
                    <p class="wt-muted wt-small">{{ __('No premium papers yet.') }}</p>
                @endforelse
            </div>
        </div>

        {{-- Released papers --}}
        <div class="d-flex flex-wrap justify-content-between align-items-end gap-2 mb-3">
            <h2 class="wt-section-title mb-0">{{ __('Released papers') }}</h2>
            <span class="wt-muted wt-small">{{ trans_choice(':count paper|:count papers', $rows->count(), ['count' => $rows->count()]) }}</span>
        </div>
        <div class="wt-tabs">
            <a href="{{ route('public.authors.show', ['role' => $role, 'author' => $author]) }}" class="wt-tab {{ $categoryFilter ? '' : 'is-active' }}">{{ __('All') }}</a>
            @foreach($papers->pluck('category')->filter()->unique() as $cat)
                <a href="{{ route('public.authors.show', ['role' => $role, 'author' => $author, 'category' => $cat]) }}" class="wt-tab {{ $categoryFilter === $cat ? 'is-active' : '' }}">{{ __(Submission::categoryLabel($cat)) }}</a>
            @endforeach
        </div>
        <div class="article-tile-grid">
            @foreach($rows as $row)
                @include('articles::partials.article-tile', ['article' => $row->paper, 'bestSellers' => $bestSlug ? [$bestSlug] : []])
            @endforeach
        </div>
    </div>

    @include('partials.footer')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
    <script>
        (function () {
            const el = document.getElementById('authorChart');
            if (!el || !window.Chart) return;
            Chart.defaults.font.family = "'Lato', sans-serif";
            Chart.defaults.color = '#6B6455';
            new Chart(el, {
                type: 'bar',
                data: {
                    labels: @json($chart['labels']),
                    datasets: [
                        { label: @json(__('Copies bought')), data: @json($chart['copies']), backgroundColor: '#C4A840', borderRadius: 4, maxBarThickness: 26 },
                        { label: @json(__('Other reads')), data: @json($chart['reads']), backgroundColor: 'rgba(123,107,53,0.28)', borderRadius: 4, maxBarThickness: 26 }
                    ]
                },
                options: {
                    indexAxis: 'y',
                    maintainAspectRatio: false,
                    scales: {
                        x: { stacked: true, grid: { color: 'rgba(123,107,53,0.1)' } },
                        y: { stacked: true, grid: { display: false }, ticks: { color: '#2C2416' } }
                    },
                    plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, boxHeight: 12 } } }
                }
            });
        })();
    </script>

    <style>
        .author-hero { display: flex; flex-wrap: wrap; gap: 1.5rem; align-items: center; padding: 2rem; }
        .author-grid { grid-template-columns: 1fr; }
        @media (min-width: 992px) { .author-grid { grid-template-columns: 2fr 1fr; } }
        .best-row { display: flex; align-items: center; gap: .9rem; padding: .85rem 0; border-bottom: 1px solid rgba(123,107,53,.12); text-decoration: none; color: var(--dark); }
        .best-row:last-child { border-bottom: none; }
        .best-row:hover .fw-bold { color: var(--primary); }
        .best-rank { font-family: 'IM Fell English', serif; font-size: 1.8rem; color: var(--gold); width: 1.6rem; text-align: center; line-height: 1; }
    </style>
@endsection
