@extends('layouts.app')

@section('content')
    @include('partials.navbar')

    {{-- eLearning-specific styles (shared across every elearning page) --}}
    <style>
        /* ─── ELEARNING SECTIONS ─────────────────────────── */
        .el-section { padding: 80px 0; }
        .el-section.bg-cream { background-color: var(--cream); }
        .el-section.bg-cream-light { background-color: var(--cream-light); }
        .el-section.bg-dark { background-color: var(--dark); color: #fff; }

        /* ─── DROPDOWN ANIMATION ─────────────────────────── */
        .elearning-dropdown-menu {
            display: block !important;
            visibility: hidden;
            opacity: 0;
            transform: translateY(10px);
            transition: all 0.3s ease !important;
            pointer-events: none;
        }
        .elearning-dropdown:hover .elearning-dropdown-menu {
            visibility: visible;
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto;
        }

        .el-container { max-width: 1200px; margin: 0 auto; padding: 0 1.5rem; }

        .el-pill {
            font-size: 0.75rem; font-weight: 700; letter-spacing: 3px;
            text-transform: uppercase; color: var(--gold); margin-bottom: 0.6rem;
        }

        .el-heading {
            font-family: 'IM Fell English', serif; font-size: 2.4rem;
            color: var(--dark); margin-bottom: 1rem; line-height: 1.25;
        }

        .el-heading--light { color: #fff; }

        .el-subtext { font-size: 1rem; color: var(--muted); line-height: 1.8; max-width: 640px; }
        .el-subtext--light { color: rgba(255,255,255,0.6); }

        /* ─── EMPTY STATE ────────────────────────────────── */
        .el-empty-state {
            text-align: center; padding: 60px 20px; background: var(--cream-light);
            border: 2px dashed rgba(123,107,53,0.2); border-radius: 14px;
        }
        .el-empty-state--dark { background: rgba(255,255,255,0.04); border-color: rgba(255,255,255,0.1); }
        .el-empty-state i { font-size: 3rem; color: var(--gold); margin-bottom: 1rem; display: block; opacity: 0.5; }
        .el-empty-state h5 { font-weight: 700; margin-bottom: 0.5rem; }
        .el-empty-state p { font-size: 0.9rem; color: var(--muted); max-width: 400px; margin: 0 auto; }
        .el-empty-state--dark h5 { color: #fff; }
        .el-empty-state--dark p { color: rgba(255,255,255,0.5); }

        /* ─── CARD STYLES ────────────────────────────────── */
        .el-card {
            background: var(--cream-light); border: 1px solid rgba(123,107,53,0.12);
            border-radius: 12px; padding: 2rem; transition: transform 0.25s ease, box-shadow 0.25s ease;
        }
        .el-card:hover { transform: translateY(-4px); box-shadow: 0 12px 32px rgba(44,36,22,0.1); }
        .el-card--dark { background: rgba(255,255,255,0.06); border-color: rgba(255,255,255,0.1); }
        .el-card--dark:hover { background: rgba(255,255,255,0.1); }

        /* ─── BUTTONS ────────────────────────────────────── */
        .el-btn-primary {
            background: var(--primary-dark); color: #fff; border: none; padding: 0.8rem 2rem;
            border-radius: 6px; font-weight: 700; font-size: 0.95rem; text-decoration: none;
            display: inline-block; transition: background 0.2s, transform 0.15s; cursor: pointer;
        }
        .el-btn-primary:hover { background: var(--dark); color: #fff; transform: translateY(-1px); }

        .el-btn-outline {
            background: transparent; color: var(--primary-dark); border: 2px solid var(--primary-dark);
            padding: 0.75rem 2rem; border-radius: 6px; font-weight: 700; font-size: 0.95rem;
            text-decoration: none; display: inline-block; transition: all 0.2s; cursor: pointer;
        }
        .el-btn-outline:hover { background: var(--primary-dark); color: #fff; }
        .el-btn-outline--light { color: #fff; border-color: #fff; }
        .el-btn-outline--light:hover { background: #fff; color: var(--primary-dark); }

        /* ─── STAT COUNTER ────────────────────────────────── */
        .el-stat-number {
            font-family: 'IM Fell English', serif; font-size: 2.8rem; font-weight: 700;
            color: var(--primary-dark); line-height: 1;
        }
        .el-stat-label {
            font-size: 0.85rem; color: var(--muted); font-weight: 600;
            letter-spacing: 1px; text-transform: uppercase; margin-top: 0.4rem;
        }

        /* ─── CATEGORY CARDS ──────────────────────────────── */
        .el-cat-card {
            background: var(--cream-light); border: 1px solid rgba(123,107,53,0.12); border-radius: 12px;
            padding: 2rem 1.5rem; text-align: center; transition: all 0.25s ease; cursor: pointer;
            text-decoration: none; display: block; color: var(--dark);
        }
        .el-cat-card:hover {
            transform: translateY(-5px); box-shadow: 0 12px 32px rgba(44,36,22,0.1);
            border-color: var(--gold); color: var(--dark);
        }
        .el-cat-card-icon {
            width: 56px; height: 56px; margin: 0 auto 1rem; border-radius: 50%;
            background: rgba(196,168,64,0.12); display: flex; align-items: center;
            justify-content: center; transition: background 0.25s ease;
        }
        .el-cat-card-icon i { font-size: 1.5rem; color: var(--gold); margin: 0; }
        .el-cat-card:hover .el-cat-card-icon { background: var(--gold); }
        .el-cat-card:hover .el-cat-card-icon i { color: #fff; }
        .el-cat-card h5 { font-weight: 700; font-size: 0.95rem; margin-bottom: 0.3rem; }
        .el-cat-card-count {
            display: inline-block; margin-top: 0.4rem; font-size: 0.75rem; font-weight: 600;
            color: var(--muted); background: rgba(123,107,53,0.08); border-radius: 20px; padding: 0.25rem 0.75rem;
        }

        /* ─── PRICING CARDS ───────────────────────────────── */
        .el-pricing-card {
            background: var(--cream-light); border: 1px solid rgba(123,107,53,0.12); border-radius: 14px;
            padding: 2.5rem 2rem; text-align: center; transition: transform 0.25s ease, box-shadow 0.25s ease;
            position: relative;
        }
        .el-pricing-card:hover { transform: translateY(-6px); box-shadow: 0 16px 40px rgba(44,36,22,0.12); }
        .el-pricing-card.recommended { border: 2px solid var(--gold); box-shadow: 0 8px 30px rgba(196,168,64,0.15); }
        .el-pricing-badge {
            position: absolute; top: -12px; left: 50%; transform: translateX(-50%); background: var(--gold);
            color: #fff; font-size: 0.7rem; font-weight: 700; letter-spacing: 2px; text-transform: uppercase;
            padding: 4px 16px; border-radius: 20px;
        }
        .el-pricing-price {
            font-family: 'IM Fell English', serif; font-size: 2.8rem; color: var(--primary-dark);
            line-height: 1; margin: 1rem 0 0.2rem;
        }
        .el-pricing-period { font-size: 0.85rem; color: var(--muted); margin-bottom: 1.5rem; }
        .el-pricing-features { list-style: none; padding: 0; margin: 0 0 2rem 0; text-align: left; }
        .el-pricing-features li {
            font-size: 0.9rem; color: var(--dark); padding: 0.5rem 0;
            border-bottom: 1px solid rgba(123,107,53,0.08); display: flex; align-items: center; gap: 10px;
        }
        .el-pricing-features li i { color: var(--gold); font-size: 0.85rem; }

        /* ─── FAQ ACCORDION ───────────────────────────────── */
        .el-faq-item {
            background: var(--cream-light); border: 1px solid rgba(123,107,53,0.1); border-radius: 10px;
            margin-bottom: 0.8rem; overflow: hidden; transition: border-color 0.2s;
        }
        .el-faq-item:hover { border-color: var(--gold); }
        .el-faq-question {
            width: 100%; background: none; border: none; padding: 1.2rem 1.5rem; font-size: 0.95rem;
            font-weight: 700; color: var(--dark); text-align: left; cursor: pointer; display: flex;
            justify-content: space-between; align-items: center; transition: color 0.2s;
        }
        .el-faq-question:hover { color: var(--primary); }
        .el-faq-question i { transition: transform 0.3s ease; color: var(--gold); }
        .el-faq-question.active i { transform: rotate(180deg); }
        .el-faq-answer { max-height: 0; overflow: hidden; transition: max-height 0.35s ease, padding 0.35s ease; padding: 0 1.5rem; }
        .el-faq-answer.open { max-height: 200px; padding: 0 1.5rem 1.2rem; }
        .el-faq-answer p { font-size: 0.9rem; color: var(--muted); line-height: 1.7; margin: 0; }

        /* ─── DASHBOARD PREVIEW ──────────────────────────── */
        .el-dashboard-mock {
            background: var(--cream-light); border: 1px solid rgba(123,107,53,0.15); border-radius: 14px;
            overflow: hidden; box-shadow: 0 20px 60px rgba(44,36,22,0.12);
        }
        .el-dashboard-topbar { background: var(--dark); padding: 0.8rem 1.5rem; display: flex; align-items: center; gap: 8px; }
        .el-dashboard-dot { width: 10px; height: 10px; border-radius: 50%; display: inline-block; }
        .el-dashboard-dot.red { background: #ff5f57; }
        .el-dashboard-dot.yellow { background: #ffbd2e; }
        .el-dashboard-dot.green { background: #28c840; }
        .el-dashboard-body {
            padding: 2rem; min-height: 280px; display: flex; flex-direction: column;
            justify-content: center; align-items: center;
        }

        /* ─── PATH CARDS ──────────────────────────────────── */
        .el-path-card {
            background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px;
            padding: 2rem 1.5rem; text-align: center; transition: all 0.25s ease; cursor: pointer;
        }
        .el-path-card:hover { background: rgba(255,255,255,0.12); transform: translateY(-4px); box-shadow: 0 12px 30px rgba(0,0,0,0.2); }
        .el-path-card i { font-size: 2rem; color: var(--gold); margin-bottom: 1rem; display: block; }
        .el-path-card h5 { font-weight: 700; font-size: 1rem; color: #fff; margin-bottom: 0.3rem; }
        .el-path-card p { font-size: 0.8rem; color: rgba(255,255,255,0.5); margin: 0; }

        /* ─── FEATURE GRID ────────────────────────────────── */
        .el-feature-item { display: flex; align-items: flex-start; gap: 1rem; padding: 1.5rem 0; }
        .el-feature-icon {
            width: 48px; height: 48px; background: rgba(196,168,64,0.12); border-radius: 10px;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .el-feature-icon i { font-size: 1.2rem; color: var(--gold); }

        /* ─── HERO SEARCH ─────────────────────────────────── */
        .el-hero-search {
            display: flex; max-width: 520px; background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.2); border-radius: 8px; overflow: hidden; margin-top: 1.5rem;
        }
        .el-hero-search input { flex: 1; border: none; background: transparent; padding: 0.9rem 1.2rem; color: #fff; font-size: 0.95rem; outline: none; }
        .el-hero-search input::placeholder { color: rgba(255,255,255,0.5); }
        .el-hero-search button { background: var(--gold); border: none; padding: 0.9rem 1.4rem; color: #fff; font-weight: 700; cursor: pointer; transition: background 0.2s; }
        .el-hero-search button:hover { background: var(--primary); }

        /* ─── SECTION DIVIDER ─────────────────────────────── */
        .el-divider { border: none; border-top: 1px solid rgba(123,107,53,0.15); margin: 0; }

        /* ─── TAB NAVIGATION (now links between real pages) ─ */
        .el-tab-bar-wrap {
            background: var(--cream); border-bottom: 1px solid rgba(123,107,53,0.15);
            position: sticky; top: 0; z-index: 20;
        }
        .el-tab-bar {
            max-width: 1200px; margin: 0 auto; padding: 0 1.5rem; display: flex;
            justify-content: center; flex-wrap: wrap; gap: 0.25rem; border-bottom: none !important;
        }
        .el-tab-bar a.nav-link {
            border: none; border-bottom: 3px solid transparent; border-radius: 0; background: none;
            color: var(--muted); font-weight: 700; font-size: 0.85rem; letter-spacing: 0.5px;
            text-transform: uppercase; padding: 1.1rem 1rem; transition: color 0.2s, border-color 0.2s;
            text-decoration: none; display: inline-block;
        }
        .el-tab-bar a.nav-link:hover { color: var(--dark); border-color: rgba(196,168,64,0.4); }
        .el-tab-bar a.nav-link.active { color: var(--primary-dark); border-bottom: 3px solid var(--gold); }

        @media (max-width: 767px) {
            .el-tab-bar { flex-wrap: nowrap; justify-content: flex-start; overflow-x: auto; -webkit-overflow-scrolling: touch; }
            .el-tab-bar a.nav-link { white-space: nowrap; padding: 1rem 0.85rem; }
        }

        /* ─── PAGE TRANSITION (replaces the old tab-pane fade) ─ */
        #elearning-body {
            transition: opacity 0.4s ease, transform 0.4s ease;
            opacity: 1;
            transform: translateY(0);
        }
        #elearning-body.is-leaving,
        #elearning-body.is-entering {
            opacity: 0;
            transform: translateY(12px);
        }
        @media (prefers-reduced-motion: reduce) {
            #elearning-body { transition: none; }
        }

        /* ─── RESPONSIVE ──────────────────────────────────── */
        @media (max-width: 767px) {
            .el-section { padding: 50px 0; }
            .el-heading { font-size: 1.8rem; }
            .el-stat-number { font-size: 2rem; }
            .el-pricing-price { font-size: 2.2rem; }
            .el-hero-search { flex-direction: column; }
            .el-hero-search input { border-bottom: 1px solid rgba(255,255,255,0.15); }
        }
    </style>

    {{-- Hero only renders on the landing route (/elearning) --}}
    @if(request()->routeIs('elearning.index'))
        @include('elearning::partials.hero')
    @endif

    {{-- Tab navigation persists across every elearning page except the hero landing page --}}
    @unless(request()->routeIs('elearning.index'))
        @include('elearning::partials.tab-bar')
    @endunless

    {{-- Swappable region: this is what the transition script fades and replaces --}}
    <div id="elearning-body">
        @yield('elearning-body')
    </div>

    @include('elearning::partials.final-cta')
    @include('partials.footer')

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            
            // ─── Hide Instructors link from core navbar ───
            document.querySelectorAll('.elearning-dropdown-menu a').forEach(function(link) {
                if (link.getAttribute('href') && link.getAttribute('href').includes('/elearning/instructors')) {
                    link.style.setProperty('display', 'none', 'important');
                }
            });

            // ─── FAQ accordion (delegated so it survives content swaps) ───
            document.addEventListener('click', function (e) {
                const btn = e.target.closest('.el-faq-question');
                if (!btn) return;

                const answer = btn.nextElementSibling;
                const isOpen = answer.classList.contains('open');

                document.querySelectorAll('.el-faq-answer').forEach(a => a.classList.remove('open'));
                document.querySelectorAll('.el-faq-question').forEach(q => q.classList.remove('active'));

                if (!isOpen) {
                    answer.classList.add('open');
                    btn.classList.add('active');
                }
            });

            // ─── Smooth page-to-page transitions between elearning pages ───
            const body = document.getElementById('elearning-body');
            const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            let isNavigating = false;

            async function navigate(url, pushState) {
                if (!body || isNavigating) { window.location.href = url; return; }
                isNavigating = true;

                if (!prefersReducedMotion) body.classList.add('is-leaving');

                let html;
                try {
                    const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                    if (!res.ok) throw new Error('Navigation request failed');
                    html = await res.text();
                } catch (err) {
                    window.location.href = url; // fall back to a real navigation
                    return;
                }

                const swap = function () {
                    const doc = new DOMParser().parseFromString(html, 'text/html');
                    const newBody = doc.getElementById('elearning-body');
                    const newTabBar = doc.querySelector('.el-tab-bar-wrap');
                    const newTitle = doc.querySelector('title');

                    if (newBody) document.getElementById('elearning-body').innerHTML = newBody.innerHTML;

                    const currentTabBar = document.querySelector('.el-tab-bar-wrap');
                    if (newTabBar && currentTabBar) {
                        currentTabBar.outerHTML = newTabBar.outerHTML;
                    } else if (newTabBar && !currentTabBar) {
                        document.getElementById('elearning-body').insertAdjacentHTML('beforebegin', newTabBar.outerHTML);
                    } else if (!newTabBar && currentTabBar) {
                        currentTabBar.remove();
                    }

                    if (newTitle) document.title = newTitle.textContent;
                    if (pushState) window.history.pushState({}, '', url);

                    const freshBody = document.getElementById('elearning-body');
                    if (!prefersReducedMotion) {
                        freshBody.classList.add('is-entering');
                        requestAnimationFrame(function () {
                            requestAnimationFrame(function () {
                                freshBody.classList.remove('is-entering');
                                freshBody.classList.remove('is-leaving');
                            });
                        });
                    }

                    const tabBarEl = document.querySelector('.el-tab-bar-wrap');
                    if (tabBarEl) {
                        window.scrollTo({ top: tabBarEl.offsetTop, behavior: prefersReducedMotion ? 'auto' : 'smooth' });
                    }

                    isNavigating = false;
                };

                if (prefersReducedMotion) {
                    swap();
                } else {
                    setTimeout(swap, 400); // matches the CSS transition duration
                }
            }

            document.addEventListener('click', function (e) {
                const link = e.target.closest('.el-tab-bar a.nav-link');
                if (!link) return;

                const href = link.getAttribute('href');
                const isSameOrigin = href && (href.startsWith('/') || href.startsWith(window.location.origin));
                if (!isSameOrigin) return;

                e.preventDefault();
                navigate(href, true);
            });

            window.addEventListener('popstate', function () {
                navigate(window.location.href, false);
            });
        });
    </script>
@endsection