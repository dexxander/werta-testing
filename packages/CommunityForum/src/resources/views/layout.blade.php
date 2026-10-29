@extends('layouts.app')
@section('content')
@include('partials.navbar')
<style>
    .community { max-width: 1120px; margin: auto; padding: 56px 24px 80px; }
    .community a { color: var(--primary-dark); }
    .community-hero { max-width: 720px; margin-bottom: 32px; }
    .community-eyebrow { text-transform: uppercase; letter-spacing: .14em; font-size: .75rem; font-weight: 700; color: var(--primary); }
    .community h1 { font-family: 'IM Fell English', serif; font-size: clamp(2rem, 5vw, 3.3rem); margin: 12px 0; overflow-wrap: anywhere; }
    .community h2 { font-size: 1.25rem; font-weight: 700; }
    .community-muted { color: var(--muted); }
    .community-grid { display: grid; grid-template-columns: minmax(0, 1fr) 300px; gap: 28px; align-items: start; }
    .community-card { background: var(--cream-light); border: 1px solid var(--sand); border-radius: 16px; padding: 24px; margin-bottom: 18px; }
    .community-topic { display: inline-block; background: #ebe6d4; color: var(--primary-dark); border-radius: 30px; padding: 5px 12px; font-size: .78rem; font-weight: 700; margin-bottom: 12px; }
    .community-meta { font-size: .82rem; color: var(--muted); display: flex; flex-wrap: wrap; gap: 12px; margin: 12px 0; }
    .community-body { white-space: pre-wrap; overflow-wrap: anywhere; line-height: 1.8; }
    .community-reply-context { border-left: 3px solid var(--gold); background: #ebe6d4; padding: 12px 16px; border-radius: 0 8px 8px 0; font-size: .9rem; overflow-wrap: anywhere; }
    .community .form-control, .community .form-select { border-color: var(--sand); background-color: #fff; }
    .community label { font-weight: 700; margin-bottom: 6px; }
    .community-button { display: inline-block; border: 0; border-radius: 8px; padding: 11px 18px; background: var(--primary-dark); color: #fff !important; text-decoration: none; font-weight: 700; }
    .community-button:hover { background: var(--primary); }
    .community-empty { text-align: center; padding: 48px 20px; }
    .community-empty i { font-size: 2rem; color: var(--primary); }
    .community-pagination { display: flex; justify-content: space-between; gap: 16px; margin: 24px 0; }
    .community :focus-visible { outline: 3px solid var(--gold); outline-offset: 3px; }
    @media (max-width: 800px) { .community-grid { grid-template-columns: 1fr; } .community { padding: 32px 16px 48px; } }
</style>
<main class="community">
    @if(session('success')) <div class="alert alert-success" role="status">{{ session('success') }}</div> @endif
    @if($errors->any())
        <div class="alert alert-danger" role="alert"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif
    @yield('community-content')
</main>
@include('partials.footer')
@endsection
