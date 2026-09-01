@extends('layouts.app')

@section('content')
    @include('partials.navbar')

    {{-- Main Articles Container --}}
    <main class="werta-articles-main">
        
        {{-- ═══════════════════════════════════════════════════
             TOP SECTION: PREMIUM FEATURE & TRENDING
        ═══════════════════════════════════════════════════ --}}
        <div class="articles-top-grid">
            
            {{-- Left: Featured Premium Article --}}
            @if($featuredArticle)
                <section class="premium-featured-card group">
                    <div class="premium-badge">
                        <i class="bi bi-star-fill"></i> PREMIUM
                    </div>
                    
                    <div class="premium-image-wrapper">
                        <img src="{{ $featuredArticle->image_url }}" alt="{{ $featuredArticle->title }}">
                    </div>
                    
                    <div class="premium-content">
                        <div class="article-meta">
                            <span class="meta-tag">{{ strtoupper($featuredArticle->category) }}</span>
                            <span class="meta-dot">•</span>
                            <span class="meta-read-time">{{ $featuredArticle->read_time }} MIN READ</span>
                            <span class="meta-lang">
                                <i class="bi bi-translate"></i> {{ $featuredArticle->lang }}
                            </span>
                        </div>
                        
                        <h1 class="article-headline group-hover-gold">{{ $featuredArticle->title }}</h1>
                        <p class="article-excerpt">{{ $featuredArticle->excerpt }}</p>
                        
                        <a href="{{ route('public.articles.show', ['slug' => $featuredArticle->slug]) }}" class="btn-unlock-article" style="display: inline-block; text-decoration: none; text-align: center;">
                            <i class="bi bi-lock-fill"></i> Unlock Full Article
                        </a>
                    </div>
                </section>
            @else
                <section class="premium-featured-card group" style="display: flex; align-items: center; justify-content: center; min-height: 300px;">
                    <p class="text-sm text-gray-400">No featured article yet.</p>
                </section>
            @endif

            {{-- Right: Trending Conversations --}}
            <aside class="trending-sidebar">
                <h2 class="sidebar-title">
                    <i class="bi bi-graph-up-arrow" style="color: var(--gold);"></i> Trending Conversations
                </h2>
                
                <div class="trending-comments-list">
                    @forelse($trendingComments as $comment)
                        <div class="comment-item group">
                            <div class="comment-header">
                                <div class="comment-avatar" style="background: #C4A840; color: #fff;">{{ substr($comment->author_name, 0, 1) }}</div>
                                <span class="comment-author">{{ $comment->author_name }}</span>
                                <span class="comment-time">{{ $comment->time_ago }}</span>
                            </div>
                            <p class="comment-text">{{ $comment->text }}</p>
                            <a href="{{ route('public.articles.show', ['slug' => $comment->article_slug]) }}" class="comment-source group-hover-underline">On: {{ $comment->article_title }}</a>
                        </div>
                        @if(!$loop->last)
                            <hr class="comment-divider">
                        @endif
                    @empty
                        <p class="text-sm text-gray-400">No trending conversations yet.</p>
                    @endforelse
                </div>
            </aside>
        </div>

        {{-- ═══════════════════════════════════════════════════
             DISCOVERY MAP (Grid & Interactive Constellation)
        ═══════════════════════════════════════════════════ --}}
        <section class="discovery-map-section">
            <div class="discovery-header">
                <div>
                    <h2 class="section-title">Discovery Map</h2>
                    <p class="section-subtitle">Explore the intersection of modern technology and mental wellbeing.</p>
                </div>
                
                <button id="btn-toggle-map" onclick="toggleDiscoveryMap()" class="btn-interactive-view">
                    <i class="bi bi-diagram-3 mr-1"></i> <span id="map-btn-text">Interactive View</span>
                </button>
            </div>

            <div id="map-grid-view" class="discovery-grid">
                <div onclick="showResources('ai')" class="discovery-node hover-lift">
                    <i class="bi bi-cpu text-gold text-3xl mb-2 block"></i>
                    <h3>AI Guided</h3>
                    <p>{{ $categories['ai']['count'] ?? 0 }} RESOURCES</p>
                </div>
                <div onclick="showResources('iot')" class="discovery-node hover-lift">
                    <i class="bi bi-smartwatch text-primary text-3xl mb-2 block"></i>
                    <h3>IoT Wellness</h3>
                    <p>{{ $categories['iot']['count'] ?? 0 }} RESOURCES</p>
                </div>
                <div onclick="showResources('ar')" class="discovery-node hover-lift">
                    <i class="bi bi-headset-vr text-dark text-3xl mb-2 block"></i>
                    <h3>AR Therapy</h3>
                    <p>{{ $categories['ar']['count'] ?? 0 }} RESOURCES</p>
                </div>
                <div onclick="showResources('multilingual')" class="discovery-node hover-lift">
                    <i class="bi bi-globe2 text-gold text-3xl mb-2 block"></i>
                    <h3>Multilingual</h3>
                    <p>{{ $categories['multilingual']['count'] ?? 0 }} RESOURCES</p>
                </div>
            </div>

            <div id="map-interactive-view" class="interactive-constellation" style="display: none;">
                
                <svg class="constellation-lines" width="100%" height="100%">
                    <line x1="50%" y1="50%" x2="25%" y2="25%" class="pulse-line" />
                    <line x1="50%" y1="50%" x2="75%" y2="30%" class="pulse-line" />
                    <line x1="50%" y1="50%" x2="30%" y2="75%" class="pulse-line" />
                    <line x1="50%" y1="50%" x2="70%" y2="70%" class="pulse-line" />
                </svg>

                <div class="c-node center-hub">
                    <img src="{{ asset('images/Werta_Logo.png') }}" alt="Werta Logo" style="height: 35px; margin-bottom: 4px;" onerror="this.style.display='none'">
                    <span>Werta<br>Ecosystem</span>
                </div>

                <div onclick="showResources('ai')" class="c-node satellite ai-node">
                    <i class="bi bi-cpu"></i>
                    <span>AI Guided</span>
                </div>
                <div onclick="showResources('iot')" class="c-node satellite iot-node">
                    <i class="bi bi-smartwatch"></i>
                    <span>IoT Wellness</span>
                </div>
                <div onclick="showResources('ar')" class="c-node satellite ar-node">
                    <i class="bi bi-headset-vr"></i>
                    <span>AR Therapy</span>
                </div>
                <div onclick="showResources('multilingual')" class="c-node satellite lang-node">
                    <i class="bi bi-globe2"></i>
                    <span>Multilingual</span>
                </div>
            </div>

            <div id="dynamic-resource-panel" class="resource-panel" style="display: none;">
                
                <button onclick="closeResources()" class="btn-close-panel"><i class="bi bi-x-lg"></i></button>

                <div id="list-ai" class="mini-card-grid resource-list">
                    @forelse(($categoryArticles['ai'] ?? []) as $item)
                        <a href="{{ route('public.articles.show', ['slug' => $item->slug]) }}" class="mini-card">
                            <span class="mini-tag">{{ strtoupper($item->type) }}</span>
                            <h4>{{ $item->title }}</h4>
                            <span class="mini-meta">{{ $item->read_time }} MIN READ</span>
                        </a>
                    @empty
                        <p class="text-sm text-gray-400">No resources yet.</p>
                    @endforelse
                </div>

                <div id="list-iot" class="mini-card-grid resource-list" style="display: none;">
                    @forelse(($categoryArticles['iot'] ?? []) as $item)
                        <a href="{{ route('public.articles.show', ['slug' => $item->slug]) }}" class="mini-card">
                            <span class="mini-tag">{{ strtoupper($item->type) }}</span>
                            <h4>{{ $item->title }}</h4>
                            <span class="mini-meta">{{ $item->read_time }} MIN READ</span>
                        </a>
                    @empty
                        <p class="text-sm text-gray-400">No resources yet.</p>
                    @endforelse
                </div>

                <div id="list-ar" class="mini-card-grid resource-list" style="display: none;">
                    @forelse(($categoryArticles['ar'] ?? []) as $item)
                        <a href="{{ route('public.articles.show', ['slug' => $item->slug]) }}" class="mini-card">
                            <span class="mini-tag">{{ strtoupper($item->type) }}</span>
                            <h4>{{ $item->title }}</h4>
                            <span class="mini-meta">{{ $item->read_time }} MIN READ</span>
                        </a>
                    @empty
                        <p class="text-sm text-gray-400">No resources yet.</p>
                    @endforelse
                </div>

                <div id="list-multilingual" class="mini-card-grid resource-list" style="display: none;">
                    @forelse(($categoryArticles['multilingual'] ?? []) as $item)
                        <a href="{{ route('public.articles.show', ['slug' => $item->slug]) }}" class="mini-card">
                            <span class="mini-tag">{{ strtoupper($item->type) }}</span>
                            <h4>{{ $item->title }}</h4>
                            <span class="mini-meta">{{ $item->read_time }} MIN READ</span>
                        </a>
                    @empty
                        <p class="text-sm text-gray-400">No resources yet.</p>
                    @endforelse
                </div>
                </div>
            </div>

        </section>

        {{-- ═══════════════════════════════════════════════════
             MORE TO EXPLORE (Native JS Horizontal Carousel)
        ═══════════════════════════════════════════════════ --}}
        <section class="more-articles-section">
            
            <div class="carousel-header">
                <h2 class="section-title mb-0">More to Explore</h2>
                <div class="carousel-controls">
                    <button onclick="document.getElementById('article-slider').scrollBy({ left: -340, behavior: 'smooth' })" class="carousel-btn" aria-label="Scroll Left">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <button onclick="document.getElementById('article-slider').scrollBy({ left: 340, behavior: 'smooth' })" class="carousel-btn" aria-label="Scroll Right">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>
            </div>
            
            <div id="article-slider" class="carousel-container">
                @forelse($moreArticles as $article)
                    <div onclick="window.location.href='{{ route('public.articles.show', ['slug' => $article->slug]) }}'" class="standard-article-card carousel-card group hover-lift">
                        <div class="card-image">
                            <img src="{{ $article->image_url }}" alt="{{ $article->category }}">
                        </div>
                        <div class="card-content">
                            <span class="card-category text-gold">{{ strtoupper($article->category) }}</span>
                            <h3 class="card-title group-hover-gold">{{ $article->title }}</h3>
                            <span class="card-read-time">{{ $article->read_time }} MIN READ • <i class="bi bi-translate"></i> {{ $article->lang }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-400 px-2">No articles published yet.</p>
                @endforelse
            </div>
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
                btnText.innerText = 'Grid View';
                btnIcon.className = 'bi bi-grid mr-1';
            } else {
                gridView.style.display = 'grid';
                interactiveView.style.display = 'none';
                btnText.innerText = 'Interactive View';
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

    {{-- ═══════════════════════════════════════════════════
         STYLES
    ═══════════════════════════════════════════════════ --}}
    <style>
        :root {
            --primary: #7B6B35;
            --primary-dark: #2C2416;
            --gold: #C4A840;
            --cream: #F5EFE0;
            --cream-light: #FDFAF4;
            --dark: #2C2416;
            --muted: #6B7280;
        }

        .werta-articles-main {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 2rem 80px;
            font-family: 'Lato', sans-serif;
            color: var(--dark);
        }

        .text-gold { color: var(--gold); }
        .text-primary { color: var(--primary); }
        .text-dark { color: var(--dark); }
        
        .hover-lift { transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .hover-lift:hover { transform: translateY(-4px); box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
        .group-hover-gold { transition: color 0.2s; }
        .group:hover .group-hover-gold { color: var(--gold); }
        .group-hover-underline { transition: color 0.2s; }
        .group:hover .group-hover-underline { text-decoration: underline; color: var(--gold); }

        /* Typography */
        .section-title {
            font-family: 'IM Fell English', serif;
            font-size: 2.2rem;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }
        .section-subtitle {
            color: var(--muted);
            font-size: 1rem;
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

        /* Premium Card */
        .premium-featured-card {
            background: var(--cream-light);
            border: 1px solid rgba(196, 168, 64, 0.2);
            border-radius: 16px;
            overflow: hidden;
            position: relative;
        }
        .premium-badge {
            position: absolute;
            top: 1rem;
            left: 1rem;
            background: var(--gold);
            color: #fff;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.7rem;
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
            align-items: center;
            gap: 0.5rem;
            font-size: 0.75rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        .meta-tag { color: var(--primary-dark); letter-spacing: 1px; }
        .meta-dot { color: #ccc; }
        .meta-read-time { color: var(--muted); }
        .meta-lang { background: var(--cream); padding: 2px 6px; border-radius: 4px; color: var(--muted); }
        
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
            color: #fff;
            border: none;
            padding: 0.8rem 1.8rem;
            border-radius: 8px;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-unlock-article:hover { background: #9E8630; }

        /* Trending Sidebar */
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
            gap: 1.5rem;
        }
        .comment-header {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            margin-bottom: 0.5rem;
        }
        .comment-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 0.8rem;
        }
        .comment-author { font-weight: 700; font-size: 0.9rem; }
        .comment-time { font-size: 0.7rem; color: var(--muted); font-weight: 700; }
        .comment-text {
            font-size: 0.9rem;
            color: var(--muted);
            line-height: 1.5;
            margin-bottom: 0.5rem;
            font-style: italic;
        }
        .comment-source {
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--primary-dark);
            text-decoration: none;
        }
        .comment-divider { border: 0; border-top: 1px solid rgba(0,0,0,0.05); margin: 0; }

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
            border-radius: 8px;
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
        @media (min-width: 992px) { .discovery-grid { grid-template-columns: repeat(4, 1fr); } }
        
        .discovery-node {
            background: #fff;
            border: 1px solid rgba(0,0,0,0.05);
            padding: 1.5rem;
            border-radius: 12px;
            text-align: center;
            cursor: pointer;
        }
        .discovery-node h3 {
            font-family: 'IM Fell English', serif;
            font-size: 1.3rem;
            margin-bottom: 0.2rem;
        }
        .discovery-node p {
            font-size: 0.7rem;
            font-weight: 700;
            color: var(--muted);
            letter-spacing: 1px;
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
            border-radius: 50%;
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
            box-shadow: 0 0 30px rgba(58, 172, 184, 0.3);
            transform: translate(-50%, -50%) scale(1.05);
        }
        .satellite {
            width: 100px; height: 100px;
            border: 2px solid var(--gold);
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--dark);
            letter-spacing: 0.5px;
        }
        .satellite i {
            font-size: 1.6rem;
            margin-bottom: 5px;
            color: var(--primary-dark);
        }
        .satellite:hover {
            transform: translate(-50%, -50%) scale(1.15);
            border-color: var(--primary);
            color: var(--primary-dark);
            box-shadow: 0 15px 35px rgba(58, 172, 184, 0.2);
        }

        .ai-node { top: 25%; left: 25%; }
        .iot-node { top: 30%; left: 75%; }
        .ar-node { top: 75%; left: 30%; }
        .lang-node { top: 70%; left: 70%; }
        
        @media (max-width: 640px) {
            .ai-node { top: 15%; left: 25%; }
            .iot-node { top: 20%; left: 80%; }
            .ar-node { top: 85%; left: 25%; }
            .lang-node { top: 80%; left: 80%; }
            .satellite { width: 85px; height: 85px; font-size: 0.65rem; }
            .center-hub { width: 110px; height: 110px; font-size: 0.9rem; }
        }

        /* ── IN-PAGE DYNAMIC RESOURCE PANEL ──────────────── */
        .resource-panel {
            margin-top: 2rem;
            background: #fff;
            border: 1px solid var(--gold);
            border-radius: 12px;
            padding: 2.5rem;
            position: relative;
            box-shadow: 0 20px 40px rgba(196, 168, 64, 0.08);
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
            font-size: 2rem;
            color: var(--dark);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .panel-desc {
            color: var(--muted);
            font-size: 1.05rem;
            margin-bottom: 2rem;
        }
        
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
            border: 1px solid rgba(0,0,0,0.05);
            padding: 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.2s;
        }
        .mini-card:hover {
            border-color: var(--primary);
            box-shadow: 0 5px 15px rgba(58, 172, 184, 0.1);
            transform: translateY(-2px);
        }
        .mini-tag {
            display: inline-block;
            font-size: 0.7rem;
            font-weight: 700;
            color: var(--primary-dark);
            letter-spacing: 1px;
            margin-bottom: 0.5rem;
        }
        .mini-card h4 {
            font-family: 'Lato', sans-serif;
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }
        .mini-meta {
            font-size: 0.75rem;
            color: var(--muted);
        }


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
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            color: var(--dark);
        }
        .carousel-btn:hover {
            border-color: var(--gold);
            color: var(--gold);
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
            border: 1px solid rgba(0,0,0,0.05);
            border-radius: 12px;
            overflow: hidden;
            cursor: pointer;
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
            font-size: 0.7rem;
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
            font-size: 0.75rem;
            color: var(--muted);
        }
    </style>
@endsection
