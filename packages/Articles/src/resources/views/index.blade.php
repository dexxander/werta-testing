@extends('layouts.app')

@section('content')
    @include('partials.navbar')
    @php
        use Articles\Support\MockBilling as MB;
        $catPositions = [
            'science'       => 'top: 22%; left: 24%;',
            'social'        => 'top: 18%; left: 62%;',
            'education'     => 'top: 46%; left: 84%;',
            'mental-health' => 'top: 80%; left: 70%;',
            'parenting'     => 'top: 82%; left: 32%;',
            'career'        => 'top: 52%; left: 14%;',
        ];
    @endphp

    {{-- Main Articles Container --}}
    <main class="werta-articles-main">

        {{-- ═══════════════════════════════════════════════════
             TOP SECTION: FEATURED & POPULAR
        ═══════════════════════════════════════════════════ --}}
        <div class="articles-top-grid">

            {{-- Left: Featured Article --}}
            @if($featuredArticle)
                <section class="premium-featured-card group">
                    <div class="premium-badge">
                        @if($featuredArticle->access_type === 'paid')
                            <i class="bi bi-star-fill"></i> {{ __('PREMIUM') }}
                        @else
                            {{ __('FREE') }}
                        @endif
                    </div>

                    <div class="premium-image-wrapper">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($featuredArticle->title) }}&size=800&background=C4A840&color=fff" alt="{{ $featuredArticle->title }}">
                    </div>

                    <div class="premium-content">
                        <div class="article-meta">
                            @if($featuredArticle->category_label)
                                <a href="{{ route('public.articles', ['category' => $featuredArticle->category]) }}#all-articles" class="meta-tag text-decoration-none">{{ __($featuredArticle->category_label) }}</a>
                                <span class="meta-dot">•</span>
                            @endif
                            <a href="{{ $featuredArticle->author_url }}" class="meta-read-time text-decoration-none">{{ $featuredArticle->author_name }}</a>
                            @if($featuredArticle->completed_reviews_count > 0)
                                <span class="meta-dot">•</span>
                                <span class="meta-reviewed"><i class="bi bi-patch-check-fill"></i> {{ __('Peer reviewed') }}</span>
                            @endif
                        </div>

                        <h1 class="article-headline group-hover-gold">{{ $featuredArticle->title }}</h1>
                        <p class="article-excerpt">{{ $featuredArticle->abstract }}</p>

                        <div class="d-flex flex-wrap align-items-center gap-3">
                            <a href="{{ route('public.articles.show', ['slug' => $featuredArticle->slug]) }}" class="btn-unlock-article" style="display: inline-block; text-decoration: none; text-align: center;">
                                @if($featuredArticle->access_type === 'paid')
                                    <i class="bi bi-book"></i> {{ __('Read the free preview') }}
                                @else
                                    <i class="bi bi-book"></i> {{ __('Read article') }}
                                @endif
                            </a>
                            @if($featuredArticle->access_type === 'paid')
                                <span class="featured-price"><i class="bi bi-coin"></i> {{ __(':n tokens', ['n' => MB::ARTICLE_TOKENS]) }} · {{ MB::rm(MB::articlePrice()) }}</span>
                            @endif
                        </div>
                    </div>
                </section>
            @else
                <section class="premium-featured-card group" style="display: flex; align-items: center; justify-content: center; min-height: 300px;">
                    <p class="text-sm text-gray-400">{{ __('No featured article yet.') }}</p>
                </section>
            @endif

            {{-- Right: Popular this month --}}
            <aside class="trending-sidebar">
                <h2 class="sidebar-title">
                    <i class="bi bi-graph-up-arrow" style="color: var(--primary);"></i> {{ __('Popular this month') }}
                </h2>

                <div class="trending-comments-list">
                    @forelse($popular as $i => $item)
                        <a href="{{ route('public.articles.show', ['slug' => $item->slug]) }}" class="popular-item group">
                            <span class="popular-rank">{{ $i + 1 }}</span>
                            <span class="popular-body">
                                <span class="popular-title group-hover-gold">{{ $item->title }}</span>
                                <span class="popular-meta">
                                    {{ $item->author_name }}
                                    @if($item->category_label) · {{ __($item->category_label) }} @endif
                                    @if($item->access_type === 'paid') · <i class="bi bi-star-fill" style="color: var(--gold);"></i> @endif
                                </span>
                            </span>
                        </a>
                        @if(!$loop->last)
                            <hr class="comment-divider">
                        @endif
                    @empty
                        <p class="text-sm text-gray-400">{{ __('No articles published yet.') }}</p>
                    @endforelse
                </div>

                <a href="{{ route('mock.wallet') }}" class="sidebar-token-note">
                    <i class="bi bi-coin"></i>
                    <span>{{ __('Every premium paper is :price. Top up 5 and get 1 free.', ['price' => MB::rm(MB::articlePrice())]) }}</span>
                </a>
            </aside>
        </div>

        {{-- ═══════════════════════════════════════════════════
             DISCOVERY MAP (Categories)
        ═══════════════════════════════════════════════════ --}}
        <section class="discovery-map-section">
            <div class="discovery-header">
                <div>
                    <h2 class="section-title">{{ __('Discovery Map') }}</h2>
                    <p class="section-subtitle">{{ __('Explore papers by field. Every category has the same fixed price.') }}</p>
                </div>

                <button id="btn-toggle-map" onclick="toggleDiscoveryMap()" class="btn-interactive-view">
                    <i class="bi bi-diagram-3 mr-1"></i> <span id="map-btn-text">{{ __('Interactive View') }}</span>
                </button>
            </div>

            <div id="map-grid-view" class="discovery-grid">
                @foreach($categories as $cat)
                    <div onclick="showResources('{{ $cat['key'] }}')" class="discovery-node hover-lift">
                        <i class="bi {{ $cat['icon'] }} discovery-icon"></i>
                        <h3>{{ __($cat['label']) }}</h3>
                        <p>{{ $cat['count'] }} {{ __('RESOURCES') }}</p>
                    </div>
                @endforeach
            </div>

            <div id="map-interactive-view" class="interactive-constellation" style="display: none;">
                <svg class="constellation-lines" width="100%" height="100%">
                    @foreach($catPositions as $pos)
                        @php preg_match('/top: ([\d.]+)%; left: ([\d.]+)%/', $pos, $m); @endphp
                        <line x1="50%" y1="50%" x2="{{ $m[2] }}%" y2="{{ $m[1] }}%" class="pulse-line" />
                    @endforeach
                </svg>

                <div class="c-node center-hub">
                    <img src="{{ asset('images/Werta_Logo.png') }}" alt="{{ __('Werta Logo') }}" style="height: 35px; margin-bottom: 4px;" onerror="this.style.display='none'">
                    <span>Werta<br>{{ __('Ecosystem') }}</span>
                </div>

                @foreach($categories as $cat)
                    <div onclick="showResources('{{ $cat['key'] }}')" class="c-node satellite" style="{{ $catPositions[$cat['key']] }}">
                        <i class="bi {{ $cat['icon'] }}"></i>
                        <span>{{ __($cat['label']) }}</span>
                    </div>
                @endforeach
            </div>

            <div id="dynamic-resource-panel" class="resource-panel" style="display: none;">
                <button onclick="closeResources()" class="btn-close-panel" aria-label="{{ __('Close') }}"><i class="bi bi-x-lg"></i></button>

                @foreach($categories as $cat)
                    <div id="list-{{ $cat['key'] }}" class="resource-list" style="display: none;">
                        <h3 class="panel-title"><i class="bi {{ $cat['icon'] }}" style="color: var(--primary);"></i> {{ __($cat['label']) }}</h3>
                        <div class="mini-card-grid">
                            @forelse(($categoryArticles[$cat['key']] ?? collect())->take(4) as $item)
                                <a href="{{ route('public.articles.show', ['slug' => $item->slug]) }}" class="mini-card">
                                    <span class="mini-tag">{{ $item->access_type === 'paid' ? __('PREMIUM') : __('FREE') }}</span>
                                    <h4>{{ $item->title }}</h4>
                                    <span class="mini-meta">{{ __('By') }} {{ $item->author_name }}</span>
                                </a>
                            @empty
                                <p class="text-sm text-gray-400">{{ __('No resources yet.') }}</p>
                            @endforelse
                        </div>
                        @if(($categoryArticles[$cat['key']] ?? collect())->count() > 0)
                            <a href="{{ route('public.articles', ['category' => $cat['key']]) }}#all-articles" class="panel-see-all">{{ __('See all in :category', ['category' => __($cat['label'])]) }} <i class="bi bi-arrow-right"></i></a>
                        @endif
                    </div>
                @endforeach
            </div>
        </section>

        {{-- ═══════════════════════════════════════════════════
             ALL ARTICLES (filterable)
        ═══════════════════════════════════════════════════ --}}
        <section id="all-articles" class="all-articles-section">
            <div class="carousel-header">
                <div>
                    <h2 class="section-title mb-0">{{ __('All Articles') }}</h2>
                    <p class="section-subtitle mb-0">{{ __(':n papers', ['n' => $articles->total()]) }}@if($filters['category']) · {{ __(\Submissions\Models\Submission::categoryLabel($filters['category'])) }}@endif</p>
                </div>
            </div>

            <form method="GET" action="{{ route('public.articles') }}#all-articles" class="article-filter-bar">
                <div class="filter-search">
                    <i class="bi bi-search"></i>
                    <input type="search" name="q" value="{{ $filters['q'] }}" placeholder="{{ __('Search titles, topics or authors') }}" aria-label="{{ __('Search articles') }}">
                </div>
                <select name="access" class="filter-select-sm" aria-label="{{ __('Access') }}" onchange="this.form.submit()">
                    <option value="">{{ __('Free and premium') }}</option>
                    <option value="free" @selected($filters['access'] === 'free')>{{ __('Free only') }}</option>
                    <option value="paid" @selected($filters['access'] === 'paid')>{{ __('Premium only') }}</option>
                </select>
                <select name="sort" class="filter-select-sm" aria-label="{{ __('Sort') }}" onchange="this.form.submit()">
                    <option value="newest">{{ __('Newest') }}</option>
                    <option value="popular" @selected($filters['sort'] === 'popular')>{{ __('Most popular') }}</option>
                </select>
                @if($filters['category'])<input type="hidden" name="category" value="{{ $filters['category'] }}">@endif
                <button type="submit" class="btn-filter-go">{{ __('Search') }}</button>
            </form>

            <div class="category-chips">
                <a href="{{ route('public.articles', array_filter(['q' => $filters['q'], 'access' => $filters['access'], 'sort' => $filters['sort'] === 'popular' ? 'popular' : null])) }}#all-articles"
                   class="category-chip {{ $filters['category'] === '' ? 'is-active' : '' }}">{{ __('All categories') }}</a>
                @foreach($categories as $cat)
                    <a href="{{ route('public.articles', array_filter(['category' => $cat['key'], 'q' => $filters['q'], 'access' => $filters['access'], 'sort' => $filters['sort'] === 'popular' ? 'popular' : null])) }}#all-articles"
                       class="category-chip {{ $filters['category'] === $cat['key'] ? 'is-active' : '' }}">
                        <i class="bi {{ $cat['icon'] }}"></i> {{ __($cat['label']) }} <span class="chip-count">{{ $cat['count'] }}</span>
                    </a>
                @endforeach
            </div>

            @if($articles->count())
                <div class="article-tile-grid">
                    @foreach($articles as $article)
                        @include('articles::partials.article-tile', ['article' => $article, 'bestSellers' => $bestSellers])
                    @endforeach
                </div>
                @if($articles->hasPages())
                    <div class="d-flex justify-content-center mt-4">{{ $articles->links('pagination::bootstrap-5') }}</div>
                @endif
            @else
                <div class="no-results">
                    <i class="bi bi-search"></i>
                    <p>{{ __('No articles match these filters.') }}</p>
                    <a href="{{ route('public.articles') }}#all-articles">{{ __('Clear filters') }}</a>
                </div>
            @endif
        </section>

        {{-- ═══════════════════════════════════════════════════
             MORE TO EXPLORE (Native JS Horizontal Carousel)
        ═══════════════════════════════════════════════════ --}}
        <section class="more-articles-section">

            <div class="carousel-header">
                <h2 class="section-title mb-0">{{ __('More to Explore') }}</h2>
                <div class="carousel-controls">
                    <button onclick="document.getElementById('article-slider').scrollBy({ left: -340, behavior: 'smooth' })" class="carousel-btn" aria-label="{{ __('Scroll Left') }}">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <button onclick="document.getElementById('article-slider').scrollBy({ left: 340, behavior: 'smooth' })" class="carousel-btn" aria-label="{{ __('Scroll Right') }}">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>
            </div>

            <div id="article-slider" class="carousel-container">
                @forelse($moreArticles as $article)
                    <div onclick="window.location.href='{{ route('public.articles.show', ['slug' => $article->slug]) }}'" class="standard-article-card carousel-card group hover-lift">
                        <div class="card-image">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($article->title) }}&size=400&background=C4A840&color=fff" alt="{{ $article->title }}">
                        </div>
                        <div class="card-content">
                            <span class="card-category text-accent">
                                {{ $article->access_type === 'paid' ? __('PREMIUM') : __('FREE') }}@if($article->category_label) · {{ __($article->category_label) }}@endif
                            </span>
                            <h3 class="card-title group-hover-gold">{{ $article->title }}</h3>
                            <span class="card-read-time">{{ __('By') }} {{ $article->author_name }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-400 px-2">{{ __('No articles published yet.') }}</p>
                @endforelse
            </div>
        </section>

        {{-- Reviewer call-out --}}
        <section class="reviewer-band">
            <div>
                <p class="reviewer-band-eyebrow">{{ __('Checked by professionals') }}</p>
                <h2 class="reviewer-band-title">{{ __('Every paper is reviewed by verified experts before it is published.') }}</h2>
                <p class="reviewer-band-text">{{ __('Psychologists, educators and health professionals evaluate accuracy, ethics and whether a paper is ready to be sold.') }}</p>
            </div>
            <a href="{{ route('reviewer.apply') }}" class="btn-unlock-article text-decoration-none">{{ __('Apply to become a reviewer') }}</a>
        </section>

    </main>

    @include('partials.footer')

    {{-- ═══════════════════════════════════════════════════
         SCRIPTS
    ═══════════════════════════════════════════════════ --}}
    <script>
        // Toggles between Grid and Constellation Maps
        function toggleDiscoveryMap() {
            const gridView = document.getElementById('map-grid-view');
            const interactiveView = document.getElementById('map-interactive-view');
            const btnText = document.getElementById('map-btn-text');
            const btnIcon = document.querySelector('#btn-toggle-map i');

            if (gridView.style.display !== 'none') {
                gridView.style.display = 'none';
                interactiveView.style.display = 'block';
                btnText.innerText = WertaI18n.t('Grid View');
                btnIcon.className = 'bi bi-grid mr-1';
            } else {
                gridView.style.display = 'grid';
                interactiveView.style.display = 'none';
                btnText.innerText = WertaI18n.t('Interactive View');
                btnIcon.className = 'bi bi-diagram-3 mr-1';
            }
            // Auto-close resources if map type changes
            closeResources();
        }

        // Shows the specific dynamic resource panel
        function showResources(category) {
            const panel = document.getElementById('dynamic-resource-panel');

            // Hide all lists first
            document.querySelectorAll('.resource-list').forEach(el => el.style.display = 'none');

            // Show the panel and the targeted list
            panel.style.display = 'block';
            document.getElementById('list-' + category).style.display = 'block';

            // Smoothly scroll down so the user sees the newly opened panel
            panel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }

        // Hides the resource panel
        function closeResources() {
            document.getElementById('dynamic-resource-panel').style.display = 'none';
        }
    </script>

    <style>
        .werta-articles-main {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 2rem 80px;
            font-family: 'Lato', sans-serif;
            color: var(--dark);
        }

        .text-accent { color: var(--primary); }
        .text-primary { color: var(--primary); }
        .text-dark { color: var(--dark); }

        .hover-lift { transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .hover-lift:hover { transform: translateY(-4px); box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
        .group-hover-gold { transition: color 0.2s; }
        .group:hover .group-hover-gold { color: var(--primary); }

        /* Typography */
        .section-title {
            font-family: 'IM Fell English', serif;
            font-size: 2.2rem;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }
        .section-subtitle {
            color: var(--muted);
            font-size: var(--text-md);
        }

        /* ── TOP GRID ───────────────────────────────────── */
        .articles-top-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
            margin-bottom: 3rem;
        }
        @media (min-width: 992px) {
            .articles-top-grid { grid-template-columns: 2fr 1fr; }
        }

        /* Featured Card */
        .premium-featured-card {
            background: var(--cream-light);
            box-shadow: var(--card-ring), var(--shadow-md);
            border-radius: 16px;
            overflow: hidden;
            position: relative;
        }
        .premium-badge {
            position: absolute;
            top: 1rem;
            left: 1rem;
            background: var(--gold);
            color: var(--dark);
            padding: 0.3rem 0.8rem;
            border-radius: var(--radius-xl);
            font-size: var(--text-xs);
            font-weight: 700;
            letter-spacing: 1px;
            z-index: 10;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .premium-image-wrapper {
            height: 300px;
            width: 100%;
            overflow: hidden;
        }
        .premium-image-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        .premium-featured-card:hover .premium-image-wrapper img { transform: scale(1.05); }

        .premium-content { padding: 2rem; }
        .article-meta {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.5rem;
            font-size: var(--text-sm);
            font-weight: 700;
            margin-bottom: 1rem;
        }
        .meta-tag { color: var(--primary-dark); letter-spacing: 1px; text-transform: uppercase; }
        .meta-dot { color: #ccc; }
        .meta-read-time { color: var(--muted); }
        .meta-reviewed { color: #3a7d44; }

        .article-headline {
            font-family: 'IM Fell English', serif;
            font-size: 2.5rem;
            line-height: 1.2;
            margin-bottom: 1rem;
        }
        .article-excerpt {
            color: var(--muted);
            font-size: 1.05rem;
            line-height: 1.6;
            margin-bottom: 2rem;
        }
        .btn-unlock-article {
            background: var(--gold);
            color: var(--dark);
            border: none;
            padding: 0.8rem 1.8rem;
            border-radius: var(--radius-md);
            font-size: var(--text-base-lg);
            font-weight: 700;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-unlock-article:hover { background: var(--primary); color: #fff; }
        .featured-price { font-size: var(--text-base); font-weight: 700; color: var(--primary-dark); }
        .featured-price i { color: var(--gold); }

        /* Popular Sidebar */
        .trending-sidebar {
            background: var(--cream);
            border-radius: 16px;
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
        }
        .sidebar-title {
            font-family: 'IM Fell English', serif;
            font-size: 1.5rem;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding-bottom: 1rem;
            margin-bottom: 1.5rem;
        }
        .trending-comments-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        .popular-item { display: flex; gap: .9rem; align-items: flex-start; text-decoration: none; color: var(--dark); }
        .popular-rank { font-family: 'IM Fell English', serif; font-size: 1.6rem; line-height: 1; color: var(--gold); min-width: 1.4rem; }
        .popular-body { display: flex; flex-direction: column; gap: .2rem; }
        .popular-title { font-weight: 700; font-size: var(--text-base); line-height: 1.35; }
        .popular-meta { font-size: var(--text-sm); color: var(--muted); }
        .comment-divider { border: 0; border-top: 1px solid rgba(0,0,0,0.05); margin: 0; }
        .sidebar-token-note { margin-top: auto; padding-top: 1.25rem; display: flex; gap: .6rem; align-items: flex-start; font-size: var(--text-sm); font-weight: 700; color: var(--primary-dark); text-decoration: none; }
        .sidebar-token-note i { color: var(--gold); font-size: 1.1rem; }
        .sidebar-token-note:hover { color: var(--primary); }

        /* ── DISCOVERY MAP ──────────────────────────────── */
        .discovery-map-section {
            background: var(--cream-light);
            border: 1px solid rgba(196, 168, 64, 0.2);
            border-radius: 16px;
            padding: 2.5rem;
            margin-bottom: 4rem;
            position: relative;
        }
        .discovery-header {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-bottom: 2rem;
        }
        @media (min-width: 768px) {
            .discovery-header { flex-direction: row; justify-content: space-between; align-items: flex-end; }
        }
        .btn-interactive-view {
            background: transparent;
            border: 2px solid var(--primary-dark);
            color: var(--primary-dark);
            padding: 0.6rem 1.2rem;
            border-radius: var(--radius-md);
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-interactive-view:hover {
            background: var(--primary-dark);
            color: #fff;
        }
        .discovery-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        @media (min-width: 640px) { .discovery-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (min-width: 992px) { .discovery-grid { grid-template-columns: repeat(3, 1fr); } }

        .discovery-node {
            background: #fff;
            border: 1px solid rgba(0,0,0,0.05);
            padding: 1.5rem;
            border-radius: var(--radius-lg);
            text-align: center;
            cursor: pointer;
        }
        .discovery-icon { font-size: 2rem; color: var(--primary); display: block; margin-bottom: .5rem; }
        .discovery-node h3 {
            font-family: 'IM Fell English', serif;
            font-size: 1.3rem;
            margin-bottom: 0.2rem;
        }
        .discovery-node p {
            font-size: var(--text-xs);
            font-weight: 700;
            color: var(--muted);
            letter-spacing: 1px;
            margin: 0;
        }

        /* ── CONSTELLATION MAP ──────────────────────────── */
        .interactive-constellation {
            position: relative;
            height: 450px;
            background: #fff;
            border-radius: 16px;
            border: 1px solid rgba(0,0,0,0.05);
            overflow: hidden;
            box-shadow: inset 0 0 50px rgba(0,0,0,0.02);
            animation: fadeIn 0.4s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .constellation-lines {
            position: absolute;
            top: 0; left: 0;
            z-index: 1;
            pointer-events: none;
        }
        .pulse-line {
            stroke: var(--gold);
            stroke-width: 2.5;
            stroke-dasharray: 8, 8;
            opacity: 0.35;
            animation: dashPulse 25s linear infinite;
        }
        @keyframes dashPulse { to { stroke-dashoffset: -200; } }

        .c-node {
            position: absolute;
            z-index: 10;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            transform: translate(-50%, -50%);
            background: #fff;
            border-radius: var(--radius-full);
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .center-hub {
            top: 50%; left: 50%;
            width: 140px; height: 140px;
            border: 3px solid var(--primary-dark);
            background: var(--cream-light);
            font-family: 'IM Fell English', serif;
            font-size: 1.1rem;
            color: var(--dark);
            line-height: 1.1;
        }
        .center-hub:hover {
            box-shadow: 0 0 30px rgba(196, 168, 64, 0.35);
            transform: translate(-50%, -50%) scale(1.05);
        }
        .satellite {
            width: 104px; height: 104px;
            border: 2px solid var(--gold);
            font-size: var(--text-xs);
            font-weight: 700;
            color: var(--dark);
            letter-spacing: 0.3px;
            padding: 0 6px;
            line-height: 1.2;
        }
        .satellite i {
            font-size: 1.5rem;
            margin-bottom: 5px;
            color: var(--primary-dark);
        }
        .satellite:hover {
            transform: translate(-50%, -50%) scale(1.15);
            border-color: var(--primary);
            color: var(--primary-dark);
            box-shadow: 0 15px 35px rgba(44, 36, 22, 0.12);
        }
        @media (max-width: 640px) {
            .satellite { width: 78px; height: 78px; font-size: .6rem; }
            .satellite i { font-size: 1.1rem; }
            .center-hub { width: 96px; height: 96px; font-size: var(--text-sm); }
        }

        /* ── IN-PAGE DYNAMIC RESOURCE PANEL ──────────────── */
        .resource-panel {
            margin-top: 2rem;
            background: #fff;
            border-radius: var(--radius-lg);
            padding: 2.5rem;
            position: relative;
            box-shadow: var(--card-ring), 0 20px 40px rgba(196, 168, 64, 0.08);
            animation: fadeIn 0.3s ease-out;
        }
        .btn-close-panel {
            position: absolute;
            top: 1rem;
            right: 1.5rem;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--muted);
            cursor: pointer;
            transition: color 0.2s;
        }
        .btn-close-panel:hover { color: var(--dark); }
        .panel-title {
            font-family: 'IM Fell English', serif;
            font-size: var(--text-3xl);
            color: var(--dark);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .panel-see-all { display: inline-flex; align-items: center; gap: 6px; margin-top: 1.5rem; font-weight: 700; color: var(--primary-dark); text-decoration: none; }
        .panel-see-all:hover { color: var(--primary); }

        /* Mini Cards inside the Panel */
        .mini-card-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
        @media (min-width: 768px) {
            .mini-card-grid { grid-template-columns: repeat(2, 1fr); }
        }
        .mini-card {
            display: block;
            background: var(--cream-light);
            box-shadow: var(--card-ring), var(--shadow-md);
            padding: 1.5rem;
            border-radius: var(--radius-md);
            text-decoration: none;
            transition: all 0.2s;
        }
        .mini-card:hover {
            box-shadow: var(--card-ring-hover), var(--shadow-lg);
            transform: translateY(-2px);
        }
        .mini-tag {
            display: inline-block;
            font-size: var(--text-xs);
            font-weight: 700;
            color: var(--primary-dark);
            letter-spacing: 1px;
            margin-bottom: 0.5rem;
        }
        .mini-card h4 {
            font-family: 'Lato', sans-serif;
            font-size: var(--text-lg);
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }
        .mini-meta {
            font-size: var(--text-sm);
            color: var(--muted);
        }

        /* ── ALL ARTICLES ────────────────────────────────── */
        .all-articles-section { margin-bottom: 4rem; scroll-margin-top: 90px; }
        .article-filter-bar {
            display: flex; flex-wrap: wrap; gap: .75rem; align-items: center;
            background: #fff; border-radius: var(--radius-lg); box-shadow: var(--card-ring), var(--shadow-sm);
            padding: 1rem; margin-bottom: 1rem;
        }
        .filter-search { flex: 1 1 260px; display: flex; align-items: center; gap: .5rem; background: var(--cream-light); border: 1px solid rgba(123,107,53,.25); border-radius: var(--radius-md); padding: 0 .8rem; }
        .filter-search i { color: var(--muted); }
        .filter-search input { border: none; background: transparent; outline: none; width: 100%; padding: .6rem 0; font-size: var(--text-base); color: var(--dark); }
        .filter-select-sm { padding: .6rem .8rem; border-radius: var(--radius-md); border: 1px solid rgba(123,107,53,.25); background: var(--cream-light); color: var(--dark); font-size: var(--text-base); }
        .btn-filter-go { background: var(--primary); color: #fff; border: none; padding: .6rem 1.3rem; border-radius: var(--radius-md); font-weight: 700; font-size: var(--text-base); cursor: pointer; }
        .btn-filter-go:hover { background: var(--primary-dark); }
        .category-chips { display: flex; flex-wrap: wrap; gap: .5rem; margin-bottom: 1.75rem; }
        .category-chip { display: inline-flex; align-items: center; gap: 6px; padding: .45rem .95rem; border-radius: var(--radius-pill); font-size: var(--text-sm); font-weight: 700; color: var(--primary-dark); background: #fff; box-shadow: inset 0 0 0 1px rgba(123,107,53,.3); text-decoration: none; transition: all .2s; }
        .category-chip:hover { box-shadow: inset 0 0 0 1px var(--primary); color: var(--primary); }
        .category-chip.is-active { background: var(--primary-dark); color: #fff; box-shadow: none; }
        .chip-count { opacity: .65; font-weight: 400; }

        .no-results { text-align: center; padding: 3rem 1rem; background: #fff; border-radius: var(--radius-lg); box-shadow: var(--card-ring); color: var(--muted); }
        .no-results i { font-size: 2rem; color: var(--gold); display: block; margin-bottom: .5rem; }
        .no-results a { color: var(--primary-dark); font-weight: 700; }
        .pagination .page-link { color: var(--primary-dark); border-color: rgba(123,107,53,.25); }
        .pagination .page-item.active .page-link { background: var(--primary-dark); border-color: var(--primary-dark); color: #fff; }

        /* ── INTERACTIVE CAROUSEL ────────────────────────────── */
        .carousel-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 1.5rem;
        }
        .carousel-controls {
            display: flex;
            gap: 0.5rem;
        }
        .carousel-btn {
            background: #fff;
            border: 1px solid rgba(0,0,0,0.1);
            width: 40px;
            height: 40px;
            border-radius: var(--radius-full);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            color: var(--dark);
        }
        .carousel-btn:hover {
            border-color: var(--primary);
            color: var(--primary);
            transform: scale(1.05);
        }

        /* The scrolling track */
        .carousel-container {
            display: flex;
            gap: 1.5rem;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            padding-bottom: 1.5rem; /* Space for box-shadow on hover */
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        .carousel-container::-webkit-scrollbar {
            display: none;
        }

        .standard-article-card {
            background: #fff;
            box-shadow: var(--card-ring), var(--shadow-md);
            border-radius: var(--radius-lg);
            overflow: hidden;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .standard-article-card:hover {
            box-shadow: var(--card-ring-hover), var(--shadow-lg);
            transform: translateY(-4px);
        }
        .carousel-card {
            flex: 0 0 300px;
            scroll-snap-align: start;
        }
        .card-image {
            height: 140px;
            width: 100%;
            overflow: hidden;
        }
        .card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s;
        }
        .standard-article-card:hover .card-image img { transform: scale(1.05); }
        .card-content { padding: 1.2rem; }
        .card-category {
            font-size: var(--text-xs);
            font-weight: 700;
            letter-spacing: 1px;
            display: block;
            margin-bottom: 0.5rem;
        }
        .card-title {
            font-family: 'Lato', sans-serif;
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            line-height: 1.3;
        }
        .card-read-time {
            font-size: var(--text-sm);
            color: var(--muted);
        }

        /* ── REVIEWER BAND ─────────────────────────────────── */
        .reviewer-band { margin-top: 3rem; background: var(--dark); color: var(--cream); border-radius: 16px; padding: 2.5rem; display: flex; flex-wrap: wrap; gap: 1.5rem; align-items: center; justify-content: space-between; }
        .reviewer-band > div { max-width: 640px; }
        .reviewer-band-eyebrow { font-size: var(--text-sm); font-weight: 700; letter-spacing: 3px; text-transform: uppercase; color: var(--gold); margin-bottom: .5rem; }
        .reviewer-band-title { font-family: 'IM Fell English', serif; font-size: var(--text-2xl); line-height: 1.25; margin-bottom: .6rem; color: #fff; }
        .reviewer-band-text { color: rgba(245,239,224,.75); margin: 0; }
        @media (max-width: 767px) {
            .werta-articles-main { padding: 28px 1rem 60px; }
            .article-headline { font-size: 2rem; }
            .discovery-map-section { padding: 1.5rem; }
            .resource-panel { padding: 1.5rem; }
        }
    </style>
@endsection
