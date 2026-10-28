{{-- One article in a grid. Expects $article (Submission); optional $bestSellers (array of slugs).
     Wrap tiles in <div class="article-tile-grid">. --}}
@once
<style>
        .article-tile { transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .article-tile:hover { transform: translateY(-4px); }
        .article-tile-grid { display: grid; grid-template-columns: 1fr; gap: 1.5rem; }
        @media (min-width: 640px) { .article-tile-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (min-width: 992px) { .article-tile-grid { grid-template-columns: repeat(3, 1fr); } }
        .article-tile { display: flex; flex-direction: column; background: #fff; border-radius: var(--radius-lg); box-shadow: var(--card-ring), var(--shadow-md); overflow: hidden; text-decoration: none; color: var(--dark); }
        .article-tile:hover { box-shadow: var(--card-ring-hover), var(--shadow-lg); color: var(--dark); }
        .article-tile-image { position: relative; height: 150px; overflow: hidden; }
        .article-tile-image img { width: 100%; height: 100%; object-fit: cover; transition: transform .3s; }
        .article-tile:hover .article-tile-image img { transform: scale(1.05); }
        .article-tile-flags { position: absolute; top: .75rem; left: .75rem; display: flex; gap: .4rem; flex-wrap: wrap; }
        .tile-flag { background: rgba(255,255,255,.92); color: var(--dark); font-size: var(--text-xs); font-weight: 700; letter-spacing: .8px; padding: .2rem .6rem; border-radius: var(--radius-pill); }
        .tile-flag--premium { background: var(--gold); }
        .tile-flag--best { background: var(--dark); color: var(--gold); }
        .article-tile-body { padding: 1.2rem; display: flex; flex-direction: column; gap: .45rem; flex: 1; }
        .tile-category { font-size: var(--text-xs); font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: var(--primary); }
        .tile-title { font-family: 'Lato', sans-serif; font-size: 1.1rem; font-weight: 700; line-height: 1.3; margin: 0; }
        .tile-excerpt { font-size: var(--text-base); color: var(--muted); line-height: 1.5; margin: 0; }
        .tile-meta { display: flex; flex-wrap: wrap; gap: .4rem .8rem; font-size: var(--text-sm); color: var(--muted); margin-top: auto; padding-top: .4rem; }
        .tile-reviewed { color: #3a7d44; font-weight: 700; }
        .tile-price { font-size: var(--text-sm); font-weight: 700; color: var(--primary-dark); }
        .tile-price i { color: var(--gold); }
</style>
@endonce
@php
    $isBest = in_array($article->slug, $bestSellers ?? [], true);
    $isReviewed = ($article->completed_reviews_count ?? 0) > 0;
@endphp
<a href="{{ route('public.articles.show', ['slug' => $article->slug]) }}" class="article-tile hover-lift">
    <div class="article-tile-image">
        <img src="https://ui-avatars.com/api/?name={{ urlencode($article->title) }}&size=400&background=C4A840&color=fff" alt="{{ $article->title }}">
        <div class="article-tile-flags">
            @if($article->access_type === 'paid')
                <span class="tile-flag tile-flag--premium"><i class="bi bi-star-fill"></i> {{ __('PREMIUM') }}</span>
            @else
                <span class="tile-flag">{{ __('FREE') }}</span>
            @endif
            @if($isBest)
                <span class="tile-flag tile-flag--best"><i class="bi bi-fire"></i> {{ __('Best seller') }}</span>
            @endif
        </div>
    </div>
    <div class="article-tile-body">
        @if($article->category_label)
            <span class="tile-category"><i class="bi {{ $article->category_icon }}"></i> {{ __($article->category_label) }}</span>
        @endif
        <h3 class="tile-title">{{ $article->title }}</h3>
        @if($article->abstract)
            <p class="tile-excerpt">{{ \Illuminate\Support\Str::limit($article->abstract, 110) }}</p>
        @endif
        <div class="tile-meta">
            <span>{{ __('By') }} {{ $article->author_name }}</span>
            @if($isReviewed)
                <span class="tile-reviewed" title="{{ __('Peer reviewed') }}"><i class="bi bi-patch-check-fill"></i> {{ __('Peer reviewed') }}</span>
            @endif
        </div>
        @if($article->access_type === 'paid')
            <div class="tile-price"><i class="bi bi-coin"></i> {{ __(':n tokens', ['n' => \Articles\Support\MockBilling::ARTICLE_TOKENS]) }} · {{ \Articles\Support\MockBilling::rm(\Articles\Support\MockBilling::articlePrice()) }}</div>
        @endif
    </div>
</a>
