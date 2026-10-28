@extends('layouts.app')

@section('content')
    @include('partials.navbar')
    @include('partials.werta-ui')

    <div class="wt-page wt-page--narrow">
        <a href="{{ route('public.articles') }}" class="wt-back"><i class="bi bi-arrow-left"></i> {{ __('Back to Articles') }}</a>

        <div class="wt-card wt-card--cream wt-mb d-flex flex-wrap gap-4 align-items-center" style="padding: 2rem;">
            <img src="{{ $reviewer->avatar_url }}" alt="{{ $reviewer->name }}" class="wt-avatar wt-avatar--lg">
            <div class="flex-grow-1 min-w-0">
                <p class="wt-eyebrow mb-1">{{ __('Verified reviewer') }} <i class="bi bi-shield-check" style="color: #3a7d44;"></i></p>
                <h1 class="wt-title" style="margin-bottom: .3rem;">{{ $reviewer->name }}</h1>
                <p class="wt-subtitle">{{ $reviewer->title }}@if($reviewer->institution) · {{ $reviewer->institution }}@endif</p>
                <div class="d-flex flex-wrap gap-2 mt-2">
                    @if($reviewer->field_label)<span class="wt-badge wt-badge--gold"><i class="bi bi-bookmark-star"></i> {{ __($reviewer->field_label) }}</span>@endif
                    @if($reviewer->qualifications)<span class="wt-badge wt-badge--grey"><i class="bi bi-mortarboard"></i> {{ $reviewer->qualifications }}</span>@endif
                    @if($reviewer->registration_no)<span class="wt-badge wt-badge--grey"><i class="bi bi-person-vcard"></i> {{ __('Reg. no. :no', ['no' => $reviewer->registration_no]) }}</span>@endif
                </div>
            </div>
        </div>

        <div class="wt-grid wt-grid-3 wt-mb">
            <div class="wt-card wt-card--flat">
                <div class="wt-card-label">{{ __('Reviews completed') }}</div>
                <div class="wt-stat-value">{{ $stats['reviews'] }}</div>
            </div>
            <div class="wt-card wt-card--flat">
                <div class="wt-card-label">{{ __('Average turnaround') }}</div>
                <div class="wt-stat-value">{{ trans_choice(':count day|:count days', (int) round($stats['avgDays']), ['count' => $stats['avgDays']]) }}</div>
            </div>
            <div class="wt-card wt-card--flat">
                <div class="wt-card-label">{{ __('Verified since') }}</div>
                <div class="wt-stat-value">{{ optional($reviewer->approved_at)->translatedFormat('M Y') ?? '—' }}</div>
            </div>
        </div>

        @if($reviewer->bio)
            <div class="wt-card wt-card--flat wt-mb">
                <div class="wt-card-label">{{ __('About') }}</div>
                <p class="mb-0">{{ $reviewer->bio }}</p>
            </div>
        @endif

        <h2 class="wt-section-title">{{ __('Papers reviewed') }}</h2>
        <div class="wt-list">
            @forelse($reviewed as $review)
                <a href="{{ route('public.articles.show', $review->submission->slug) }}" class="wt-row">
                    <span class="min-w-0">
                        <span class="wt-row-title d-block text-truncate">{{ $review->submission->title }}</span>
                        <span class="wt-row-meta">{{ $review->submission->author_name }}@if($review->submission->category_label) · {{ __($review->submission->category_label) }}@endif</span>
                    </span>
                    <span class="wt-row-meta text-end">{{ optional($review->submitted_at)->translatedFormat('d M Y') }}</span>
                </a>
            @empty
                <div class="wt-empty"><i class="bi bi-journal-check"></i>{{ __('No public reviews yet.') }}</div>
            @endforelse
        </div>
    </div>

    @include('partials.footer')
@endsection
