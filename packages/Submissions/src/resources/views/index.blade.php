@extends('layouts.app')

@section('content')
    @include('partials.navbar')
    @include('partials.werta-ui')
    @php
        use Articles\Support\MockBilling as MB;
        $tabs = [
            'all'       => __('All'),
            'review'    => __('In review'),
            'action'    => __('Needs your action'),
            'published' => __('Published'),
            'rejected'  => __('Not approved'),
        ];
    @endphp

    <div class="wt-page">
        <div class="wt-header">
            <div>
                <p class="wt-eyebrow">{{ __('Author studio') }}</p>
                <h1 class="wt-title">{{ __('Your Articles') }}</h1>
                <p class="wt-subtitle">{{ __('Track every manuscript from submission to publication. Premium papers earn you :rm for every copy bought.', ['rm' => MB::rm(MB::AUTHOR_PER_COPY)]) }}</p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                @if($authorUrl)
                    <a href="{{ $authorUrl }}" class="wt-btn wt-btn--outline"><i class="bi bi-person-vcard"></i> {{ __('My author page') }}</a>
                @endif
                <a href="{{ route('submissions.earnings') }}" class="wt-btn wt-btn--outline"><i class="bi bi-cash-coin"></i> {{ __('Earnings') }}</a>
                <a href="{{ route('submissions.create') }}" class="wt-btn wt-btn--gold"><i class="bi bi-plus-lg"></i> {{ __('Publish an Article') }}</a>
            </div>
        </div>

        @include('partials.flash')

        {{-- Summary --}}
        <div class="wt-grid wt-grid-4 wt-mb">
            <div class="wt-card wt-card--flat">
                <div class="wt-card-label">{{ __('Published') }}</div>
                <div class="wt-stat-value">{{ $counts['published'] }}</div>
                <div class="wt-stat-sub">{{ __('live on Werta') }}</div>
            </div>
            <div class="wt-card wt-card--flat">
                <div class="wt-card-label">{{ __('In review') }}</div>
                <div class="wt-stat-value">{{ $counts['review'] }}</div>
                <div class="wt-stat-sub">{{ __('with verified reviewers') }}</div>
            </div>
            <div class="wt-card wt-card--flat">
                <div class="wt-card-label">{{ __('Needs your action') }}</div>
                <div class="wt-stat-value {{ $counts['action'] ? 'is-gold' : '' }}">{{ $counts['action'] }}</div>
                <div class="wt-stat-sub">{{ __('revisions or publish fee') }}</div>
            </div>
            <div class="wt-card wt-card--flat">
                <div class="wt-card-label">{{ __('Copies sold') }}</div>
                <div class="wt-stat-value is-gold">{{ number_format($copies) }}</div>
                <div class="wt-stat-sub">{{ __(':rm earned', ['rm' => MB::rm($copies * MB::AUTHOR_PER_COPY)]) }}</div>
            </div>
        </div>

        <div class="wt-tabs">
            @foreach($tabs as $key => $label)
                <a href="{{ route('submissions.index', $key === 'all' ? [] : ['tab' => $key]) }}" class="wt-tab {{ $tab === $key ? 'is-active' : '' }}">{{ $label }}<span class="wt-tab-count">{{ $counts[$key] }}</span></a>
            @endforeach
        </div>

        <div class="wt-stack">
            @forelse($submissions as $submission)
                @php $paid = $submission->access_type === 'paid'; @endphp
                <div class="wt-card wt-card--flat studio-row">
                    <div class="studio-tile">{{ strtoupper(mb_substr($submission->title, 0, 2)) }}</div>
                    <div class="flex-grow-1 min-w-0">
                        <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                            @include('submissions::partials.status', ['submission' => $submission, 'showSteps' => false])
                            @if($submission->category_label)
                                <span class="wt-badge wt-badge--gold"><i class="bi {{ $submission->category_icon }}"></i> {{ __($submission->category_label) }}</span>
                            @endif
                        </div>
                        <a href="{{ route('submissions.show', $submission) }}" class="studio-title">{{ $submission->title }}</a>
                        <div class="studio-meta">
                            <span><i class="bi bi-calendar3"></i> {{ $submission->submitted_at->translatedFormat('d M Y') }}</span>
                            <span><i class="bi {{ $submission->visibility === 'private' ? 'bi-lock' : 'bi-globe2' }}"></i> {{ __(ucfirst($submission->visibility)) }}</span>
                            <span><i class="bi {{ $paid ? 'bi-star-fill' : 'bi-tag' }}"></i> {{ $paid ? __('Premium · :price', ['price' => MB::rm(MB::articlePrice())]) : __('Free') }}</span>
                            @if($paid && $submission->status === 'published')
                                <span class="studio-sales"><i class="bi bi-bag-check"></i> {{ __(':n copies · :rm', ['n' => MB::totalSales($submission), 'rm' => MB::rm(MB::authorEarning($submission))]) }}</span>
                            @endif
                        </div>
                        <div style="max-width: 560px;">
                            @include('submissions::partials.status', ['submission' => $submission, 'showBadge' => false])
                        </div>
                    </div>
                    <div class="studio-actions">
                        @if($submission->status === 'accepted' && $paid)
                            <form method="POST" action="{{ route('submissions.pay-fee', $submission) }}">@csrf
                                <button class="wt-btn wt-btn--gold wt-btn--sm"><i class="bi bi-credit-card"></i> {{ __('Pay :fee fee', ['fee' => MB::rm(MB::PUBLISH_FEE)]) }}</button>
                            </form>
                        @endif
                        @if($submission->status === 'published' && $submission->visibility === 'public')
                            <a href="{{ route('public.articles.show', $submission->slug) }}" class="wt-btn wt-btn--outline wt-btn--sm"><i class="bi bi-eye"></i> {{ __('View live') }}</a>
                        @endif
                        <a href="{{ route('submissions.show', $submission) }}" class="wt-btn wt-btn--ghost wt-btn--sm">{{ __('Details') }} <i class="bi bi-chevron-right"></i></a>
                    </div>
                </div>
            @empty
                <div class="wt-card wt-card--flat wt-empty">
                    <i class="bi bi-journal-plus"></i>
                    @if($tab === 'all')
                        <p class="mb-3">{{ __('You haven\'t submitted anything yet.') }}</p>
                        <a href="{{ route('submissions.create') }}" class="wt-btn wt-btn--gold wt-btn--sm">{{ __('Publish your first article') }}</a>
                    @else
                        <p class="mb-0">{{ __('Nothing here right now.') }}</p>
                    @endif
                </div>
            @endforelse
        </div>
    </div>

    @include('partials.footer')

    <style>
        .studio-row { display: flex; gap: 1.2rem; align-items: flex-start; flex-wrap: wrap; }
        .studio-tile { width: 48px; height: 48px; border-radius: var(--radius-lg); background: rgba(196,168,64,.16); color: var(--primary); font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .studio-title { display: block; font-weight: 700; font-size: var(--text-lg); color: var(--dark); text-decoration: none; line-height: 1.3; }
        .studio-title:hover { color: var(--primary); }
        .studio-meta { display: flex; flex-wrap: wrap; gap: .3rem 1rem; font-size: var(--text-sm); color: var(--muted); margin: .35rem 0 .2rem; }
        .studio-sales { color: var(--primary-dark); font-weight: 700; }
        .studio-actions { display: flex; flex-direction: column; align-items: flex-end; gap: .5rem; }
        @media (max-width: 767px) {
            .studio-tile { display: none; }
            .studio-actions { flex-direction: row; flex-wrap: wrap; align-items: center; width: 100%; }
        }
    </style>
@endsection
