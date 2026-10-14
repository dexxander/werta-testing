@extends('layouts.app')

@section('content')
    @include('partials.navbar')
    @php
        use Articles\Support\MockBilling as MB;
        $price   = MB::sen($article->price);
        $balance = $state['wallet'];
        $subOn   = $state['sub']['active'];
        $left    = MB::SUB_CAP - count($state['sub']['used']);
        $here    = '/articles/' . $article->slug . '/subscribe';
    @endphp

    <div style="max-width: 860px; margin: 50px auto; padding: 0 1.5rem;">
        <div style="font-size: var(--text-xs); letter-spacing: 1px; font-weight: 700; color: var(--muted); text-align:center; margin-bottom:.5rem;">
            <span style="background: var(--cream); border:1px solid var(--gold); border-radius: var(--radius-pill); padding:.15rem .7rem;">{{ __('DEMO — no real payment') }}</span>
        </div>
        <h1 style="font-family: 'IM Fell English', serif; font-size: 2.1rem; color: var(--dark); text-align:center;">{{ __('Unlock “:title”', ['title' => $article->title]) }}</h1>
        <p style="color: var(--muted); text-align:center; margin-bottom: 1.5rem;">{{ __('A paid paper by') }} {{ $article->author_name }}</p>

        @if(session('mock_ok'))<div class="alert alert-success py-2 small">{{ __(session('mock_ok')) }}</div>@endif
        @if(session('mock_error'))<div class="alert alert-danger py-2 small">{{ __(session('mock_error')) }}</div>@endif

        @if(!$viewer)
            <div style="background:#fff; border-radius: var(--radius-xl); box-shadow: var(--card-ring), var(--shadow-lg); padding: 2.5rem; text-align:center;">
                <i class="bi bi-person-lock" style="font-size:2rem; color: var(--gold);"></i>
                <h3 style="margin:.6rem 0;">{{ __('Sign in to continue') }}</h3>
                <p style="color: var(--muted);">{{ __('You need an account to buy or subscribe.') }}</p>
                <a href="{{ url('/client/login') }}" class="btn-cta-filled" style="text-decoration:none; display:inline-block;">{{ __('Sign in as client') }}</a>
                <a href="{{ url('/parent/login') }}" style="margin-left:.8rem; color: var(--primary-dark); font-weight:700;">{{ __('Parent') }}</a>
                <a href="{{ route('counselor.login') }}" style="margin-left:.8rem; color: var(--primary-dark); font-weight:700;">{{ __('Counselor') }}</a>
            </div>
        @else
            <div style="text-align:center; margin-bottom:1.2rem; color: var(--primary-dark); font-weight:700;">
                <i class="bi bi-wallet2"></i> {{ __('Wallet balance') }}: {{ MB::rm($balance) }}
                <a href="{{ route('mock.wallet') }}" style="font-weight:400; margin-left:.5rem; color: var(--muted);">{{ __('Open wallet') }}</a>
            </div>

            <div class="row g-4">
                {{-- Option A: buy once --}}
                <div class="col-md-6">
                    <div style="background:#fff; border-radius: var(--radius-xl); box-shadow: var(--card-ring), var(--shadow-md); padding: 2rem; height:100%;">
                        <div style="font-size: var(--text-xs); letter-spacing:1px; font-weight:700; color: var(--muted);">{{ __('OPTION 1 · BUY ONCE') }}</div>
                        <div style="font-size: 2.3rem; font-weight:700; color: var(--primary-dark);">{{ MB::rm($price) }}</div>
                        <ul style="color: var(--muted); font-size: var(--text-base); padding-left:1.1rem;">
                            <li>{{ __('Yours forever, even if you cancel') }}</li>
                            <li>{{ __('60% goes to the author') }}</li>
                        </ul>
                        @if($balance >= $price)
                            <form method="POST" action="{{ route('mock.buy', $article->slug) }}">@csrf
                                <button class="btn-cta-filled" style="width:100%; border:none; cursor:pointer;">{{ __('Buy for') }} {{ MB::rm($price) }}</button>
                            </form>
                        @else
                            <div class="alert alert-warning py-2 small">{{ __('Short by') }} {{ MB::rm($price - $balance) }}. {{ __('Top up first.') }}</div>
                        @endif
                    </div>
                </div>

                {{-- Option B: subscription --}}
                <div class="col-md-6">
                    <div style="background:#fff; border-radius: var(--radius-xl); box-shadow: var(--card-ring), var(--shadow-md); padding: 2rem; height:100%;">
                        <div style="font-size: var(--text-xs); letter-spacing:1px; font-weight:700; color: var(--muted);">{{ __('OPTION 2 · SUBSCRIBE') }}</div>
                        <div style="font-size: 2.3rem; font-weight:700; color: var(--primary-dark);">{{ MB::rm(MB::SUB_PRICE) }}<span style="font-size:1rem; font-weight:400;"> / {{ __('month') }}</span></div>
                        <ul style="color: var(--muted); font-size: var(--text-base); padding-left:1.1rem;">
                            <li>{{ __(':n papers per month', ['n' => MB::SUB_CAP]) }}</li>
                            <li>{{ __('Access ends when the subscription ends') }}</li>
                            <li>{{ __('Subscription reads do not pay the author') }}</li>
                        </ul>
                        @if($subOn)
                            <div style="font-size: var(--text-base); margin-bottom:.6rem;">{{ __('Allowance left') }}: <strong>{{ $left }}/{{ MB::SUB_CAP }}</strong></div>
                            @if($left > 0)
                                <form method="POST" action="{{ route('mock.allowance', $article->slug) }}">@csrf
                                    <button class="btn-cta-filled" style="width:100%; border:none; cursor:pointer;">{{ __('Use 1 allowance to read') }}</button>
                                </form>
                            @else
                                <div class="alert alert-warning py-2 small">{{ __('Allowance used up this month.') }}</div>
                            @endif
                        @elseif($balance >= MB::SUB_PRICE)
                            <form method="POST" action="{{ route('mock.subscribe') }}">@csrf
                                <input type="hidden" name="return" value="{{ $here }}">
                                <button class="btn-cta-filled" style="width:100%; border:none; cursor:pointer;">{{ __('Subscribe for') }} {{ MB::rm(MB::SUB_PRICE) }}</button>
                            </form>
                        @else
                            <div class="alert alert-warning py-2 small">{{ __('Short by') }} {{ MB::rm(MB::SUB_PRICE - $balance) }}. {{ __('Top up first.') }}</div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Top-up --}}
            <div style="background: var(--cream); border-radius: var(--radius-lg); padding: 1rem 1.3rem; margin-top: 1.5rem; display:flex; flex-wrap:wrap; gap:.6rem; align-items:center; justify-content:center;">
                <span style="font-weight:700; color: var(--primary-dark);"><i class="bi bi-plus-circle"></i> {{ __('Top up wallet') }}:</span>
                @foreach([10, 20, 50] as $amt)
                    <form method="POST" action="{{ route('mock.topup') }}" class="m-0">@csrf
                        <input type="hidden" name="amount" value="{{ $amt }}">
                        <input type="hidden" name="return" value="{{ $here }}">
                        <button class="btn btn-sm" style="border:1px solid var(--gold); background:#fff; color: var(--primary-dark); font-weight:700;">RM {{ $amt }}</button>
                    </form>
                @endforeach
            </div>
        @endif

        <a href="{{ route('public.articles.show', ['slug' => $article->slug]) }}" style="display:block; text-align:center; margin-top:1.5rem; font-size: var(--text-sm); color: var(--muted);">{{ __('← Back to article') }}</a>
    </div>

    @include('partials.footer')
@endsection
