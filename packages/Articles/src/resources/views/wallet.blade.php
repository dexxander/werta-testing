@extends('layouts.app')

@section('content')
    @include('partials.navbar')
    @include('partials.werta-ui')
    @php
        use Articles\Support\MockBilling as MB;
        use Articles\Support\Referrals;
        $subOn = $state['sub']['active'];
        $used  = count($state['sub']['used']);
        $balance = MB::balance($state);
        $typeIcon = [
            'topup' => 'bi-plus-circle', 'bonus' => 'bi-stars', 'promo' => 'bi-tag', 'referral' => 'bi-people',
            'purchase' => 'bi-bag-check', 'gift' => 'bi-gift', 'gift_in' => 'bi-gift', 'subscription' => 'bi-arrow-repeat',
            'allowance' => 'bi-ticket-perforated', 'expiry' => 'bi-hourglass-split',
        ];
        $shareUrl = $myCode ? route('mock.referral.link', $myCode) : null;
    @endphp

    <div class="wt-page">
        <div class="wt-header">
            <div>
                <p class="wt-eyebrow">{{ __('Werta tokens') }}</p>
                <h1 class="wt-title">{{ __('Wallet & Library') }}</h1>
                <p class="wt-subtitle">{{ __('One wallet for everything on Werta. Tokens pay for papers, gifts and the subscription, and later courses and partner rewards.') }}</p>
            </div>
            <span class="wt-demo-tag"><i class="bi bi-info-circle"></i> {{ __('DEMO — fake data, no real payment') }}</span>
        </div>

        @include('partials.flash')

        @if(!$viewer)
            <div class="wt-card text-center">
                <i class="bi bi-person-lock" style="font-size: 2rem; color: var(--gold);"></i>
                <h3 class="wt-section-title mt-2">{{ __('Sign in to use the wallet.') }}</h3>
                <a href="{{ \App\Support\DemoAuth::enabled() ? route('demo.index') : url('/client/login') }}" class="wt-btn wt-btn--gold">{{ __('Sign in') }}</a>
            </div>
        @else
            <div class="wt-grid wt-grid-2 wt-mb">
                {{-- Balance --}}
                <div class="wt-card wt-card--dark">
                    <div class="wt-card-label">{{ __('Balance') }} · {{ $viewer[1] }}</div>
                    <div class="wt-token" style="font-size: 3rem; color: var(--gold); line-height: 1.1;"><i class="bi bi-coin"></i> {{ $balance }}</div>
                    <div style="opacity: .8;">{{ __('tokens · worth :rm', ['rm' => MB::rm(MB::tokensToSen($balance))]) }}</div>
                    <div class="d-flex flex-wrap gap-4 mt-3">
                        <div>
                            <div class="wt-card-label">{{ __('Paid tokens') }}</div>
                            <div class="fw-bold">{{ $state['paid'] }} <span class="wt-small" style="opacity: .7;">{{ __('never expire') }}</span></div>
                        </div>
                        <div>
                            <div class="wt-card-label">{{ __('Bonus tokens') }}</div>
                            <div class="fw-bold">{{ $state['bonus'] }}
                                @if($state['bonus'] > 0 && $state['bonus_expires'])
                                    <span class="wt-small" style="opacity: .7;">{{ __('expire :date', ['date' => \Illuminate\Support\Carbon::parse($state['bonus_expires'])->translatedFormat('d M Y')]) }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="wt-small mt-3" style="opacity: .7;">
                        {{ trans_choice('Enough for :count premium paper.|Enough for :count premium papers.', intdiv($balance, MB::ARTICLE_TOKENS), ['count' => intdiv($balance, MB::ARTICLE_TOKENS)]) }}
                        {{ __('Bonus tokens are spent first.') }}
                    </div>
                </div>

                {{-- Subscription --}}
                <div class="wt-card">
                    <div class="wt-card-label">{{ __('Subscription') }}</div>
                    @if($subOn)
                        <div class="wt-stat-value is-gold">{{ __('Active') }} <i class="bi bi-check-circle-fill" style="color: var(--gold);"></i></div>
                        <div class="wt-small wt-muted">{{ __('Renews :date', ['date' => \Illuminate\Support\Carbon::parse($state['sub']['renews'])->translatedFormat('d M Y')]) }}</div>
                        <div class="wt-progress mt-3"><div class="wt-progress-fill" style="width: {{ $used / MB::SUB_CAP * 100 }}%"></div></div>
                        <div class="wt-small mt-1">{{ __('Allowance used') }}: <strong>{{ $used }}/{{ MB::SUB_CAP }}</strong></div>
                        <form method="POST" action="{{ route('mock.expire') }}" class="mt-3">@csrf
                            <button class="wt-btn wt-btn--outline wt-btn--sm">{{ __('Simulate expiry') }}</button>
                        </form>
                    @else
                        <div class="wt-stat-value" style="color: var(--muted);">{{ __('Not active') }}</div>
                        <div class="wt-small wt-muted">{{ __(':n tokens (:price) a month · :cap papers', ['n' => MB::SUB_TOKENS, 'price' => MB::rm(MB::subPrice()), 'cap' => MB::SUB_CAP]) }}</div>
                        <p class="wt-small wt-muted mt-2 mb-3">{{ __('Good for heavy readers. Papers read this way lock again when the subscription ends, and they earn the author nothing.') }}</p>
                        <form method="POST" action="{{ route('mock.subscribe') }}">@csrf
                            <button class="wt-btn wt-btn--dark wt-btn--sm" @disabled($balance < MB::SUB_TOKENS)>{{ __('Subscribe for :n tokens', ['n' => MB::SUB_TOKENS]) }}</button>
                        </form>
                        @if($used)<div class="wt-small mt-2 wt-muted">{{ __('Papers read via subscription are locked again.') }}</div>@endif
                    @endif
                </div>
            </div>

            {{-- Packs --}}
            <h2 class="wt-section-title">{{ __('Top up') }}</h2>
            <p class="wt-section-sub">{{ __('Every premium paper is :n tokens (:price). Buy 5 papers’ worth and get 1 free.', ['n' => MB::ARTICLE_TOKENS, 'price' => MB::rm(MB::articlePrice())]) }}</p>
            <div class="wt-grid wt-grid-4 wt-mb">
                @foreach(MB::PACKS as $id => $pack)
                    @include('articles::partials.pack', ['id' => $id, 'pack' => $pack])
                @endforeach
            </div>

            <div class="wt-grid wt-grid-2 wt-mb">
                {{-- Promotions --}}
                <div class="wt-card">
                    <div class="wt-card-label">{{ __('Promotions') }}</div>
                    <h3 class="wt-section-title" style="font-size: var(--text-xl);">{{ __('Offers for you') }}</h3>
                    <form method="POST" action="{{ route('mock.promo') }}" class="wt-inline-form mb-3">@csrf
                        <input type="text" name="code" class="wt-input" placeholder="{{ __('Enter a promo code') }}" aria-label="{{ __('Promo code') }}" required>
                        <button class="wt-btn wt-btn--dark">{{ __('Apply') }}</button>
                    </form>
                    <div class="wt-stack">
                        @foreach($promos as $p)
                            @php $usedPromo = isset($state['promos'][$p['code']]); @endphp
                            <div class="d-flex gap-3 align-items-start">
                                <i class="bi {{ $p['type'] === 'bonus_pct' ? 'bi-graph-up-arrow' : 'bi-gift' }}" style="color: var(--gold); font-size: 1.3rem;"></i>
                                <div class="flex-grow-1">
                                    <div class="fw-bold">{{ __($p['title']) }} <span class="wt-code ms-1">{{ $p['code'] }}</span></div>
                                    <div class="wt-small wt-muted">{{ __($p['desc']) }} {{ __('Ends :date.', ['date' => \Illuminate\Support\Carbon::parse($p['ends'])->translatedFormat('d M Y')]) }}</div>
                                    @if($state['armed'] === $p['code'])
                                        <span class="wt-badge wt-badge--green mt-1">{{ __('Ready for your next top-up') }}</span>
                                    @endif
                                </div>
                                @if($usedPromo)
                                    <span class="wt-badge wt-badge--grey">{{ __('Used') }}</span>
                                @elseif(($p['new_users_only'] ?? false) && $state['topups'] > 0)
                                    <span class="wt-badge wt-badge--grey">{{ __('New readers only') }}</span>
                                @else
                                    <form method="POST" action="{{ route('mock.promo') }}">@csrf
                                        <input type="hidden" name="code" value="{{ $p['code'] }}">
                                        <button class="wt-btn wt-btn--outline wt-btn--sm">{{ __('Use') }}</button>
                                    </form>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4 pt-3" style="border-top: 1px solid rgba(123,107,53,.15);">
                        <div class="wt-card-label">{{ __('Coming soon · partner rewards') }}</div>
                        @foreach($partners as $p)
                            <div class="d-flex gap-3 align-items-center mt-2 wt-muted">
                                <i class="bi {{ str_contains($p['title'], 'café') ? 'bi-cup-hot' : 'bi-shop' }}" style="font-size: 1.2rem;"></i>
                                <div class="flex-grow-1 wt-small"><strong>{{ __($p['title']) }}</strong> — {{ __($p['desc']) }} <span class="d-block">{{ __($p['partner']) }}</span></div>
                                <span class="wt-badge wt-badge--grey">{{ __('Soon') }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Referral --}}
                <div class="wt-card">
                    <div class="d-flex justify-content-between align-items-start gap-2">
                        <div class="wt-card-label">{{ __('Invite friends') }}</div>
                        <span class="wt-badge wt-badge--yellow">{{ __('Rules in draft') }}</span>
                    </div>
                    <h3 class="wt-section-title" style="font-size: var(--text-xl);">{{ __('Share Werta, earn tokens') }}</h3>
                    <p class="wt-small wt-muted">{{ __('When a friend joins with your code and makes their first top-up, you get :a bonus tokens and they get :b.', ['a' => Referrals::RULES['referrer_tokens'], 'b' => Referrals::RULES['referee_tokens']]) }}</p>
                    <div class="wt-label mt-3">{{ __('Your code') }}</div>
                    <div class="d-flex flex-wrap gap-2 align-items-center mb-2">
                        <span class="wt-code" style="font-size: 1.1rem;">{{ $myCode }}</span>
                        <button type="button" class="wt-btn wt-btn--ghost wt-btn--sm" onclick="navigator.clipboard && navigator.clipboard.writeText('{{ $shareUrl }}'); this.querySelector('span').textContent = WertaI18n.t('Link copied');">
                            <i class="bi bi-link-45deg"></i> <span>{{ __('Copy invite link') }}</span>
                        </button>
                    </div>
                    <div class="wt-help">{{ $shareUrl }}</div>

                    <div class="mt-4 pt-3" style="border-top: 1px solid rgba(123,107,53,.15);">
                        <div class="wt-label">{{ __('Were you invited?') }}</div>
                        @if($referredByName)
                            <div class="wt-small">
                                {{ __('Invited by :name.', ['name' => $referredByName]) }}
                                @if($state['ref_rewarded'])
                                    <span class="wt-badge wt-badge--green">{{ __('Bonus paid') }}</span>
                                @else
                                    <span class="wt-badge wt-badge--yellow">{{ __('Bonus after first top-up') }}</span>
                                @endif
                            </div>
                        @elseif($state['topups'] > 0)
                            <div class="wt-small wt-muted">{{ __('Referral codes can only be added before your first top-up.') }}</div>
                        @else
                            <form method="POST" action="{{ route('mock.referral') }}" class="wt-inline-form">@csrf
                                <input type="text" name="code" value="{{ $prefill }}" class="wt-input" placeholder="{{ __('Friend’s code, e.g. WT-ABC123') }}" aria-label="{{ __('Referral code') }}" required>
                                <button class="wt-btn wt-btn--outline">{{ __('Add') }}</button>
                            </form>
                            <div class="wt-help">{{ __('Demo: copy the code shown in another demo account’s wallet.') }}</div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Library --}}
            <h2 class="wt-section-title">{{ __('My Library') }}</h2>
            <div class="wt-list wt-mb">
                @foreach($library as $item)
                    <a href="{{ route('public.articles.show', $item->paper->slug) }}" class="wt-row">
                        <span class="d-flex align-items-center gap-3 min-w-0">
                            <i class="bi {{ $item->via === 'gift' ? 'bi-gift' : 'bi-bag-check' }}" style="color: var(--gold); font-size: 1.2rem;"></i>
                            <span class="min-w-0">
                                <span class="wt-row-title d-block text-truncate">{{ $item->paper->title }}</span>
                                <span class="wt-row-meta">{{ $item->paper->author_name }}@if($item->paper->category_label) · {{ __($item->paper->category_label) }}@endif</span>
                            </span>
                        </span>
                        <span class="wt-row-meta text-end">
                            @if($item->via === 'gift')
                                {{ __('Gift from :name', ['name' => $item->from]) }}
                            @else
                                {{ __('Owned forever') }}
                            @endif
                        </span>
                    </a>
                @endforeach
                @foreach($allowancePapers as $p)
                    @continue(isset($state['purchases'][$p->slug]))
                    <a href="{{ route('public.articles.show', $p->slug) }}" class="wt-row">
                        <span class="d-flex align-items-center gap-3 min-w-0">
                            <i class="bi bi-ticket-perforated" style="color: var(--primary); font-size: 1.2rem;"></i>
                            <span class="wt-row-title text-truncate">{{ $p->title }}</span>
                        </span>
                        @if($subOn)
                            <span class="wt-row-meta">{{ __('Via subscription') }}</span>
                        @else
                            <span class="wt-row-meta" style="color: #a33;"><i class="bi bi-lock-fill"></i> {{ __('Locked — subscription ended') }}</span>
                        @endif
                    </a>
                @endforeach
                @if($library->isEmpty() && $allowancePapers->isEmpty())
                    <div class="wt-empty">
                        <i class="bi bi-journal-bookmark"></i>
                        <p class="mb-2">{{ __('Nothing here yet. Open a premium article to read its free preview and unlock it.') }}</p>
                        <a href="{{ route('public.articles') }}" class="wt-btn wt-btn--outline wt-btn--sm">{{ __('Browse articles') }}</a>
                    </div>
                @endif
            </div>

            {{-- Ledger --}}
            <h2 class="wt-section-title">{{ __('Transactions') }}</h2>
            <div class="wt-list" style="overflow-x: auto;">
                <table class="wt-table">
                    <thead>
                        <tr>
                            <th>{{ __('Activity') }}</th>
                            <th class="wt-hide-sm">{{ __('Date') }}</th>
                            <th class="wt-num">{{ __('Paid') }}</th>
                            <th class="wt-num">{{ __('Bonus') }}</th>
                            <th class="wt-num wt-hide-sm">{{ __('Payment') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($state['ledger'] as $row)
                            <tr>
                                <td><i class="bi {{ $typeIcon[$row['type']] ?? 'bi-dot' }}" style="color: var(--primary);"></i> {{ $row['desc'] }}</td>
                                <td class="wt-hide-sm wt-muted wt-small">{{ $row['at'] }}</td>
                                <td class="wt-num {{ $row['paid'] > 0 ? 'wt-plus' : ($row['paid'] < 0 ? 'wt-minus' : 'wt-muted') }}">{{ $row['paid'] == 0 ? '—' : ($row['paid'] > 0 ? '+' : '−') . abs($row['paid']) }}</td>
                                <td class="wt-num {{ $row['bonus'] > 0 ? 'wt-plus' : ($row['bonus'] < 0 ? 'wt-minus' : 'wt-muted') }}">{{ $row['bonus'] == 0 ? '—' : ($row['bonus'] > 0 ? '+' : '−') . abs($row['bonus']) }}</td>
                                <td class="wt-num wt-hide-sm">{{ $row['money'] ? MB::rm($row['money']) : '—' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="wt-empty">{{ __('No transactions yet. Top up to get started.') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <form method="POST" action="{{ route('mock.reset') }}" class="mt-4 text-end">@csrf
                <button class="wt-btn wt-btn--ghost wt-btn--sm">{{ __('Reset demo data') }}</button>
            </form>
        @endif
    </div>

    @include('partials.footer')
@endsection
