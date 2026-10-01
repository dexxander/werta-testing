@extends('submissions::layout')

@section('title', 'Sign in to publish')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4">
    <div class="w-full max-w-lg bg-white rounded-2xl ring-1 ring-primary-dark/35 shadow-sm p-8 text-center">
        @if(!$isStaff)
            <h1 class="text-2xl font-bold text-dark mb-3">Sign in to publish an article</h1>
            <p class="text-sm text-muted mb-6">Articles can be submitted by clients, parents and counselors. Sign in with one of these accounts to continue.</p>

            <div class="space-y-3 mb-6">
                <a href="{{ url('/client/login') }}" class="block w-full bg-gold text-dark hover:bg-primary hover:text-white font-semibold rounded-lg px-4 py-2.5 transition-colors">
                    Sign in as a client
                </a>
                <a href="{{ url('/parent/login') }}" class="block w-full bg-gold text-dark hover:bg-primary hover:text-white font-semibold rounded-lg px-4 py-2.5 transition-colors">
                    Sign in as a parent
                </a>
                <a href="{{ url('/counselor/login') }}" class="block w-full bg-gold text-dark hover:bg-primary hover:text-white font-semibold rounded-lg px-4 py-2.5 transition-colors">
                    Sign in as a counselor
                </a>
            </div>

            <div class="mb-6">
                <a href="{{ url('/auth/register') }}" class="text-primary underline text-sm">
                    New to Werta? Create an account
                </a>
            </div>
        @else
            <h1 class="text-2xl font-bold text-dark mb-3">Publishing is not available for staff accounts</h1>
            <p class="text-sm text-muted mb-6">Admin and superadmin accounts review articles but cannot publish them. To submit an article, sign out and use a client, parent or counselor account.</p>

            <div class="mb-6">
                <a href="{{ url('/' . session('staff_role') . '/dashboard') }}" class="block w-full bg-gold text-dark hover:bg-primary hover:text-white font-semibold rounded-lg px-4 py-2.5 transition-colors">
                    Back to dashboard
                </a>
            </div>
        @endif

        <div class="border-t border-sand/40 pt-6">
            <a href="{{ route('public.articles') }}" class="text-primary text-sm font-semibold hover:text-primary-dark transition-colors inline-flex items-center gap-1">
                <i class="bi bi-arrow-left"></i> Browse articles
            </a>
        </div>
    </div>
</div>
@endsection
