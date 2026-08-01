@extends('layouts.app')

@section('content')
    @include('partials.navbar')

    {{-- ═══════════════════════════════════════════════════
         HERO SECTION  (teal background)
    ═══════════════════════════════════════════════════ --}}
    <section class="assessment-hero">
        <div class="assessment-hero-inner">

            {{-- Left: text content --}}
            <div class="assessment-hero-text">
                <p class="assessment-pill">MENTAL WELLBEING</p>
                <h1 class="assessment-heading">
                    Personality &amp;<br>Wellbeing Assessment
                </h1>
                <p class="assessment-sub">
                    Discover your personality type and gain deeper insight into your
                    mental wellbeing. Enhanced with multiple dimensions for greater accuracy.
                </p>
                <button onclick="document.getElementById('assessmentModal').classList.add('active')" class="btn-assessment-cta">Take the Assessment</button>

                <div class="assessment-stats">
                    <div class="stat-item">
                        <i class="bi bi-people-fill"></i>
                        <span><strong>12,480</strong> assessments taken</span>
                    </div>
                    <div class="stat-item">
                        <i class="bi bi-bar-chart-fill"></i>
                        <span>Most common type: <a href="#">INFP</a></span>
                    </div>
                    <div class="stat-item">
                        <i class="bi bi-patch-check-fill"></i>
                        <span><strong>91.4%</strong> results rated as accurate</span>
                    </div>
                </div>
            </div>

            {{-- Right: animation --}}
            <div class="assessment-hero-visual">
                <iframe src="https://animationwerta.netlify.app/"
                        frameborder="0"
                        scrolling="no"
                        allowtransparency="true">
                </iframe>
            </div>

        </div>

        {{-- Wave divider --}}
        <div class="wave-divider">
            <svg viewBox="0 0 1440 90" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
                <path d="M0,40 C240,90 480,0 720,45 C960,90 1200,10 1440,50 L1440,90 L0,90 Z" fill="var(--cream)"/>
            </svg>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════
         FEATURES SECTION  (cream background)
    ═══════════════════════════════════════════════════ --}}
    <section class="assessment-features">
        <div class="assessment-features-inner">

            <p class="pill-label" style="text-align:center; margin-bottom:0.6rem;">WHY TAKE IT</p>
            <h2 class="features-heading">
                Fuel your personal growth with<br>Werta's Assessment
            </h2>

            {{-- Feature row 1 --}}
            <div class="feature-row">
                <div class="feature-text">
                    <h3>Deepen your self-awareness</h3>
                    <p>
                        Dive deeper into your personality and discover your strengths and weaknesses,
                        motivations, natural talents, and more with a comprehensive Werta profile.
                    </p>
                </div>
                <div class="feature-visual">
                    <img src="{{ asset('images/photo1.png') }}" alt="Deepen self-awareness" class="feature-photo">
                </div>
            </div>

            <hr class="section-divider" style="margin: 3rem 0;">

            {{-- Feature row 2 --}}
            <div class="feature-row feature-row-reverse">
                <div class="feature-visual">
                    <img src="{{ asset('images/photo2.png') }}" alt="Understand yourself and others" class="feature-photo">
                </div>
                <div class="feature-text">
                    <h3>Understand yourself and others</h3>
                    <p>
                        Fast-track your self-discovery journey and forge deeper connections with others
                        using our toolkit, which contains 25+ specialized personal growth, relationship,
                        and personality assessments.
                    </p>
                </div>
            </div>

            <hr class="section-divider" style="margin: 3rem 0;">

            {{-- Feature row 3 --}}
            <div class="feature-row">
                <div class="feature-text">
                    <h3>Track your wellbeing over time</h3>
                    <p>
                        Monitor changes in your mental wellbeing across multiple sessions.
                        Werta keeps a history so you can see how far you've come on your wellness journey.
                    </p>
                </div>
                <div class="feature-visual">
                    <img src="{{ asset('images/photo3.png') }}" alt="Track your wellbeing over time" class="feature-photo">
                </div>
            </div>

        </div>
    </section>

    @include('partials.footer')

    {{-- ═══════════════════════════════════════════════════
         ASSESSMENT MODAL
    ═══════════════════════════════════════════════════ --}}
    <div id="assessmentModal" class="modal-backdrop">
        <div class="modal-card">

            {{-- Decorative bubbles --}}
            <div class="bubble b1"></div>
            <div class="bubble b2"></div>
            <div class="bubble b3"></div>
            <div class="bubble b4"></div>
            <div class="bubble b5"></div>
            <div class="bubble b6"></div>

            {{-- Logo --}}
            <div class="modal-logo">
                <img src="{{ asset('images/Werta_Logo.png') }}" alt="Werta" style="height:48px; width:auto;">
                <span style="display:flex; align-items:baseline; gap:1px;">
                    <span style="font-family:'Great Vibes',cursive; font-size:2rem; color:var(--gold); line-height:1;">W</span>
                    <span style="font-family:'Lato',sans-serif; font-size:1rem; font-weight:700; letter-spacing:4px; color:var(--primary);">ERTA</span>
                </span>
            </div>

            {{-- Title & description --}}
            <h2 class="modal-title">Personal Wellbeing Assessment</h2>
            <p class="modal-desc">
                This brief questionnaire helps us understand your current emotional state.
                It takes about 5-10 minutes to complete. Your responses are strictly confidential
                and will be used to personalize your care journey.
            </p>

            {{-- 3 info cards --}}
            <div class="modal-cards">
                <div class="modal-card-item">
                    <div class="modal-card-icon">
                        <i class="bi bi-list-ol"></i>
                    </div>
                    <p class="modal-card-title">30 questions</p>
                    <p class="modal-card-sub">From different dimensions</p>
                </div>
                <div class="modal-card-item">
                    <div class="modal-card-icon">
                        <i class="bi bi-check2-circle"></i>
                    </div>
                    <p class="modal-card-title">Select the answer</p>
                    <p class="modal-card-sub">That best describes you</p>
                </div>
                <div class="modal-card-item">
                    <div class="modal-card-icon">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <p class="modal-card-title">Take your time</p>
                    <p class="modal-card-sub">In a quiet environment</p>
                </div>
            </div>

            {{-- Actions --}}
            <div class="modal-actions">
                <button onclick="document.getElementById('assessmentModal').classList.remove('active')" class="btn-modal-cancel">
                    Cancel
                </button>
                <a href="{{ url('/assessment/questions') }}" class="btn-modal-begin">
                    Begin Assessment
                </a>
            </div>

        </div>
    </div>

    <style>
        /* ── HERO ─────────────────────────────────────────── */
        .assessment-hero {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 60%, var(--gold) 100%);
            position: relative;
            padding: 80px 0 0;
        }

        .assessment-hero-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem 80px;
            display: flex;
            align-items: center;
            gap: 3rem;
        }

        .assessment-hero-text {
            flex: 1;
            min-width: 0;
        }

        /* ── HERO VISUAL (animation) ──────────────────────── */
        .assessment-hero-visual {
            flex: 1.6;
            min-width: 0;
            display: flex;
            align-items: flex-start;
            justify-content: center;
        }

        .assessment-hero-visual iframe {
            width: 100%;
            height: 520px;
            border-radius: 16px;
            background: transparent;
        }

        .assessment-pill {
            display: inline-block;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 3px;
            color: rgba(255,255,255,0.85);
            background: rgba(255,255,255,0.15);
            padding: 0.3rem 0.9rem;
            border-radius: 20px;
            margin-bottom: 1.2rem;
        }

        .assessment-heading {
            font-family: 'IM Fell English', serif;
            font-size: 2.8rem;
            line-height: 1.2;
            color: #ffffff;
            margin-bottom: 1rem;
        }

        .assessment-sub {
            font-size: 1rem;
            color: rgba(255,255,255,0.88);
            line-height: 1.7;
            max-width: 480px;
            margin-bottom: 2rem;
        }

        .btn-assessment-cta {
            display: inline-block;
            background: var(--primary-dark);
            color: #fff;
            font-weight: 700;
            font-size: 0.95rem;
            padding: 0.85rem 2.2rem;
            border-radius: 6px;
            text-decoration: none;
            transition: background 0.2s;
            margin-bottom: 2rem;
        }

        .btn-assessment-cta:hover {
            background: var(--dark);
            color: #fff;
        }

        .assessment-stats {
            display: flex;
            flex-direction: column;
            gap: 0.65rem;
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            color: rgba(255,255,255,0.9);
            font-size: 0.88rem;
        }

        .stat-item i {
            font-size: 1rem;
            color: #fff;
        }

        .stat-item a {
            color: #fff;
            font-weight: 700;
            text-decoration: underline;
        }


        /* ── WAVE DIVIDER ─────────────────────────────────── */
        .wave-divider {
            line-height: 0;
            overflow: hidden;
        }

        .wave-divider svg {
            display: block;
            width: 100%;
            height: 90px;
        }

        /* ── FEATURES SECTION ─────────────────────────────── */
        .assessment-features {
            background: var(--cream);
            padding: 70px 0 80px;
        }

        .assessment-features-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .features-heading {
            font-family: 'IM Fell English', serif;
            font-size: 2rem;
            color: #3AACB8;
            text-align: center;
            margin-bottom: 3.5rem;
            line-height: 1.3;
        }

        .feature-row {
            display: flex;
            align-items: center;
            gap: 4rem;
        }

        .feature-row-reverse {
            flex-direction: row-reverse;
        }

        .feature-text {
            flex: 1;
        }

        .feature-text h3 {
            font-family: 'IM Fell English', serif;
            font-size: 1.4rem;
            color: var(--primary-dark);
            margin-bottom: 0.8rem;
            text-decoration: underline;
            text-underline-offset: 4px;
        }

        .feature-text p {
            font-size: 0.95rem;
            color: var(--muted);
            line-height: 1.75;
            max-width: 420px;
        }

        /* ── FEATURE PHOTOS ───────────────────────────────── */
        .feature-visual {
            flex: 0 0 220px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .feature-photo {
            width: 220px;
            height: 220px;
            object-fit: cover;
            border-radius: 50%;
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
        }

        /* ── MODAL ────────────────────────────────────────── */
        .modal-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(44,36,22,0.5);
            z-index: 2000;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .modal-backdrop.active {
            display: flex;
        }

        .modal-card {
            background: var(--cream-light);
            border-radius: 20px;
            padding: 2.5rem 2.5rem 2rem;
            max-width: 560px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        /* ── Bubbles ──────────────────────────────────────── */
        .bubble {
            position: absolute;
            border-radius: 50%;
            opacity: 0.18;
            animation: bubbleFloat var(--dur) ease-in-out infinite alternate;
        }

        .b1 { width:90px;  height:90px;  background:var(--gold);         top:-20px;  left:-20px;  --dur:3.8s; }
        .b2 { width:55px;  height:55px;  background:var(--primary);      top:30px;   right:10px;  --dur:4.5s; animation-delay:.8s; }
        .b3 { width:120px; height:120px; background:var(--gold);         bottom:-30px; right:-30px; --dur:5s;  animation-delay:.3s; }
        .b4 { width:40px;  height:40px;  background:var(--primary-dark); bottom:60px; left:10px;  --dur:3.2s; animation-delay:1.2s; }
        .b5 { width:65px;  height:65px;  background:var(--primary);      top:45%;    left:-15px;  --dur:4.1s; animation-delay:.5s; }
        .b6 { width:35px;  height:35px;  background:var(--gold);         top:20%;    right:30px;  --dur:3.6s; animation-delay:1.5s; }

        @keyframes bubbleFloat {
            from { transform: translateY(0px) scale(1); }
            to   { transform: translateY(-14px) scale(1.06); }
        }

        .modal-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-bottom: 1.5rem;
        }

        .modal-title {
            font-family: 'IM Fell English', serif;
            font-size: 1.7rem;
            color: var(--dark);
            margin-bottom: 0.9rem;
        }

        .modal-desc {
            font-size: 0.92rem;
            color: var(--muted);
            line-height: 1.7;
            margin-bottom: 1.8rem;
        }

        /* 3 info cards */
        .modal-cards {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .modal-card-item {
            flex: 1;
            background: var(--cream);
            border-radius: 12px;
            padding: 1.2rem 0.8rem;
            border: 1px solid rgba(123,107,53,0.12);
        }

        .modal-card-icon {
            width: 48px;
            height: 48px;
            background: var(--primary);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.75rem;
        }

        .modal-card-icon i {
            font-size: 1.4rem;
            color: #fff;
        }

        .modal-card-title {
            font-weight: 700;
            font-size: 0.9rem;
            color: var(--dark);
            margin-bottom: 0.2rem;
        }

        .modal-card-sub {
            font-size: 0.78rem;
            color: var(--muted);
            margin: 0;
        }

        /* Buttons */
        .modal-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        .btn-modal-cancel {
            background: transparent;
            border: 1.5px solid rgba(123,107,53,0.3);
            color: var(--muted);
            padding: 0.7rem 1.8rem;
            border-radius: 8px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-modal-cancel:hover {
            border-color: var(--primary);
            color: var(--primary);
        }

        .btn-modal-begin {
            background: var(--primary);
            color: #fff;
            border: none;
            padding: 0.7rem 1.8rem;
            border-radius: 8px;
            font-size: 0.95rem;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: background 0.2s;
        }

        .btn-modal-begin:hover {
            background: var(--primary-dark);
            color: #fff;
        }
    </style>
@endsection
