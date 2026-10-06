@extends('layouts.app')

@section('content')
    @include('partials.navbar')

    <div style="max-width: 600px; margin: 60px auto; padding: 0 1.5rem;">
        <div style="background: #fff; border-radius: var(--radius-xl); box-shadow: var(--card-ring), var(--shadow-lg); padding: 3rem; text-align: center;">
            <i class="bi bi-star-fill" style="font-size: 2.5rem; color: var(--gold);"></i>
            <h1 style="font-family: 'IM Fell English', serif; font-size: 2rem; color: var(--dark); margin: 1rem 0 0.5rem;">{{ __('Unlock “:title”', ['title' => $article->title]) }}</h1>
            <p style="color: var(--muted); margin-bottom: 2rem;">{{ __('This is a paid article by') }} {{ \App\Support\SiteText::translate($article->author_name) }}.</p>

            <div style="font-size: 2.5rem; font-weight: 700; color: var(--primary-dark); margin-bottom: 2rem;">
                RM {{ \App\Support\SiteText::translate(number_format($article->price, 2)) }}
            </div>

            {{-- TODO: wire to real payment gateway once billing is built --}}
            <button class="btn-cta-filled" style="width: 100%; border: none; cursor: pointer;">
                {{ __('Unlock This Article') }}
            </button>

            <a href="{{ route('public.articles.show', ['slug' => $article->slug]) }}" style="display: block; margin-top: 1rem; font-size: 0.85rem; color: var(--muted);">
                {{ __('← Back to article') }}
            </a>
        </div>
    </div>

    @include('partials.footer')
@endsection
