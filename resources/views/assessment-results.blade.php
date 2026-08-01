@extends('layouts.app')

@section('content')
    @include('partials.navbar')

    <div class="results-wrapper">

        {{-- ── Heading ── --}}
        <div class="results-heading-wrap">
            <h1 class="results-heading">
                Your Wellbeing Report <span class="heading-highlight">Is Ready</span>
            </h1>
            <p class="results-sub">Your responses have been analyzed across 6 dimensions.</p>
        </div>

        {{-- ── Teaser card ── --}}
        <div class="teaser-card">

            {{-- Left: personality type --}}
            <div class="teaser-left">
                <p class="teaser-label">Your Personality Type</p>
                <div class="personality-type" id="personalityType">??–??</div>
                <div class="personality-desc blurred" id="personalityDesc">
                    You are a thoughtful and empathetic individual with a strong sense of
                    personal values. You draw energy from meaningful connections and tend
                    to approach challenges with creativity and care.
                </div>
            </div>

            {{-- Right: score bars + unlock --}}
            <div class="teaser-right">
                <div class="score-bars blurred" id="scoreBars">
                    <div class="score-bar-item" data-key="social">
                        <span class="bar-label">Social & Energy</span>
                        <div class="bar-track"><div class="bar-fill" style="width:0%"></div></div>
                        <span class="bar-pct">–</span>
                    </div>
                    <div class="score-bar-item" data-key="thinking">
                        <span class="bar-label">Thinking & Decision Making</span>
                        <div class="bar-track"><div class="bar-fill" style="width:0%"></div></div>
                        <span class="bar-pct">–</span>
                    </div>
                    <div class="score-bar-item" data-key="structure">
                        <span class="bar-label">Structure & Lifestyle</span>
                        <div class="bar-track"><div class="bar-fill" style="width:0%"></div></div>
                        <span class="bar-pct">–</span>
                    </div>
                    <div class="score-bar-item" data-key="wellbeing">
                        <span class="bar-label">Emotional Wellbeing</span>
                        <div class="bar-track"><div class="bar-fill" style="width:0%"></div></div>
                        <span class="bar-pct">–</span>
                    </div>
                    <div class="score-bar-item" data-key="stress">
                        <span class="bar-label">Stress & Coping</span>
                        <div class="bar-track"><div class="bar-fill" style="width:0%"></div></div>
                        <span class="bar-pct">–</span>
                    </div>
                    <div class="score-bar-item" data-key="growth">
                        <span class="bar-label">Self-Awareness & Growth</span>
                        <div class="bar-track"><div class="bar-fill" style="width:0%"></div></div>
                        <span class="bar-pct">–</span>
                    </div>
                </div>

                {{-- Unlock CTA --}}
                <div class="unlock-box">
                    <p class="unlock-tag">YOUR FULL REPORT IS LOCKED</p>
                    <p class="unlock-desc">Unlock your complete personality breakdown, detailed wellbeing scores, and personalized growth plan.</p>
                    <a href="#" class="btn-unlock">
                        <i class="bi bi-lock-fill"></i> Unlock Full Report
                    </a>
                </div>
            </div>

        </div>

        {{-- ── Summary teaser ── --}}
        <div class="summary-card">
            <div class="summary-header">
                <i class="bi bi-file-earmark-person"></i>
                <h3>Your Emotional Landscape Summary</h3>
            </div>
            <div class="summary-body blurred" id="summaryBody">
                <p id="summaryText">
                    Based on your responses, you demonstrate a balanced emotional profile with notable
                    strengths in self-awareness and interpersonal connection. Your wellbeing scores
                    suggest a grounded sense of purpose, though there are areas — particularly around
                    stress management — where focused attention could significantly improve your
                    day-to-day quality of life. You thrive in structured environments but maintain
                    the flexibility to adapt when needed. Your growth mindset positions you well for
                    continued personal development.
                </p>
            </div>
            <div class="summary-blur-cta">
                <i class="bi bi-lock-fill"></i>
                <span>Unlock your full summary — <a href="#">Get Full Report</a></span>
            </div>
        </div>

    </div>

    <style>
        body { background: var(--cream); }

        .results-wrapper {
            max-width: 1000px;
            margin: 0 auto;
            padding: 3rem 1.5rem 5rem;
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        /* ── Heading ── */
        .results-heading-wrap { text-align: center; }

        .results-heading {
            font-family: 'IM Fell English', serif;
            font-size: 2.2rem;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }

        .heading-highlight { color: var(--primary); }

        .results-sub { font-size: 0.92rem; color: var(--muted); }

        /* ── Teaser card ── */
        .teaser-card {
            background: var(--cream-light);
            border-radius: 20px;
            border: 1px solid rgba(123,107,53,0.12);
            box-shadow: 0 8px 32px rgba(0,0,0,0.08);
            display: flex;
            overflow: hidden;
        }

        .teaser-left {
            flex: 0 0 280px;
            padding: 2.5rem;
            border-right: 1px solid rgba(123,107,53,0.1);
        }

        .teaser-label {
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 0.8rem;
        }

        .personality-type {
            font-family: 'IM Fell English', serif;
            font-size: 3.5rem;
            color: var(--primary);
            margin-bottom: 1rem;
            letter-spacing: 2px;
        }

        .personality-desc {
            font-size: 0.88rem;
            color: var(--muted);
            line-height: 1.7;
        }

        .teaser-right {
            flex: 1;
            padding: 2.5rem;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        /* ── Score bars ── */
        .score-bars {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .score-bar-item {
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .bar-label {
            font-size: 0.78rem;
            color: var(--muted);
            font-weight: 600;
            flex: 0 0 170px;
        }

        .bar-track {
            flex: 1;
            height: 8px;
            background: rgba(123,107,53,0.1);
            border-radius: 99px;
            overflow: hidden;
        }

        .bar-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--primary), var(--gold));
            border-radius: 99px;
            transition: width 1s ease;
        }

        .bar-pct {
            font-size: 0.78rem;
            font-weight: 700;
            color: var(--primary);
            flex: 0 0 32px;
            text-align: right;
        }

        /* ── Blur ── */
        .blurred {
            filter: blur(5px);
            user-select: none;
            pointer-events: none;
        }

        /* ── Unlock box ── */
        .unlock-box {
            background: var(--primary);
            border-radius: 14px;
            padding: 1.4rem 1.6rem;
            text-align: center;
        }

        .unlock-tag {
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 2px;
            color: rgba(255,255,255,0.75);
            margin-bottom: 0.5rem;
        }

        .unlock-desc {
            font-size: 0.85rem;
            color: rgba(255,255,255,0.9);
            margin-bottom: 1rem;
            line-height: 1.5;
        }

        .btn-unlock {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--gold);
            color: #fff;
            font-weight: 700;
            font-size: 0.92rem;
            padding: 0.7rem 1.8rem;
            border-radius: 8px;
            text-decoration: none;
            transition: background 0.2s;
        }

        .btn-unlock:hover { background: #a88a20; color: #fff; }

        /* ── Summary card ── */
        .summary-card {
            background: var(--cream-light);
            border-radius: 20px;
            border: 1px solid rgba(123,107,53,0.12);
            box-shadow: 0 8px 32px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        .summary-header {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            padding: 1.4rem 2rem;
            border-bottom: 1px solid rgba(123,107,53,0.1);
            background: rgba(123,107,53,0.04);
        }

        .summary-header i { font-size: 1.3rem; color: var(--gold); }

        .summary-header h3 {
            font-family: 'IM Fell English', serif;
            font-size: 1.1rem;
            color: var(--dark);
        }

        .summary-body {
            padding: 1.8rem 2rem;
            font-size: 0.95rem;
            color: var(--muted);
            line-height: 1.8;
        }

        .summary-blur-cta {
            padding: 1rem 2rem 1.4rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.85rem;
            color: var(--muted);
        }

        .summary-blur-cta i { color: var(--gold); }
        .summary-blur-cta a { color: var(--primary); font-weight: 700; }
    </style>

    <script>
        const raw = localStorage.getItem('werta_scores');
        if (!raw) { window.location.href = '/assessment'; }

        const scores = JSON.parse(raw);

        // Fill score bars
        document.querySelectorAll('.score-bar-item').forEach(item => {
            const key = item.dataset.key;
            if (scores[key]) {
                const pct = scores[key].pct;
                item.querySelector('.bar-fill').style.width = pct + '%';
                item.querySelector('.bar-pct').textContent = pct + '%';
            }
        });

        // Determine personality type (4 dimensions)
        const social    = scores.social?.pct    ?? 50;
        const thinking  = scores.thinking?.pct  ?? 50;
        const structure = scores.structure?.pct ?? 50;
        const wellbeing = scores.wellbeing?.pct ?? 50;

        const dim1 = social    >= 50 ? 'E' : 'I';
        const dim2 = thinking  >= 50 ? 'T' : 'F';
        const dim3 = structure >= 50 ? 'J' : 'P';
        const dim4 = wellbeing >= 50 ? 'S' : 'N';

        document.getElementById('personalityType').textContent = dim1 + dim2 + '–' + dim3 + dim4;
    </script>

@endsection
