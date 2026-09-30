@extends('layouts.app')

@section('content')
    @include('partials.navbar')

    <div style="max-width: 600px; margin: 60px auto; padding: 0 1.5rem;">
        <div style="background: #fff; border-radius: var(--radius-xl); box-shadow: var(--card-ring), var(--shadow-lg); padding: 3rem; text-align: center;">
            <i class="bi bi-star-fill" style="font-size: 2.5rem; color: var(--gold);"></i>
            <h1 style="font-family: 'IM Fell English', serif; font-size: 2rem; color: var(--dark); margin: 1rem 0 0.5rem;">Unlock "{{ $article->title }}"</h1>
            <p style="color: var(--muted); margin-bottom: 2rem;">This is a paid article by {{ $article->author_name }}.</p>

            <div style="font-size: 2.5rem; font-weight: 700; color: var(--primary-dark); margin-bottom: 2rem;">
                RM {{ number_format($article->price, 2) }}
            </div>

            {{-- TODO: wire to real payment gateway once billing is built --}}
            <button class="btn-cta-filled" style="width: 100%; border: none; cursor: pointer;">
                Unlock This Article
            </button>

            <a href="{{ route('public.articles.show', ['slug' => $article->slug]) }}" style="display: block; margin-top: 1rem; font-size: 0.85rem; color: var(--muted);">
                ← Back to article
            </a>
        </div>
    </div>

    @include('partials.footer')
@endsection