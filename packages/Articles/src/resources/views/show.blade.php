@extends('layouts.app')

@section('content')
    @include('partials.navbar')

    {{-- Main Reading Container --}}
    <main class="article-reader-main">
        
        {{-- Back Button --}}
        <div class="mb-8">
            <a href="{{ route('public.articles') }}" class="back-link">
                <i class="bi bi-arrow-left"></i> Back to Discovery Map
            </a>
        </div>

        {{-- ═══════════════════════════════════════════════════
             ARTICLE HEADER
        ═══════════════════════════════════════════════════ --}}
        <header class="article-header">
            <div class="article-meta">
                <span class="meta-tag">{{ strtoupper($article->category ?? '') }}</span>
                <span class="meta-dot">•</span>
                <span class="meta-read-time">{{ $article->read_time ?? '—' }} MIN READ</span>
            </div>
            
            <h1 class="article-title">{{ $article->title ?? 'Article not found' }}</h1>
            <p class="article-subtitle">{{ $article->subtitle ?? '' }}</p>
            
            <div class="article-author-row">
                <div class="author-info">
                    <img src="{{ $article->author_avatar_url ?? 'https://ui-avatars.com/api/?name=Author&background=C4A840&color=fff' }}" alt="{{ $article->author_name ?? 'Author' }}" class="author-avatar">
                    <div>
                        <div class="author-name">{{ $article->author_name ?? '' }}</div>
                        <div class="author-title">{{ $article->author_title ?? '' }}</div>
                    </div>
                </div>
                <div class="article-date">{{ $article->date_label ?? '' }}</div>
            </div>
        </header>

        {{-- Featured Image --}}
        @if($article && $article->image_url)
            <div class="article-featured-image">
                <img src="{{ $article->image_url }}" alt="{{ $article->title }}">
                <div class="image-caption">{{ $article->image_caption ?? '' }}</div>
            </div>
        @endif

        {{-- ═══════════════════════════════════════════════════
             ARTICLE CONTENT (With Premium Paywall)
        ═══════════════════════════════════════════════════ --}}
        <article class="article-body">
            @if($article && $article->body_html)
                {!! $article->body_html !!}
            @else
                <p class="text-gray-400">This article isn't available yet.</p>
            @endif

            {{-- FADING PAYWALL OVERLAY --}}
            <div class="premium-paywall-container">
                <div class="paywall-fade"></div>
                
                <div class="paywall-box">
                    <div class="paywall-icon">
                        <i class="bi bi-star-fill text-gold"></i>
                    </div>
                    <h3 class="paywall-title">This article is for Premium Members</h3>
                    <p class="paywall-desc">Unlock the rest of this article, plus access to our full library of clinical resources, AI-guided discovery maps, and exclusive therapist Q&As.</p>
                    
                    <div class="paywall-actions">
                        <button class="btn-unlock">Unlock Full Access</button>
                        <p class="paywall-login">Already a member? <a href="#">Sign in</a></p>
                    </div>
                </div>
            </div>
        </article>

        {{-- ═══════════════════════════════════════════════════
             ENGAGEMENT & REACTIONS
        ═══════════════════════════════════════════════════ --}}
        <section class="article-engagement">
            <h3 class="engagement-title">Did you find this helpful?</h3>
            <div class="reaction-buttons">
                <button class="btn-reaction"><i class="bi bi-hand-thumbs-up"></i> Yes, very helpful</button>
                <button class="btn-reaction"><i class="bi bi-hand-thumbs-down"></i> Not quite what I needed</button>
                <button class="btn-share"><i class="bi bi-share"></i> Share</button>
            </div>
        </section>

        {{-- ═══════════════════════════════════════════════════
             COMMENTS SECTION
        ═══════════════════════════════════════════════════ --}}
        <section class="article-comments">
            <h3 class="comments-header">Community Discussion ({{ $comments->count() ?? 0 }})</h3>
            
            <div class="comment-input-area">
                <textarea rows="3" placeholder="Share your thoughts on this topic..." class="comment-textarea"></textarea>
                <div class="comment-actions">
                    <button class="btn-post-comment">Post Comment</button>
                </div>
            </div>

            <div class="comments-list">
                @forelse($comments as $comment)
                    <div class="comment-item">
                        <div class="comment-author-row">
                            <div class="comment-avatar" style="background: var(--primary); color: #fff;">{{ substr($comment->author_name, 0, 1) }}</div>
                            <div class="comment-meta">
                                <div class="c-name">{{ $comment->author_name }}</div>
                                <div class="c-time">{{ $comment->time_ago }}</div>
                            </div>
                        </div>
                        <p class="comment-text">{{ $comment->text }}</p>
                        <div class="comment-footer">
                            <button class="c-action"><i class="bi bi-heart"></i> {{ $comment->likes ?? 0 }}</button>
                            <button class="c-action"><i class="bi bi-reply"></i> Reply</button>
                        </div>

                        @foreach(($comment->replies ?? []) as $reply)
                            <div class="comment-reply">
                                <div class="comment-author-row">
                                    <div class="comment-avatar" style="background: #2C2416; color: #fff;">
                                        <i class="bi bi-patch-check"></i>
                                    </div>
                                    <div class="comment-meta">
                                        <div class="c-name">{{ $reply->author_name }} @if($reply->is_author)<span class="author-badge">Author</span>@endif</div>
                                        <div class="c-time">{{ $reply->time_ago }}</div>
                                    </div>
                                </div>
                                <p class="comment-text">{{ $reply->text }}</p>
                                <div class="comment-footer">
                                    <button class="c-action"><i class="bi bi-heart"></i> {{ $reply->likes ?? 0 }}</button>
                                    <button class="c-action"><i class="bi bi-reply"></i> Reply</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @if(!$loop->last)
                        <hr class="comment-divider">
                    @endif
                @empty
                    <p class="text-sm text-gray-400">No comments yet — be the first to share your thoughts.</p>
                @endforelse
            </div>
        </section>

    </main>

    @include('partials.footer')

    {{-- ═══════════════════════════════════════════════════
         STYLES
    ═══════════════════════════════════════════════════ --}}
    <style>
        .article-reader-main {
            max-width: 800px; /* Narrow optimal reading width */
            margin: 0 auto;
            padding: 40px 1.5rem 80px;
            font-family: 'Lato', sans-serif;
            color: var(--dark);
        }

        .text-gold { color: var(--gold); }

        /* Links & Header */
        .back-link {
            color: var(--muted);
            text-decoration: none;
            font-weight: 700;
            font-size: 0.9rem;
            transition: color 0.2s;
        }
        .back-link:hover { color: var(--gold); }

        .article-header { margin-bottom: 2rem; }
        .article-meta {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.75rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }
        .meta-tag { color: var(--primary-dark); letter-spacing: 1px; }
        .meta-dot { color: #ccc; }
        .meta-read-time { color: var(--muted); }

        .article-title {
            font-family: 'IM Fell English', serif;
            font-size: 3.2rem;
            line-height: 1.1;
            color: var(--dark);
            margin-bottom: 1rem;
        }
        .article-subtitle {
            font-size: 1.2rem;
            color: var(--muted);
            line-height: 1.6;
            margin-bottom: 2rem;
        }

        /* Author Row */
        .article-author-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem 0;
            border-top: 1px solid rgba(0,0,0,0.05);
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }
        .author-info { display: flex; align-items: center; gap: 1rem; }
        .author-avatar { width: 48px; height: 48px; border-radius: 50%; border: 2px solid var(--cream); }
        .author-name { font-weight: 700; font-size: 1rem; }
        .author-title { font-size: 0.8rem; color: var(--muted); }
        .article-date { font-size: 0.85rem; color: var(--muted); font-weight: 600; }

        /* Image */
        .article-featured-image { margin-bottom: 3rem; }
        .article-featured-image img {
            width: 100%;
            height: auto;
            border-radius: 12px;
            max-height: 450px;
            object-fit: cover;
        }
        .image-caption {
            text-align: center;
            font-size: 0.8rem;
            color: var(--muted);
            margin-top: 0.8rem;
            font-style: italic;
        }

        /* Article Body Formatting */
        .article-body {
            font-size: 1.15rem;
            line-height: 1.8;
            color: rgba(44,36,22, 0.9);
            position: relative;
        }
        .article-body p { margin-bottom: 1.8rem; }
        .article-body h2 {
            font-family: 'IM Fell English', serif;
            font-size: 2rem;
            margin: 3rem 0 1rem;
            color: var(--dark);
        }
        .dropcap {
            float: left;
            font-family: 'IM Fell English', serif;
            font-size: 4rem;
            line-height: 0.8;
            padding-right: 0.5rem;
            color: var(--gold);
        }
        .article-body blockquote {
            border-left: 4px solid var(--gold);
            padding-left: 1.5rem;
            margin: 2.5rem 0;
            font-family: 'IM Fell English', serif;
            font-size: 1.6rem;
            font-style: italic;
            color: var(--primary-dark);
            line-height: 1.4;
        }

        /* ── PAYWALL OVERLAY ───────────────────────────── */
        .premium-paywall-container {
            position: relative;
            margin-top: -100px; /* Pulls up over the text */
            padding-top: 150px; /* Space for the fade */
            text-align: center;
        }
        .paywall-fade {
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 250px;
            background: linear-gradient(to bottom, rgba(245, 239, 224, 0), var(--cream-light) 80%);
            pointer-events: none;
        }
        .paywall-box {
            position: relative;
            z-index: 10;
            background: #fff;
            border: 1px solid rgba(196, 168, 64, 0.3);
            border-radius: 16px;
            padding: 3rem 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            max-width: 600px;
            margin: 0 auto;
        }
        .paywall-icon i { font-size: 2rem; margin-bottom: 1rem; display: block; }
        .paywall-title { font-family: 'IM Fell English', serif; font-size: 1.8rem; margin-bottom: 0.8rem; }
        .paywall-desc { font-size: 1rem; color: var(--muted); margin-bottom: 2rem; padding: 0 1rem; }
        
        .btn-unlock {
            background: var(--gold);
            color: #fff;
            border: none;
            padding: 0.9rem 2.5rem;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.2s;
            width: 100%;
            margin-bottom: 1rem;
        }
        .btn-unlock:hover { background: #9E8630; }
        .paywall-login { font-size: 0.9rem; color: var(--muted); }
        .paywall-login a { color: var(--primary-dark); font-weight: 700; text-decoration: none; }

        /* ── ENGAGEMENT ───────────────────────────────── */
        .article-engagement {
            margin: 4rem 0;
            padding: 2.5rem 0;
            border-top: 1px solid rgba(0,0,0,0.05);
            border-bottom: 1px solid rgba(0,0,0,0.05);
            text-align: center;
        }
        .engagement-title { font-weight: 700; font-size: 1.2rem; margin-bottom: 1.5rem; }
        .reaction-buttons { display: flex; flex-wrap: wrap; justify-content: center; gap: 1rem; }
        
        .btn-reaction, .btn-share {
            background: #fff;
            border: 1px solid rgba(0,0,0,0.1);
            padding: 0.6rem 1.2rem;
            border-radius: 30px;
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--dark);
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .btn-reaction:hover { border-color: var(--gold); color: var(--gold); }
        .btn-share { background: var(--cream); border: none; }
        .btn-share:hover { background: var(--primary); color: #fff; }

        /* ── COMMENTS ─────────────────────────────────── */
        .article-comments { margin-bottom: 2rem; }
        .comments-header { font-weight: 700; font-size: 1.3rem; margin-bottom: 1.5rem; }
        
        .comment-input-area {
            background: #fff;
            border: 1px solid rgba(0,0,0,0.1);
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 3rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.02);
        }
        .comment-textarea {
            width: 100%;
            border: none;
            outline: none;
            resize: none;
            font-family: inherit;
            font-size: 1rem;
            color: var(--dark);
            margin-bottom: 1rem;
        }
        .comment-actions { display: flex; justify-content: flex-end; }
        .btn-post-comment {
            background: var(--primary);
            color: #fff;
            border: none;
            padding: 0.5rem 1.2rem;
            border-radius: 6px;
            font-weight: 700;
            font-size: 0.9rem;
            cursor: pointer;
        }

        .comments-list { display: flex; flex-direction: column; gap: 1.5rem; }
        .comment-item { display: flex; flex-direction: column; }
        .comment-author-row { display: flex; align-items: center; gap: 0.8rem; margin-bottom: 0.8rem; }
        .comment-avatar {
            width: 36px; height: 36px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: bold; font-size: 0.9rem;
        }
        .c-name { font-weight: 700; font-size: 0.95rem; }
        .author-badge {
            background: var(--gold); color: #fff; font-size: 0.65rem;
            padding: 2px 6px; border-radius: 4px; margin-left: 4px; vertical-align: middle;
        }
        .c-time { font-size: 0.75rem; color: var(--muted); }
        .comment-text { font-size: 0.95rem; line-height: 1.6; color: rgba(44,36,22, 0.85); margin-bottom: 0.8rem; }
        .comment-footer { display: flex; gap: 1rem; }
        .c-action {
            background: none; border: none; font-size: 0.85rem; font-weight: 600;
            color: var(--muted); cursor: pointer; display: flex; align-items: center; gap: 0.3rem; transition: color 0.2s;
        }
        .c-action:hover { color: var(--primary); }
        
        .comment-divider { border: 0; border-top: 1px solid rgba(0,0,0,0.05); margin: 0; }
        .comment-reply {
            margin-top: 1.5rem;
            padding-left: 1.5rem;
            border-left: 2px solid rgba(196, 168, 64, 0.3);
        }
    </style>
@endsection