@extends('layouts.app')

@section('content')
    @include('partials.navbar')
    @include('partials.werta-ui')

    <div class="wt-page wt-page--narrow" style="max-width: 560px;">
        <div class="wt-card text-center" style="padding: 2.5rem 2rem;">
            @if(!$isStaff)
                <i class="bi bi-pencil-square" style="font-size: 2rem; color: var(--gold);"></i>
                <h1 class="wt-title" style="font-size: var(--text-3xl); margin-top: .5rem;">{{ __('Sign in to publish an article') }}</h1>
                <p class="wt-muted mb-4">{{ __('Articles can be submitted by clients, parents and counselors. Sign in with one of these accounts to continue.') }}</p>

                <div class="d-grid gap-2 mb-4">
                    @if(\App\Support\DemoAuth::enabled())
                        <a href="{{ route('demo.index') }}" class="wt-btn wt-btn--dark">{{ __('Choose a demo account') }}</a>
                    @endif
                    <a href="{{ url('/client/login') }}" class="wt-btn wt-btn--gold">{{ __('Sign in as a client') }}</a>
                    <a href="{{ url('/parent/login') }}" class="wt-btn wt-btn--gold">{{ __('Sign in as a parent') }}</a>
                    <a href="{{ url('/counselor/login') }}" class="wt-btn wt-btn--gold">{{ __('Sign in as a counselor') }}</a>
                </div>

                <a href="{{ url('/auth/register') }}" class="wt-small" style="color: var(--primary-dark); font-weight: 700;">{{ __('New to Werta? Create an account') }}</a>
            @else
                <i class="bi bi-shield-lock" style="font-size: 2rem; color: var(--gold);"></i>
                <h1 class="wt-title" style="font-size: var(--text-3xl); margin-top: .5rem;">{{ __('Publishing is not available for staff accounts') }}</h1>
                <p class="wt-muted mb-4">{{ __('Admin and superadmin accounts review articles but cannot publish them. To submit an article, sign out and use a client, parent or counselor account.') }}</p>
                <a href="{{ url('/' . session('staff_role') . '/dashboard') }}" class="wt-btn wt-btn--gold wt-btn--block">{{ __('Back to dashboard') }}</a>
            @endif

            <div class="mt-4 pt-3" style="border-top: 1px solid rgba(123,107,53,.15);">
                <a href="{{ route('public.articles') }}" class="wt-back mb-0"><i class="bi bi-arrow-left"></i> {{ __('Browse articles') }}</a>
            </div>
        </div>
    </div>

    @include('partials.footer')
@endsection
