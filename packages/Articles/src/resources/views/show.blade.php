@extends('layouts.app')

@section('content')
    @include('partials.navbar')
    @include('partials.werta-ui')
    @php
        use Articles\Support\MockBilling as MB;
        $isPaid    = $article->access_type === 'paid';
        $balance   = MB::balance($state);
        $subOn     = $state['sub']['active'];
        $subLeft   = MB::SUB_CAP - count($state['sub']['used']);
        $loginUrl  = \App\Support\DemoAuth::enabled() ? route('demo.index') : url('/client/login');
        $hasPdf    = (bool) $article->file_path;
        $pdfUrl    = $hasPdf ? asset('storage/' . $article->file_path) : null;
    @endphp

    {{-- Main Reading Container --}}
    <main class="article-reader-main">

        {{-- Back Button --}}
        <div class="mb-8">
            <a href="{{ route('public.articles') }}" class="back-link">
                <i class="bi bi-arrow-left"></i> {{ __('Back to Discovery Map') }}
            </a>
        </div>

        {{-- ═══════════════════════════════════════════════════
             ARTICLE HEADER
        ═══════════════════════════════════════════════════ --}}
        <header class="article-header">
            <div class="article-meta">
                @if($article->category_label)
                    <a href="{{ route('public.articles', ['category' => $article->category]) }}#all-articles" class="meta-tag text-decoration-none"><i class="bi {{ $article->category_icon }}"></i> {{ __($article->category_label) }}</a>
                    <span class="meta-dot">•</span>
                @endif
                <span class="meta-tag">{{ $isPaid ? __('PREMIUM') : __('FREE') }}</span>
                @if($reviews->count() || $anonymousReviews > 0)
                    <span class="meta-dot">•</span>
                    <a href="#peer-review" class="meta-reviewed text-decoration-none"><i class="bi bi-patch-check-fill"></i> {{ __('Peer reviewed') }}</a>
                @endif
                @if($article->visibility === 'private')
                    <span class="meta-dot">•</span>
                    <span class="meta-read-time"><i class="bi bi-lock"></i> {{ __('Private') }}</span>
                @endif
            </div>

            <h1 class="article-title">{{ $article->title }}</h1>

            <div class="article-author-row">
                <a href="{{ $article->status === 'published' ? $article->author_url : '#' }}" class="author-info text-decoration-none">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($article->author_name) }}&background=C4A840&color=fff" alt="{{ $article->author_name }}" class="author-avatar">
                    <div>
                        <div class="author-name">{{ $article->author_name }}</div>
                        <div class="author-title">{{ __('nav.roles.' . $article->author_role) }} · {{ __('Werta author') }}</div>
                    </div>
                </a>
                <div class="article-date">{{ $article->submitted_at->translatedFormat('d M Y') }}</div>
            </div>
        </header>

        @include('partials.flash')

        @if($isPaid && $hasAccess)
            <div class="unlock-banner">
                <i class="bi bi-unlock-fill"></i>
                <span>
                    <strong>{{ __('Unlocked') }}</strong> —
                    @if($accessVia === 'author') {{ __('you are the author, so you read this free.') }}
                    @elseif($accessVia === 'purchased') {{ __('you bought this paper. It stays yours forever.') }}
                    @elseif($accessVia === 'gift') {{ __('a gift from :name. It stays yours forever.', ['name' => $purchase['from'] ?? '']) }}
                    @else {{ __('available through your subscription allowance.') }}
                    @endif
                </span>
                <span class="wt-demo-tag ms-auto">{{ __('DEMO') }}</span>
            </div>
            @if($accessVia === 'gift' && !empty($purchase['note']))
                <div class="gift-note"><i class="bi bi-gift"></i> “{{ $purchase['note'] }}”</div>
            @endif
        @endif

        {{-- ═══════════════════════════════════════════════════
             ARTICLE CONTENT (free preview, then paywall)
        ═══════════════════════════════════════════════════ --}}
        <article class="article-body">
            @if($article->abstract)
                <div class="abstract-block">
                    <div class="abstract-label">{{ __('Abstract') }}</div>
                    <p>{{ $article->abstract }}</p>
                </div>
            @endif

            @if($hasAccess)
                @if($isPaid && $viewer)
                    {{-- Per-reader watermark (demo of the anti-piracy deterrent) --}}
                    <div aria-hidden="true" class="reader-watermark">
                        {{ $viewer[1] }} · {{ now()->format('d M Y H:i') }}<br>{{ __('Werta licensed copy') }}
                    </div>
                @endif
                @if($hasPdf)
                    <div class="pdf-reader" id="pdf-reader" data-url="{{ $pdfUrl }}" data-mode="full">
                        <div class="pdf-status" id="pdf-status"><span class="spinner-border spinner-border-sm"></span> {{ __('Loading paper…') }}</div>
                        <div id="pdf-pages"></div>
                    </div>
                    <p class="pdf-note"><i class="bi bi-shield-lock"></i> {{ __('Read in the browser. Downloads are disabled for premium papers.') }}</p>
                @elseif($article->content)
                    <p style="white-space: pre-line;">{{ $article->content }}</p>
                @else
                    <p class="text-muted">{{ __('This article isn\'t available yet.') }}</p>
                @endif
            @else
                {{-- Free preview: first paragraphs or first pages, the rest blurred under the payment prompt --}}
                @if($hasPdf)
                    <div class="pdf-reader" id="pdf-reader" data-url="{{ $pdfUrl }}" data-mode="preview" data-preview-pages="2">
                        <div class="pdf-status" id="pdf-status"><span class="spinner-border spinner-border-sm"></span> {{ __('Loading preview…') }}</div>
                        <div id="pdf-pages"></div>
                    </div>
                @else
                    @foreach($preview['visible'] as $para)
                        <p>{{ $para }}</p>
                    @endforeach
                @endif

                <div class="preview-locked">
                    <div class="preview-blur" aria-hidden="true">
                        @if($hasPdf)
                            <div id="pdf-blur"></div>
                        @else
                            @foreach($preview['filler'] as $para)
                                <p>{{ $para }}</p>
                            @endforeach
                        @endif
                    </div>

                    <div class="paywall-overlay">
                        <div class="paywall-box">
                            <div class="paywall-icon"><i class="bi bi-lock-fill"></i></div>
                            <div class="paywall-eyebrow">{{ __('End of free preview') }}</div>
                            <h3 class="paywall-title">{{ __('Keep reading this paper') }}</h3>
                            <p class="paywall-desc">
                                {{ __('Unlock the full paper for :n tokens (:price) and keep it forever.', ['n' => MB::ARTICLE_TOKENS, 'price' => MB::rm(MB::articlePrice())]) }}
                                @if($hasPdf)
                                    <span id="pdf-more" class="d-block mt-1"></span>
                                @elseif($preview['hidden'] > 0)
                                    <span class="d-block mt-1">{{ trans_choice(':count more paragraph in the full paper.|:count more paragraphs in the full paper.', $preview['hidden'], ['count' => $preview['hidden']]) }}</span>
                                @endif
                            </p>

                            @if(!$viewer)
                                <a href="{{ $loginUrl }}" class="btn-unlock">{{ __('Sign in to continue') }}</a>
                                <p class="paywall-login">{{ __('Readers sign in as a client, parent or counselor.') }}</p>
                            @else
                                @if($subOn && $subLeft > 0)
                                    <form method="POST" action="{{ route('mock.allowance', $article->slug) }}">@csrf
                                        <button class="btn-unlock">{{ __('Read with my subscription (:left of :cap left)', ['left' => $subLeft, 'cap' => MB::SUB_CAP]) }}</button>
                                    </form>
                                @endif
                                @if($balance >= MB::ARTICLE_TOKENS)
                                    <form method="POST" action="{{ route('mock.buy', $article->slug) }}">@csrf
                                        <button class="{{ $subOn && $subLeft > 0 ? 'btn-unlock btn-unlock--outline' : 'btn-unlock' }}"><i class="bi bi-coin"></i> {{ __('Unlock for :n tokens', ['n' => MB::ARTICLE_TOKENS]) }}</button>
                                    </form>
                                    <p class="paywall-login">{{ __('Your balance: :n tokens', ['n' => $balance]) }}</p>
                                @else
                                    <a href="{{ route('public.articles.subscribe', ['slug' => $article->slug]) }}" class="btn-unlock">{{ __('Top up and unlock') }}</a>
                                    <p class="paywall-login">{{ __('You have :have tokens. This paper needs :need.', ['have' => $balance, 'need' => MB::ARTICLE_TOKENS]) }}</p>
                                @endif
                                <a href="{{ route('public.articles.subscribe', ['slug' => $article->slug]) }}" class="paywall-more">{{ __('More options: subscription or gift') }} <i class="bi bi-arrow-right"></i></a>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </article>

        {{-- Gift --}}
        @if($isPaid && $viewer && $accessVia !== 'author')
            <div class="gift-strip">
                <i class="bi bi-gift"></i>
                <span>{{ __('Know someone who needs this? Gift it for :n tokens. The author still earns from every gift.', ['n' => MB::ARTICLE_TOKENS]) }}</span>
                <a href="{{ route('public.articles.subscribe', ['slug' => $article->slug]) }}#gift" class="wt-btn wt-btn--outline wt-btn--sm">{{ __('Gift this paper') }}</a>
            </div>
        @endif

        {{-- ═══════════════════════════════════════════════════
             PEER REVIEW
        ═══════════════════════════════════════════════════ --}}
        @if($reviews->count() || $anonymousReviews > 0)
            <section class="review-panel" id="peer-review">
                <div class="review-panel-head">
                    <i class="bi bi-patch-check-fill"></i>
                    <div>
                        <h3>{{ __('Peer reviewed by verified professionals') }}</h3>
                        <p>{{ __('Checked for accuracy, clarity, relevance and ethics before publication.') }}</p>
                    </div>
                </div>
                <div class="reviewer-cards">
                    @foreach($reviews as $review)
                        <a href="{{ route('public.reviewers.show', $review->reviewer->slug) }}" class="reviewer-card">
                            <img src="{{ $review->reviewer->avatar_url }}" alt="{{ $review->reviewer->name }}" class="reviewer-avatar">
                            <div class="min-w-0">
                                <div class="reviewer-name">{{ $review->reviewer->name }} <span class="wt-badge wt-badge--green"><i class="bi bi-shield-check"></i> {{ __('Verified') }}</span></div>
                                <div class="reviewer-role">{{ $review->reviewer->title }}@if($review->reviewer->institution) · {{ $review->reviewer->institution }}@endif</div>
                                <div class="reviewer-meta">
                                    @if($review->reviewer->field_label)<span><i class="bi bi-bookmark-star"></i> {{ __($review->reviewer->field_label) }}</span>@endif
                                    @if($review->submitted_at)<span><i class="bi bi-calendar3"></i> {{ __('Reviewed :date', ['date' => $review->submitted_at->translatedFormat('d M Y')]) }}</span>@endif
                                    @if($review->overallScore())<span><i class="bi bi-star-fill" style="color: var(--gold);"></i> {{ $review->overallScore() }}/5</span>@endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
                @if($anonymousReviews > 0)
                    <p class="review-anon"><i class="bi bi-incognito"></i> {{ trans_choice(':count reviewer chose to stay anonymous.|:count reviewers chose to stay anonymous.', $anonymousReviews, ['count' => $anonymousReviews]) }}</p>
                @endif
            </section>
        @endif

        {{-- About the author --}}
        @if($article->status === 'published')
            <section class="about-author">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($article->author_name) }}&background=C4A840&color=fff&size=128" alt="{{ $article->author_name }}" class="about-author-avatar">
                <div class="flex-grow-1">
                    <div class="about-author-label">{{ __('Written by') }}</div>
                    <div class="about-author-name">{{ $article->author_name }}</div>
                    <div class="about-author-meta">{{ __('nav.roles.' . $article->author_role) }} · {{ trans_choice(':count published paper|:count published papers', $authorPapers, ['count' => $authorPapers]) }}</div>
                </div>
                <a href="{{ $article->author_url }}" class="wt-btn wt-btn--outline wt-btn--sm">{{ __('View author page') }}</a>
            </section>
        @endif

        {{-- ═══════════════════════════════════════════════════
             ENGAGEMENT & REACTIONS
        ═══════════════════════════════════════════════════ --}}
        <section class="article-engagement">
            <h3 class="engagement-title">{{ __('Did you find this helpful?') }}</h3>
            <div class="reaction-buttons">
                <button class="btn-reaction"><i class="bi bi-hand-thumbs-up"></i> {{ __('Yes, very helpful') }}</button>
                <button class="btn-reaction"><i class="bi bi-hand-thumbs-down"></i> {{ __('Not quite what I needed') }}</button>
                <button class="btn-share"><i class="bi bi-share"></i> {{ __('Share') }}</button>
            </div>
        </section>

        {{-- ═══════════════════════════════════════════════════
             COMMENTS SECTION
        ═══════════════════════════════════════════════════ --}}
        <section class="article-comments">
            <h3 class="comments-header">{{ __('Community Discussion (') }}{{ \App\Support\SiteText::translate($comments->count() ?? 0) }})</h3>

            <div class="comment-input-area">
                <textarea rows="3" placeholder="{{ __('Share your thoughts on this topic...') }}" class="comment-textarea"></textarea>
                <div class="comment-actions">
                    <button class="btn-post-comment">{{ __('Post Comment') }}</button>
                </div>
            </div>

            <div class="comments-list">
                @forelse($comments as $comment)
                    <div class="comment-item">
                        <div class="comment-author-row">
                            <div class="comment-avatar" style="background: var(--primary); color: #fff;">{{ \App\Support\SiteText::translate(substr($comment->author_name, 0, 1)) }}</div>
                            <div class="comment-meta">
                                <div class="c-name">{{ \App\Support\SiteText::translate($comment->author_name) }}</div>
                                <div class="c-time">{{ \App\Support\SiteText::translate($comment->time_ago) }}</div>
                            </div>
                        </div>
                        <p class="comment-text">{{ \App\Support\SiteText::translate($comment->text) }}</p>
                    </div>
                    @if(!$loop->last)
                        <hr class="comment-divider">
                    @endif
                @empty
                    <p class="text-sm text-muted">{{ __('No comments yet — be the first to share your thoughts.') }}</p>
                @endforelse
            </div>
        </section>

    </main>

    @include('partials.footer')

    @if($hasPdf)
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
        <script>
            (function () {
                const reader = document.getElementById('pdf-reader');
                if (!reader || !window.pdfjsLib) return;
                pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

                const preview = reader.dataset.mode === 'preview';
                const previewPages = parseInt(reader.dataset.previewPages || '2', 10);
                const pagesEl = document.getElementById('pdf-pages');
                const statusEl = document.getElementById('pdf-status');

                async function renderPage(pdf, number, target) {
                    const page = await pdf.getPage(number);
                    const width = Math.min(target.clientWidth || 760, 760);
                    const base = page.getViewport({ scale: 1 });
                    const scale = width / base.width;
                    const ratio = window.devicePixelRatio || 1;
                    const viewport = page.getViewport({ scale: scale * ratio });
                    const canvas = document.createElement('canvas');
                    canvas.className = 'pdf-page';
                    canvas.width = viewport.width;
                    canvas.height = viewport.height;
                    canvas.style.width = (viewport.width / ratio) + 'px';
                    canvas.style.height = (viewport.height / ratio) + 'px';
                    canvas.addEventListener('contextmenu', e => e.preventDefault());
                    target.appendChild(canvas);
                    await page.render({ canvasContext: canvas.getContext('2d'), viewport }).promise;
                }

                pdfjsLib.getDocument(reader.dataset.url).promise.then(async (pdf) => {
                    const total = pdf.numPages;
                    const last = preview ? Math.min(previewPages, total) : total;
                    for (let n = 1; n <= last; n++) {
                        await renderPage(pdf, n, pagesEl);
                    }
                    statusEl.remove();
                    if (preview) {
                        const more = document.getElementById('pdf-more');
                        const hidden = total - last;
                        if (more && hidden > 0) {
                            more.textContent = WertaI18n.t(':count more pages in the full paper.', { count: hidden });
                        }
                        const blur = document.getElementById('pdf-blur');
                        if (blur && total > last) {
                            await renderPage(pdf, last + 1, blur);
                        }
                    }
                }).catch(() => {
                    statusEl.textContent = WertaI18n.t('The paper could not be loaded. Run php artisan storage:link and refresh.');
                });
            })();
        </script>
    @endif

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

        /* Links & Header */
        .back-link {
            color: var(--muted);
            text-decoration: none;
            font-weight: 700;
            font-size: var(--text-base);
            transition: color 0.2s;
        }
        .back-link:hover { color: var(--primary); }
        .mb-8 { margin-bottom: 2rem; }

        .article-header { margin-bottom: 2rem; }
        .article-meta {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.5rem;
            font-size: var(--text-sm);
            font-weight: 700;
            margin-bottom: 1.5rem;
        }
        .meta-tag { color: var(--primary-dark); letter-spacing: 1px; text-transform: uppercase; }
        .meta-dot { color: #ccc; }
        .meta-read-time { color: var(--muted); }
        .meta-reviewed { color: #3a7d44; }

        .article-title {
            font-family: 'IM Fell English', serif;
            font-size: 3.2rem;
            line-height: 1.1;
            color: var(--dark);
            margin-bottom: 1.5rem;
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
        .author-info { display: flex; align-items: center; gap: 1rem; color: var(--dark); }
        .author-info:hover .author-name { color: var(--primary); }
        .author-avatar { width: 48px; height: 48px; border-radius: var(--radius-full); border: 2px solid var(--cream); }
        .author-name { font-weight: 700; font-size: var(--text-md); transition: color .2s; }
        .author-title { font-size: var(--text-sm); color: var(--muted); }
        .article-date { font-size: 0.85rem; color: var(--muted); font-weight: 600; }

        .unlock-banner { display: flex; align-items: center; gap: .6rem; background: var(--cream-light); border: 1px solid var(--gold); border-radius: var(--radius-lg); padding: .75rem 1rem; margin-bottom: 1.5rem; font-size: var(--text-base); color: var(--primary-dark); }
        .unlock-banner > i { color: var(--gold); }
        .gift-note { margin: -0.75rem 0 1.5rem; font-style: italic; color: var(--muted); font-size: var(--text-base); }
        .gift-note i { color: var(--gold); font-style: normal; }

        /* Article Body Formatting */
        .article-body {
            font-size: 1.15rem;
            line-height: 1.8;
            color: rgba(44,36,22, 0.9);
            position: relative;
        }
        .article-body p { margin-bottom: 1.8rem; }
        .abstract-block { background: var(--cream-light); border-left: 3px solid var(--gold); border-radius: 0 var(--radius-md) var(--radius-md) 0; padding: 1.1rem 1.4rem; margin-bottom: 2.2rem; }
        .abstract-label { font-size: var(--text-xs); font-weight: 700; letter-spacing: 2px; text-transform: uppercase; color: var(--primary); margin-bottom: .3rem; }
        .abstract-block p { margin: 0; font-size: 1.05rem; font-style: italic; color: var(--muted); }

        .reader-watermark { position: fixed; inset: 0; z-index: 5; pointer-events: none; display: flex; align-items: center; justify-content: center; transform: rotate(-24deg); font-size: 2.2rem; font-weight: 700; color: rgba(123,107,53,.09); text-align: center; line-height: 1.6; }

        /* PDF reader */
        .pdf-reader { display: flex; flex-direction: column; align-items: center; }
        #pdf-pages, #pdf-blur { width: 100%; display: flex; flex-direction: column; align-items: center; gap: 1.25rem; }
        .pdf-page { display: block; max-width: 100%; background: #fff; box-shadow: var(--card-ring), var(--shadow-md); border-radius: 4px; user-select: none; }
        .pdf-status { font-size: var(--text-base); color: var(--muted); padding: 2rem 0; }
        .pdf-note { font-size: var(--text-sm); color: var(--muted); text-align: center; margin-top: 1rem; }

        /* ── PREVIEW + PAYWALL ─────────────────────────── */
        .preview-locked { position: relative; margin-top: 1.25rem; min-height: 520px; }
        .preview-blur { filter: blur(6px); opacity: .55; user-select: none; pointer-events: none; max-height: 640px; overflow: hidden; -webkit-mask-image: linear-gradient(to bottom, #000 40%, transparent); mask-image: linear-gradient(to bottom, #000 40%, transparent); }
        .paywall-overlay { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; padding: 1rem; }
        .paywall-box {
            position: relative;
            z-index: 10;
            background: #fff;
            border-radius: 16px;
            padding: 2.4rem 2rem;
            box-shadow: 0 0 0 1px rgba(196, 168, 64, 0.45), 0 18px 40px rgba(44,36,22,0.12);
            max-width: 520px;
            width: 100%;
            text-align: center;
        }
        .paywall-icon i { font-size: 1.8rem; color: var(--gold); display: block; margin-bottom: .6rem; }
        .paywall-eyebrow { font-size: var(--text-xs); font-weight: 700; letter-spacing: 2px; text-transform: uppercase; color: var(--primary); }
        .paywall-title { font-family: 'IM Fell English', serif; font-size: var(--text-2xl); margin: .3rem 0 .6rem; }
        .paywall-desc { font-size: var(--text-base-lg); color: var(--muted); margin-bottom: 1.5rem; line-height: 1.6; }
        .paywall-box form { margin: 0; }
        .btn-unlock {
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            background: var(--gold);
            color: var(--dark);
            border: none;
            padding: 0.85rem 2rem;
            border-radius: var(--radius-md);
            font-size: var(--text-md);
            font-weight: 700;
            cursor: pointer;
            transition: background 0.2s;
            width: 100%;
            margin-bottom: .75rem;
            text-decoration: none;
        }
        .btn-unlock:hover { background: var(--primary); color: #fff; }
        .btn-unlock--outline { background: #fff; box-shadow: inset 0 0 0 1.5px var(--primary); color: var(--primary-dark); }
        .paywall-login { font-size: var(--text-sm); color: var(--muted); margin-bottom: .6rem !important; }
        .paywall-more { font-size: var(--text-base); font-weight: 700; color: var(--primary-dark); text-decoration: none; }
        .paywall-more:hover { color: var(--primary); }

        .gift-strip { display: flex; flex-wrap: wrap; align-items: center; gap: .75rem; background: var(--cream-light); border-radius: var(--radius-lg); box-shadow: var(--card-ring); padding: 1rem 1.2rem; margin: 2.5rem 0 0; font-size: var(--text-base); color: var(--primary-dark); }
        .gift-strip > i { color: var(--gold); font-size: 1.3rem; }
        .gift-strip > span { flex: 1 1 260px; }

        /* Peer review */
        .review-panel { margin-top: 3rem; background: #fff; border-radius: 16px; box-shadow: var(--card-ring), var(--shadow-md); padding: 1.75rem; scroll-margin-top: 90px; }
        .review-panel-head { display: flex; gap: .9rem; align-items: flex-start; margin-bottom: 1.25rem; }
        .review-panel-head > i { font-size: 1.8rem; color: #3a7d44; line-height: 1; }
        .review-panel-head h3 { font-family: 'IM Fell English', serif; font-size: var(--text-xl); margin: 0 0 .2rem; }
        .review-panel-head p { margin: 0; color: var(--muted); font-size: var(--text-base); }
        .reviewer-cards { display: grid; gap: .9rem; }
        .reviewer-card { display: flex; gap: 1rem; align-items: center; padding: 1rem; border-radius: var(--radius-lg); background: var(--cream-light); text-decoration: none; color: var(--dark); transition: box-shadow .2s; }
        .reviewer-card:hover { box-shadow: var(--card-ring-hover); color: var(--dark); }
        .reviewer-avatar { width: 52px; height: 52px; border-radius: var(--radius-full); flex-shrink: 0; }
        .reviewer-name { font-weight: 700; display: flex; flex-wrap: wrap; align-items: center; gap: .5rem; }
        .reviewer-role { font-size: var(--text-base); color: var(--muted); }
        .reviewer-meta { display: flex; flex-wrap: wrap; gap: .3rem 1rem; font-size: var(--text-sm); color: var(--muted); margin-top: .2rem; }
        .review-anon { font-size: var(--text-sm); color: var(--muted); margin: 1rem 0 0; }
        .min-w-0 { min-width: 0; }

        .about-author { display: flex; flex-wrap: wrap; align-items: center; gap: 1rem; margin-top: 1.5rem; padding: 1.4rem 1.6rem; border-radius: 16px; background: var(--cream-light); box-shadow: var(--card-ring); }
        .about-author-avatar { width: 64px; height: 64px; border-radius: var(--radius-full); border: 2px solid #fff; }
        .about-author-label { font-size: var(--text-xs); font-weight: 700; letter-spacing: 2px; text-transform: uppercase; color: var(--primary); }
        .about-author-name { font-family: 'IM Fell English', serif; font-size: var(--text-xl); line-height: 1.2; }
        .about-author-meta { font-size: var(--text-sm); color: var(--muted); }

        /* ── ENGAGEMENT ───────────────────────────────── */
        .article-engagement {
            margin: 4rem 0;
            padding: 2.5rem 0;
            border-top: 1px solid rgba(0,0,0,0.05);
            border-bottom: 1px solid rgba(0,0,0,0.05);
            text-align: center;
        }
        .engagement-title { font-weight: 700; font-size: var(--text-lg); margin-bottom: 1.5rem; }
        .reaction-buttons { display: flex; flex-wrap: wrap; justify-content: center; gap: 1rem; }

        .btn-reaction, .btn-share {
            background: #fff;
            border: 1px solid rgba(0,0,0,0.1);
            padding: 0.6rem 1.2rem;
            border-radius: var(--radius-pill);
            font-size: var(--text-base-lg);
            font-weight: 600;
            color: var(--dark);
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .btn-reaction:hover { border-color: var(--primary); color: var(--primary); }
        .btn-share { background: var(--cream); border: none; }
        .btn-share:hover { background: var(--primary); color: #fff; }

        /* ── COMMENTS ─────────────────────────────────── */
        .article-comments { margin-bottom: 2rem; }
        .comments-header { font-weight: 700; font-size: 1.3rem; margin-bottom: 1.5rem; }

        .comment-input-area {
            background: #fff;
            border: 1px solid rgba(0,0,0,0.1);
            border-radius: var(--radius-lg);
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
            font-size: var(--text-md);
            color: var(--dark);
            margin-bottom: 1rem;
        }
        .comment-actions { display: flex; justify-content: flex-end; }
        .btn-post-comment {
            background: var(--primary);
            color: #fff;
            border: none;
            padding: 0.5rem 1.2rem;
            border-radius: var(--radius-sm);
            font-weight: 700;
            font-size: var(--text-base);
            cursor: pointer;
        }

        .comments-list { display: flex; flex-direction: column; gap: 1.5rem; }
        .comment-item { display: flex; flex-direction: column; }
        .comment-author-row { display: flex; align-items: center; gap: 0.8rem; margin-bottom: 0.8rem; }
        .comment-avatar {
            width: 36px; height: 36px; border-radius: var(--radius-full);
            display: flex; align-items: center; justify-content: center;
            font-weight: bold; font-size: var(--text-base);
        }
        .c-name { font-weight: 700; font-size: var(--text-base-lg); }
        .c-time { font-size: var(--text-sm); color: var(--muted); }
        .comment-text { font-size: var(--text-base-lg); line-height: 1.6; color: rgba(44,36,22, 0.85); margin-bottom: 0.8rem; }
        .comment-divider { border: 0; border-top: 1px solid rgba(0,0,0,0.05); margin: 0; }

        @media (max-width: 767px) {
            .article-title { font-size: 2.3rem; }
            .article-author-row { flex-direction: column; align-items: flex-start; gap: .75rem; }
            .paywall-box { padding: 1.8rem 1.25rem; }
        }
    </style>
@endsection
