@extends('layouts.app')

@section('content')
    @include('partials.navbar')
    @php
        use Articles\Support\MockBilling as MB;
        $subOn = $state['sub']['active'];
        $used  = count($state['sub']['used']);
        $typeIcon = ['topup' => 'bi-plus-circle', 'purchase' => 'bi-bag-check', 'subscription' => 'bi-arrow-repeat', 'allowance' => 'bi-ticket-perforated'];
    @endphp

    <div style="max-width: 900px; margin: 40px auto 70px; padding: 0 1.5rem;">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <h1 style="font-family: 'IM Fell English', serif; font-size: 2.2rem; color: var(--dark); margin:0;">{{ __('Wallet & Library') }}</h1>
            <span style="font-size: var(--text-xs); letter-spacing:1px; font-weight:700; color: var(--muted); background: var(--cream); border:1px solid var(--gold); border-radius: var(--radius-pill); padding:.2rem .8rem;">{{ __('DEMO — fake data, no real payment') }}</span>
        </div>

        @if(session('mock_ok'))<div class="alert alert-success py-2 small">{{ __(session('mock_ok')) }}</div>@endif
        @if(session('mock_error'))<div class="alert alert-danger py-2 small">{{ __(session('mock_error')) }}</div>@endif

        @if(!$viewer)
            <div style="background:#fff; border-radius: var(--radius-xl); box-shadow: var(--card-ring); padding:2.5rem; text-align:center;">
                <i class="bi bi-person-lock" style="font-size:2rem; color: var(--gold);"></i>
                <p class="mt-2">{{ __('Sign in to use the wallet.') }}</p>
                <a href="{{ url('/client/login') }}" class="btn-cta-filled" style="text-decoration:none; display:inline-block;">{{ __('Sign in') }}</a>
            </div>
        @else
            <div class="row g-4 mb-4">
                {{-- Balance + top-up --}}
                <div class="col-md-6">
                    <div style="background: var(--dark); color: var(--cream); border-radius: var(--radius-xl); padding: 1.8rem; height:100%;">
                        <div style="font-size: var(--text-xs); letter-spacing:1px; opacity:.7;">{{ strtoupper(__('Balance')) }} · {{ $viewer[1] }}</div>
                        <div style="font-size: 2.8rem; font-weight:700; color: var(--gold);">{{ MB::rm($state['wallet']) }}</div>
                        <div class="d-flex gap-2 flex-wrap mt-3">
                            @foreach([10, 20, 50, 100] as $amt)
                                <form method="POST" action="{{ route('mock.topup') }}" class="m-0">@csrf
                                    <input type="hidden" name="amount" value="{{ $amt }}">
                                    <button class="btn btn-sm" style="background: var(--gold); color: var(--dark); font-weight:700;">+ RM {{ $amt }}</button>
                                </form>
                            @endforeach
                        </div>
                        <div style="font-size: var(--text-xs); opacity:.6; margin-top:.8rem;">{{ __('Top up once, then buy small papers without a gateway fee each time.') }}</div>
                    </div>
                </div>

                {{-- Subscription --}}
                <div class="col-md-6">
                    <div style="background:#fff; border-radius: var(--radius-xl); box-shadow: var(--card-ring); padding: 1.8rem; height:100%;">
                        <div style="font-size: var(--text-xs); letter-spacing:1px; font-weight:700; color: var(--muted);">{{ strtoupper(__('Subscription')) }}</div>
                        @if($subOn)
                            <div style="font-size:1.4rem; font-weight:700; color: var(--primary-dark);">{{ __('Active') }} <i class="bi bi-check-circle-fill" style="color: var(--gold);"></i></div>
                            <div style="font-size: var(--text-base); color: var(--muted);">{{ __('Renews') }} {{ $state['sub']['renews'] }}</div>
                            <div class="progress mt-3" style="height:10px;"><div class="progress-bar" style="width: {{ $used / MB::SUB_CAP * 100 }}%; background: var(--gold);"></div></div>
                            <div style="font-size: var(--text-sm); margin-top:.3rem;">{{ __('Allowance used') }}: <strong>{{ $used }}/{{ MB::SUB_CAP }}</strong></div>
                            <form method="POST" action="{{ route('mock.expire') }}" class="mt-3">@csrf
                                <button class="btn btn-sm btn-outline-secondary">{{ __('Simulate expiry') }}</button>
                            </form>
                        @else
                            <div style="font-size:1.4rem; font-weight:700; color: var(--muted);">{{ __('Not active') }}</div>
                            <div style="font-size: var(--text-base); color: var(--muted);">{{ MB::rm(MB::SUB_PRICE) }}/{{ __('month') }} · {{ MB::SUB_CAP }} {{ __('papers') }}</div>
                            <form method="POST" action="{{ route('mock.subscribe') }}" class="mt-3">@csrf
                                <button class="btn-cta-filled" style="border:none; cursor:pointer; padding:.55rem 1.4rem;">{{ __('Subscribe') }}</button>
                            </form>
                            @if($used)<div class="small mt-2" style="color: var(--muted);">{{ __('Papers read via subscription are locked again.') }}</div>@endif
                        @endif
                    </div>
                </div>
            </div>

            {{-- Library --}}
            <h3 style="font-family:'IM Fell English',serif; color: var(--dark);">{{ __('My Library') }}</h3>
            <div style="background:#fff; border-radius: var(--radius-lg); box-shadow: var(--card-ring); margin-bottom: 2rem;">
                @forelse($library as $p)
                    <a href="{{ route('public.articles.show', $p->slug) }}" class="d-flex justify-content-between align-items-center px-3 py-3 text-decoration-none border-bottom" style="color: var(--dark);">
                        <span><i class="bi bi-bag-check" style="color: var(--gold);"></i> <strong>{{ $p->title }}</strong></span>
                        <span class="small" style="color: var(--muted);">{{ __('Owned forever') }}</span>
                    </a>
                @empty
                @endforelse
                @foreach($allowancePapers as $p)
                    <a href="{{ route('public.articles.show', $p->slug) }}" class="d-flex justify-content-between align-items-center px-3 py-3 text-decoration-none border-bottom" style="color: var(--dark);">
                        <span><i class="bi bi-ticket-perforated" style="color: var(--primary);"></i> <strong>{{ $p->title }}</strong></span>
                        @if($subOn)
                            <span class="small" style="color: var(--muted);">{{ __('Via subscription') }}</span>
                        @else
                            <span class="small" style="color:#a33;"><i class="bi bi-lock-fill"></i> {{ __('Locked — subscription ended') }}</span>
                        @endif
                    </a>
                @endforeach
                @if($library->isEmpty() && $allowancePapers->isEmpty())
                    <div class="p-4 text-center" style="color: var(--muted);">{{ __('Nothing here yet. Open a paid article to buy or unlock it.') }}
                        <a href="{{ route('public.articles') }}">{{ __('Browse articles') }}</a></div>
                @endif
            </div>

            {{-- Ledger --}}
            <h3 style="font-family:'IM Fell English',serif; color: var(--dark);">{{ __('Transactions') }}</h3>
            <div style="background:#fff; border-radius: var(--radius-lg); box-shadow: var(--card-ring);">
                @foreach($state['ledger'] as $row)
                    <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom small">
                        <span><i class="bi {{ $typeIcon[$row['type']] ?? 'bi-dot' }}" style="color: var(--primary);"></i> {{ $row['desc'] }}
                            <span style="color: var(--muted);">· {{ $row['at'] }}</span></span>
                        <strong style="color: {{ $row['amount'] < 0 ? '#a33' : '#3a7d44' }};">
                            {{ $row['amount'] == 0 ? __('Free') : ($row['amount'] > 0 ? '+' : '−') . MB::rm(abs($row['amount'])) }}</strong>
                    </div>
                @endforeach
            </div>

            <form method="POST" action="{{ route('mock.reset') }}" class="mt-4 text-end">@csrf
                <button class="btn btn-sm btn-link text-muted">{{ __('Reset demo data') }}</button>
            </form>
        @endif
    </div>

    @include('partials.footer')
@endsection
