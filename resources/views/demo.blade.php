@extends('layouts.app')

@section('content')
    @include('partials.navbar')
    @include('partials.werta-ui')
    @php
        $current = session('staff_role')
            ?: (session('reviewer_logged_in') ? 'reviewer'
            : (session('counselor_logged_in') ? 'counselor'
            : (session('client_logged_in') ? 'client'
            : (session('parent_logged_in') ? 'parent' : null))));
        $personas = [
            'client' => ['icon' => 'bi-person', 'title' => 'Reader', 'name' => 'Client User',
                'desc' => 'Reads previews, tops up tokens, buys and gifts papers, uses promos and referrals.',
                'try' => ['Open a premium article', 'Top up the Five + 1 pack', 'Gift a paper to Parent User']],
            'parent' => ['icon' => 'bi-person-heart', 'title' => 'Reader and author', 'name' => 'Parent User',
                'desc' => 'Second reader for gifts and referrals. Has one accepted paper waiting for the RM 80 fee.',
                'try' => ['Open Wallet & Library to see gifts', 'Pay the publish fee in Your Articles']],
            'counselor' => ['icon' => 'bi-person-badge', 'title' => 'Author', 'name' => 'Counselor User',
                'desc' => 'Has published premium papers. Sees the author studio, RM 1 per copy earnings and a public author page.',
                'try' => ['Your Articles', 'Earnings', 'My author page']],
            'reviewer' => ['icon' => 'bi-patch-check', 'title' => 'Reviewer', 'name' => null,
                'desc' => 'An approved professional. Evaluates assigned manuscripts and decides if they are suitable to sell.',
                'try' => ['Open the review queue', 'Submit an evaluation']],
            'admin' => ['icon' => 'bi-shield-lock', 'title' => 'Admin', 'name' => 'Admin User',
                'desc' => 'Assigns reviewers, makes the final decision, approves reviewer applications, and reads the analytics.',
                'try' => ['Article Reviews', 'Reviewers', 'Analytics and Revenue']],
            'superadmin' => ['icon' => 'bi-shield-check', 'title' => 'Superadmin', 'name' => 'Superadmin User',
                'desc' => 'Everything an admin sees, plus administrator accounts.',
                'try' => ['Overview charts', 'Administrators']],
        ];
    @endphp

    <div class="wt-page">
        <div class="wt-header">
            <div>
                <p class="wt-eyebrow">{{ __('Demo accounts') }}</p>
                <h1 class="wt-title">{{ __('Choose who to be') }}</h1>
                <p class="wt-subtitle">{{ __('One click signs you in as any role. Your wallet, purchases and gifts stay in this browser while you switch, so you can follow one story from reader to author to admin.') }}</p>
            </div>
            <span class="wt-demo-tag"><i class="bi bi-info-circle"></i> {{ __('DEMO — local only') }}</span>
        </div>

        @include('partials.flash')

        <div class="wt-grid wt-grid-3 wt-mb">
            @foreach($personas as $key => $p)
                <div class="wt-card d-flex flex-column {{ $current === $key ? 'wt-card--feature' : '' }}">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <span class="demo-icon"><i class="bi {{ $p['icon'] }}"></i></span>
                        <div>
                            <div class="wt-card-label mb-0">{{ __($p['title']) }}</div>
                            <div class="fw-bold">{{ $p['name'] ?? __('Approved reviewer') }}</div>
                        </div>
                        @if($current === $key)<span class="wt-badge wt-badge--green ms-auto">{{ __('Signed in') }}</span>@endif
                    </div>
                    <p class="wt-small wt-muted">{{ __($p['desc']) }}</p>
                    <ul class="wt-small ps-3 mb-3" style="color: var(--primary-dark);">
                        @foreach($p['try'] as $t)<li>{{ __($t) }}</li>@endforeach
                    </ul>
                    <form method="POST" action="{{ route('demo.login', $key) }}" class="mt-auto">@csrf
                        @if($key === 'reviewer')
                            <select name="reviewer_id" class="wt-select mb-2" aria-label="{{ __('Reviewer') }}">
                                @foreach($reviewers as $r)
                                    <option value="{{ $r->id }}">{{ $r->name }} · {{ $r->title }}</option>
                                @endforeach
                            </select>
                        @endif
                        <button class="wt-btn {{ $current === $key ? 'wt-btn--outline' : 'wt-btn--gold' }} wt-btn--block" @disabled($key === 'reviewer' && $reviewers->isEmpty())>
                            {{ __('Continue as :role', ['role' => __($p['title'])]) }}
                        </button>
                    </form>
                </div>
            @endforeach
        </div>

        <div class="wt-card wt-card--cream">
            <h2 class="wt-section-title" style="font-size: var(--text-xl);">{{ __('Suggested demo route') }}</h2>
            <ol class="mb-0 demo-steps">
                <li>{{ __('Reader: open “Mindfulness for Exam Season”, read the free first pages, then top up and unlock it.') }}</li>
                <li>{{ __('Reader: gift “Choosing a Career Path After SPM” to Parent User, and try the promo code WELCOME50.') }}</li>
                <li>{{ __('Author: check Your Articles and Earnings, then open your public author page and its chart.') }}</li>
                <li>{{ __('Reviewer: evaluate “The Science of Teen Sleep Cycles” and mark whether it is suitable to sell.') }}</li>
                <li>{{ __('Admin: assign a reviewer to “Managing Exam Anxiety”, make a decision, then read Analytics and Revenue.') }}</li>
            </ol>
        </div>
    </div>

    @include('partials.footer')

    <style>
        .demo-icon { width: 44px; height: 44px; border-radius: var(--radius-full); background: rgba(196,168,64,.15); color: var(--primary-dark); display: inline-flex; align-items: center; justify-content: center; font-size: 1.3rem; flex-shrink: 0; }
        .demo-steps li { margin-bottom: .5rem; color: var(--dark); }
    </style>
@endsection
