@extends('layouts.app')

@section('content')
    @include('partials.navbar')
    @include('partials.werta-ui')
    @php
        use Articles\Support\MockBilling as MB;
        $balance = MB::balance($state);
        $subOn   = $state['sub']['active'];
        $left    = MB::SUB_CAP - count($state['sub']['used']);
        $here    = '/articles/' . $article->slug . '/subscribe';
        $short   = max(0, MB::ARTICLE_TOKENS - $balance);
    @endphp

    <div class="wt-page wt-page--narrow">
        <a href="{{ route('public.articles.show', ['slug' => $article->slug]) }}" class="wt-back"><i class="bi bi-arrow-left"></i> {{ __('Back to article') }}</a>

        <div class="wt-header">
            <div>
                <p class="wt-eyebrow">{{ __('Checkout') }}</p>
                <h1 class="wt-title">{{ __('Unlock “:title”', ['title' => $article->title]) }}</h1>
                <p class="wt-subtitle">{{ __('A premium paper by :name.', ['name' => $article->author_name]) }} {{ __('Every premium paper costs the same: :n tokens (:price).', ['n' => MB::ARTICLE_TOKENS, 'price' => MB::rm(MB::articlePrice())]) }}</p>
            </div>
            <span class="wt-demo-tag"><i class="bi bi-info-circle"></i> {{ __('DEMO — no real payment') }}</span>
        </div>

        @include('partials.flash')

        @if($article->access_type !== 'paid' || ($hasAccess && $viewer))
            <div class="wt-card text-center">
                <i class="bi bi-unlock-fill" style="font-size: 2rem; color: var(--gold);"></i>
                <h3 class="wt-section-title mt-2">{{ $article->access_type !== 'paid' ? __('This article is free') : __('You already have access') }}</h3>
                <p class="wt-muted">{{ $article->access_type !== 'paid' ? __('No purchase needed. Anyone can read it.') : __('Open the article to keep reading.') }}</p>
                <a href="{{ route('public.articles.show', ['slug' => $article->slug]) }}" class="wt-btn wt-btn--gold">{{ __('Read now') }}</a>
            </div>
        @elseif(!$viewer)
            <div class="wt-card text-center">
                <i class="bi bi-person-lock" style="font-size: 2rem; color: var(--gold);"></i>
                <h3 class="wt-section-title mt-2">{{ __('Sign in to continue') }}</h3>
                <p class="wt-muted">{{ __('You need a reader account to buy, subscribe or send a gift.') }}</p>
                <div class="d-flex flex-wrap gap-2 justify-content-center">
                    @if(\App\Support\DemoAuth::enabled())
                        <a href="{{ route('demo.index') }}" class="wt-btn wt-btn--gold">{{ __('Choose a demo account') }}</a>
                    @endif
                    <a href="{{ url('/client/login') }}" class="wt-btn wt-btn--outline">{{ __('Sign in as client') }}</a>
                    <a href="{{ url('/parent/login') }}" class="wt-btn wt-btn--outline">{{ __('Parent') }}</a>
                    <a href="{{ route('counselor.login') }}" class="wt-btn wt-btn--outline">{{ __('Counselor') }}</a>
                </div>
            </div>
        @else
            {{-- Balance strip --}}
            <div class="wt-card wt-card--dark d-flex flex-wrap align-items-center justify-content-between gap-3 wt-mb">
                <div>
                    <div class="wt-card-label">{{ __('Your balance') }} · {{ $viewer[1] }}</div>
                    <div class="wt-token" style="font-size: 2rem; color: var(--gold);"><i class="bi bi-coin"></i> {{ $balance }} <span style="font-size: 1rem; color: var(--cream); font-weight: 400;">{{ __('tokens') }}</span></div>
                    <div class="wt-small" style="opacity: .75;">
                        {{ __(':paid paid + :bonus bonus', ['paid' => $state['paid'], 'bonus' => $state['bonus']]) }}
                        @if($state['bonus'] > 0 && $state['bonus_expires']) · {{ __('bonus expires :date', ['date' => \Illuminate\Support\Carbon::parse($state['bonus_expires'])->translatedFormat('d M Y')]) }}@endif
                    </div>
                </div>
                <a href="{{ route('mock.wallet') }}" class="wt-btn wt-btn--gold wt-btn--sm"><i class="bi bi-wallet2"></i> {{ __('Open wallet') }}</a>
            </div>

            <div class="wt-grid wt-grid-3 wt-mb">
                {{-- Option 1: buy once --}}
                <div class="wt-card wt-card--feature d-flex flex-column">
                    <div class="wt-card-label">{{ __('Option 1 · Buy once') }}</div>
                    <div class="wt-pack-price"><i class="bi bi-coin" style="color: var(--gold);"></i> {{ MB::ARTICLE_TOKENS }}</div>
                    <div class="wt-pack-per">{{ __(':price · yours forever', ['price' => MB::rm(MB::articlePrice())]) }}</div>
                    <ul class="wt-small wt-muted ps-3 mb-3">
                        <li>{{ __('Stays in your library, even without a subscription') }}</li>
                        <li>{{ __('The author earns :rm from this copy', ['rm' => MB::rm(MB::AUTHOR_PER_COPY)]) }}</li>
                    </ul>
                    <div class="mt-auto">
                        @if($balance >= MB::ARTICLE_TOKENS)
                            <form method="POST" action="{{ route('mock.buy', $article->slug) }}">@csrf
                                <button class="wt-btn wt-btn--dark wt-btn--block">{{ __('Unlock for :n tokens', ['n' => MB::ARTICLE_TOKENS]) }}</button>
                            </form>
                        @else
                            <a href="#topup" class="wt-btn wt-btn--outline wt-btn--block">{{ __('Need :n more tokens', ['n' => $short]) }}</a>
                        @endif
                    </div>
                </div>

                {{-- Option 2: subscription --}}
                <div class="wt-card d-flex flex-column">
                    <div class="wt-card-label">{{ __('Option 2 · Subscribe') }}</div>
                    <div class="wt-pack-price"><i class="bi bi-coin" style="color: var(--gold);"></i> {{ MB::SUB_TOKENS }}<span style="font-size: 1rem; font-weight: 400;"> / {{ __('month') }}</span></div>
                    <div class="wt-pack-per">{{ __(':price · :n papers a month', ['price' => MB::rm(MB::subPrice()), 'n' => MB::SUB_CAP]) }}</div>
                    <ul class="wt-small wt-muted ps-3 mb-3">
                        <li>{{ __('Access ends when the subscription ends') }}</li>
                        <li>{{ __('Subscription reads do not pay the author') }}</li>
                    </ul>
                    <div class="mt-auto">
                        @if($subOn)
                            <div class="wt-small mb-2">{{ __('Allowance left') }}: <strong>{{ $left }}/{{ MB::SUB_CAP }}</strong></div>
                            @if($left > 0)
                                <form method="POST" action="{{ route('mock.allowance', $article->slug) }}">@csrf
                                    <button class="wt-btn wt-btn--dark wt-btn--block">{{ __('Use 1 allowance to read') }}</button>
                                </form>
                            @else
                                <div class="wt-alert wt-alert--info mb-0">{{ __('Allowance used up this month.') }}</div>
                            @endif
                        @elseif($balance >= MB::SUB_TOKENS)
                            <form method="POST" action="{{ route('mock.subscribe') }}">@csrf
                                <input type="hidden" name="return" value="{{ $here }}">
                                <button class="wt-btn wt-btn--outline wt-btn--block">{{ __('Subscribe for :n tokens', ['n' => MB::SUB_TOKENS]) }}</button>
                            </form>
                        @else
                            <a href="#topup" class="wt-btn wt-btn--outline wt-btn--block">{{ __('Need :n more tokens', ['n' => MB::SUB_TOKENS - $balance]) }}</a>
                        @endif
                    </div>
                </div>

                {{-- Option 3: gift --}}
                <div class="wt-card d-flex flex-column" id="gift">
                    <div class="wt-card-label">{{ __('Option 3 · Gift it') }}</div>
                    <div class="wt-pack-price"><i class="bi bi-gift" style="color: var(--gold);"></i> {{ MB::ARTICLE_TOKENS }}</div>
                    <div class="wt-pack-per">{{ __('Send this paper to someone else') }}</div>
                    <form method="POST" action="{{ route('mock.gift', $article->slug) }}" class="mt-auto">@csrf
                        <label class="wt-label" for="gift-recipient">{{ __('Recipient') }}</label>
                        <select name="recipient" id="gift-recipient" class="wt-select mb-2" required>
                            @foreach($recipients as $key => $acc)
                                <option value="{{ $key }}">{{ $acc['name'] }} ({{ __('nav.roles.' . $acc['role']) }})</option>
                            @endforeach
                        </select>
                        <input type="text" name="note" maxlength="140" class="wt-input mb-2" placeholder="{{ __('Add a short note (optional)') }}" aria-label="{{ __('Gift note') }}">
                        <button class="wt-btn wt-btn--outline wt-btn--block" @disabled($balance < MB::ARTICLE_TOKENS || $recipients->isEmpty())>{{ __('Send gift') }}</button>
                        <div class="wt-help">{{ __('Demo: pick one of the demo accounts. The author still earns from gifts.') }}</div>
                    </form>
                </div>
            </div>

            {{-- Top-up packs --}}
            <h2 class="wt-section-title" id="topup">{{ __('Top up tokens') }}</h2>
            <p class="wt-section-sub">{{ __('Buy 5 papers’ worth and get 1 free. Bonus tokens expire after :d days.', ['d' => MB::BONUS_EXPIRY_DAYS]) }}</p>
            <div class="wt-grid wt-grid-4 wt-mb">
                @foreach(MB::PACKS as $id => $pack)
                    @include('articles::partials.pack', ['id' => $id, 'pack' => $pack, 'return' => $here])
                @endforeach
            </div>

            {{-- Promo code --}}
            <div class="wt-card wt-card--cream">
                <div class="d-flex flex-wrap gap-3 align-items-center justify-content-between">
                    <div>
                        <div class="wt-card-label">{{ __('Promo code') }}</div>
                        <div class="wt-small wt-muted">
                            @foreach($promos as $p)
                                <span class="me-2"><span class="wt-code">{{ $p['code'] }}</span> {{ __($p['desc']) }}</span>
                            @endforeach
                        </div>
                    </div>
                    <form method="POST" action="{{ route('mock.promo') }}" class="wt-inline-form" style="min-width: 280px;">@csrf
                        <input type="hidden" name="return" value="{{ $here }}">
                        <input type="text" name="code" class="wt-input" placeholder="{{ __('Enter code') }}" aria-label="{{ __('Promo code') }}" required>
                        <button class="wt-btn wt-btn--dark">{{ __('Apply') }}</button>
                    </form>
                </div>
            </div>
        @endif
    </div>

    @include('partials.footer')
@endsection
