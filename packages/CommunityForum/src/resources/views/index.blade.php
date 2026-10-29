@extends('community::layout')
@section('community-content')
<header class="community-hero">
    <span class="community-eyebrow">{{ __('community::community.eyebrow') }}</span>
    <h1>{{ __('community::community.heading') }}</h1>
    <p class="community-muted">{{ __('community::community.intro') }}</p>
    <a class="community-button" href="#new-discussion"><i class="bi bi-plus-lg" aria-hidden="true"></i> {{ __('community::community.start') }}</a>
</header>
<div class="community-grid">
    <section aria-label="{{ __('community::community.discussions') }}">
        <form method="get" action="{{ route('community.index') }}" class="community-card">
            <div class="row g-3">
                <div class="col-sm-7">
                    <label for="search">{{ __('community::community.search') }}</label>
                    <input id="search" type="search" name="q" maxlength="100" value="{{ $filters['q'] ?? '' }}" class="form-control">
                </div>
                <div class="col-sm-5">
                    <label for="topic-filter">{{ __('community::community.topic') }}</label>
                    <select id="topic-filter" name="topic" class="form-select">
                        <option value="">{{ __('community::community.all_topics') }}</option>
                        @foreach($topics as $topic)
                            <option value="{{ $topic }}" @selected(($filters['topic'] ?? '') === $topic)>{{ __('community::community.topics.'.$topic) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mt-3 d-flex align-items-center gap-3">
                <button class="community-button">{{ __('community::community.filter') }}</button>
                @if(!empty($filters['q']) || !empty($filters['topic']))<a href="{{ route('community.index') }}">{{ __('community::community.clear') }}</a>@endif
            </div>
        </form>
        <h2 class="mb-3">{{ __('community::community.discussions') }} <span class="community-muted">({{ $posts->total() }})</span></h2>
        @forelse($posts as $post)
            <article class="community-card">
                <span class="community-topic">{{ __('community::community.topics.'.$post->topic) }}</span>
                <h2><a href="{{ route('community.show', $post) }}">{{ $post->title }}</a></h2>
                <p class="community-body mb-0">{{ Illuminate\Support\Str::limit($post->body, 180) }}</p>
                <div class="community-meta">
                    <span>{{ $post->author_name }}</span>
                    <time datetime="{{ $post->created_at->toIso8601String() }}">{{ $post->created_at->diffForHumans() }}</time>
                    <span><i class="bi bi-chat-left-text" aria-hidden="true"></i> {{ __('community::community.reply_count', ['count' => $post->replies_count]) }}</span>
                </div>
            </article>
        @empty
            <div class="community-card community-empty">
                <i class="bi bi-chat-square-heart" aria-hidden="true"></i>
                <h2 class="mt-3">{{ __('community::community.empty') }}</h2>
                <p class="community-muted">{{ __('community::community.empty_hint') }}</p>
            </div>
        @endforelse
        @include('community::pagination', ['paginator' => $posts])
        <section id="new-discussion" class="community-card" style="scroll-margin-top:100px">
            <h2>{{ __('community::community.start') }}</h2>
            @if($author !== null)
                <p class="community-muted">{{ __('community::community.posting_as', ['name' => $author]) }}</p>
                <form method="post" action="{{ route('community.store') }}">
                    @csrf
                    <div class="mb-3"><label for="new-topic">{{ __('community::community.topic') }}</label>
                        <select id="new-topic" name="topic" class="form-select" required>
                            @foreach($topics as $topic)<option value="{{ $topic }}" @selected(old('topic') === $topic)>{{ __('community::community.topics.'.$topic) }}</option>@endforeach
                        </select>
                    </div>
                    <div class="mb-3"><label for="title">{{ __('community::community.title') }}</label><input id="title" name="title" class="form-control" maxlength="180" required value="{{ old('title') }}"></div>
                    <div class="mb-3"><label for="body">{{ __('community::community.message') }}</label><textarea id="body" name="body" class="form-control" rows="6" maxlength="10000" required>{{ old('body') }}</textarea></div>
                    <button class="community-button">{{ __('community::community.publish') }}</button>
                </form>
            @else
                @include('community::sign-in')
            @endif
        </section>
    </section>
    <aside class="community-card">
        <span class="community-topic"><i class="bi bi-heart" aria-hidden="true"></i> {{ __('community::community.kindness') }}</span>
        <h2>{{ __('community::community.guidelines') }}</h2>
        <p class="community-muted">{{ __('community::community.guidelines_text') }}</p>
        <hr>
        <p class="community-muted mb-0">{{ __('community::community.public_notice') }}</p>
    </aside>
</div>
@endsection
