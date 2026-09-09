@extends('layouts.app')

@section('content')
    @include('partials.navbar')

    @php
        // ═══════════════════════════════════════════════════════════════
        // DEV PREVIEW MODE — set to false to enforce public/production gating.
        // While true, every gated section below renders in full for review
        // purposes, ignoring whatever real unlock/purchase state would
        // normally apply. Search this file for "DEV PREVIEW" to find every
        // place this flag is checked.
        //
        // Before going live: set this to false (or replace with a real
        // ->hasUnlockedReport() / subscription check once that logic
        // exists), then verify each gated block below falls back to its
        // correct locked/public presentation.
        // ═══════════════════════════════════════════════════════════════
        $devPreviewUnlockAll = true;
    @endphp

    <div class="results-wrapper">

        {{-- ── Heading ── --}}
        <div class="results-heading-wrap">
            <h1 class="results-heading">
                Your Wellbeing Report <span class="heading-highlight">Is Ready</span>
            </h1>
            <p class="results-sub">Here's what we found — and how we can help.</p>
        </div>

        {{-- ── Core Results Card ── --}}
        <div class="teaser-card">

            {{-- Left: personality summary (hero label + secondary code) --}}
            <div class="teaser-left">
                <p class="teaser-label">Your Profile Archetype</p>
                <h2 class="personality-hero" id="personalityHero">The Structured Connector</h2>
                <div class="personality-code-wrap">
                    <span class="personality-code" id="personalityType">Profile Pattern: ET–JS</span>
                </div>
                <div class="personality-desc" id="personalityDesc">
                    You are a thoughtful and empathetic individual with a strong sense of
                    personal values. You draw energy from meaningful connections and tend
                    to approach challenges with creativity and care.
                </div>
            </div>

            {{-- Right: dimension score bars (always fully visible, no blur) --}}
            <div class="teaser-right">
                <div class="score-bars" id="scoreBars">
                    <div class="score-bar-item" data-key="social">
                        <div class="bar-header">
                            <span class="bar-label">Social &amp; Energy</span>
                            <span class="bar-pct">–</span>
                        </div>
                        <div class="bar-track"><div class="bar-fill" style="width:0%"></div></div>
                        <p class="dimension-context">{{ $dimensions['social']['context'] ?? 'Reflects how you navigate social environments and recharge your mental energy.' }}</p>
                    </div>
                    <div class="score-bar-item" data-key="thinking">
                        <div class="bar-header">
                            <span class="bar-label">Thinking &amp; Decision Making</span>
                            <span class="bar-pct">–</span>
                        </div>
                        <div class="bar-track"><div class="bar-fill" style="width:0%"></div></div>
                        <p class="dimension-context">{{ $dimensions['thinking']['context'] ?? 'Shows your balance between objective analytical reasoning and values-based intuition.' }}</p>
                    </div>
                    <div class="score-bar-item" data-key="structure">
                        <div class="bar-header">
                            <span class="bar-label">Structure &amp; Lifestyle</span>
                            <span class="bar-pct">–</span>
                        </div>
                        <div class="bar-track"><div class="bar-fill" style="width:0%"></div></div>
                        <p class="dimension-context">{{ $dimensions['structure']['context'] ?? 'Indicates your preferred balance between organized planning and adaptable flexibility.' }}</p>
                    </div>
                    <div class="score-bar-item" data-key="wellbeing">
                        <div class="bar-header">
                            <span class="bar-label">Emotional Wellbeing</span>
                            <span class="bar-pct">–</span>
                        </div>
                        <div class="bar-track"><div class="bar-fill" style="width:0%"></div></div>
                        <p class="dimension-context">{{ $dimensions['wellbeing']['context'] ?? 'Reflects your overall sense of purpose, daily emotional balance, and outlook.' }}</p>
                    </div>
                    <div class="score-bar-item" data-key="stress">
                        <div class="bar-header">
                            <span class="bar-label">Stress &amp; Coping</span>
                            <span class="bar-pct">–</span>
                        </div>
                        <div class="bar-track"><div class="bar-fill" style="width:0%"></div></div>
                        <p class="dimension-context">{{ $dimensions['stress']['context'] ?? 'Highlights your resilience strategies and how you manage challenging pressures.' }}</p>
                    </div>
                    <div class="score-bar-item" data-key="growth">
                        <div class="bar-header">
                            <span class="bar-label">Self-Awareness &amp; Growth</span>
                            <span class="bar-pct">–</span>
                        </div>
                        <div class="bar-track"><div class="bar-fill" style="width:0%"></div></div>
                        <p class="dimension-context">{{ $dimensions['growth']['context'] ?? 'Demonstrates your commitment to self-reflection and personal development.' }}</p>
                    </div>
                </div>
            </div>

        </div>

        {{-- ── Disclaimer Line (Always visible, non-gated) ── --}}
        <p class="results-disclaimer">
            <i class="bi bi-info-circle"></i> This is a self-reflection tool, not a clinical diagnosis. Full results are best discussed with a counselor.
        </p>

        {{-- ── Emotional Landscape Summary (Partially Gated) ── --}}
        <div class="summary-card">
            <div class="summary-header">
                <i class="bi bi-file-earmark-person"></i>
                <h3>Your Emotional Landscape Summary</h3>
            </div>
            <div class="summary-body" id="summaryBody">
                {{-- Always-visible opening: general, safe framing --}}
                <p class="summary-lead">
                    Based on your responses, you demonstrate a balanced emotional profile with notable
                    strengths in self-awareness and interpersonal connection. Your wellbeing scores
                    suggest a grounded sense of purpose, reflecting steady personal values and a thoughtful
                    approach to your relationships and everyday commitments.
                </p>

                {{-- DEV PREVIEW: gated in production, see $devPreviewUnlockAll above --}}
                @if($devPreviewUnlockAll || ($userHasUnlockedReport ?? false))
                    {{-- Unlocked detailed behavioral analysis --}}
                    <p class="summary-detail">
                        In high-pressure situations, you lean toward introspective problem-solving before seeking
                        external validation. While this provides clarity and composure, proactive boundary setting
                        and structured stress-recovery routines will further protect your emotional equilibrium.
                        Your growth mindset positions you exceptionally well for productive counseling or self-guided
                        resilience practices.
                    </p>
                @else
                    {{-- Gated teaser shown in production when report is locked --}}
                    <div class="summary-gated-teaser">
                        <p class="summary-teaser-faded">
                            In high-pressure situations, your responses reflect distinct behavioral tendencies that
                            benefit from structured coping mechanisms and targeted communication practices...
                        </p>
                        <div class="summary-blur-cta">
                            <i class="bi bi-lock-fill"></i>
                            <span>Unlock your full behavioral analysis — <a href="#nextSteps">Get Full Report</a></span>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- ── Next Steps CTA Band ── --}}
        <div class="next-steps-card" id="nextSteps">
            <div class="next-steps-header">
                <h2 class="next-steps-title">Ready for the Next Step?</h2>
                <p class="next-steps-sub">
                    Your results offer valuable insights into your wellbeing. Connect with an experienced professional to explore your results in depth, or access your complete personalized breakdown.
                </p>
            </div>

            <div class="next-steps-buttons">
                {{-- Primary CTA: Talk to a Counselor (Filled gold) --}}
                {{-- TODO: Deep-link to pre-filtered counselors based on lowest-scoring dimension once matching logic exists --}}
                <a href="{{ url('/counselors') }}" class="btn-cta-primary">
                    <i class="bi bi-person-check-fill"></i> Talk to a Counselor
                </a>

                {{-- Secondary CTA: Get Your Full Written Report (Outlined) --}}
                {{-- TODO: Connect to real report checkout / download route when built --}}
                <a href="#writtenReportSection" class="btn-cta-secondary" id="btnGetReport">
                    <i class="bi bi-file-earmark-text"></i> Get Your Full Written Report
                </a>
            </div>

            {{-- DEV PREVIEW: gated in production, see $devPreviewUnlockAll above --}}
            @if($devPreviewUnlockAll || ($userHasUnlockedReport ?? false))
                {{-- Unlocked Full Written Report Preview --}}
                <div class="written-report-section" id="writtenReportSection">
                    <div class="written-report-card">
                        <div class="written-report-top">
                            <div class="written-report-badge">
                                <i class="bi bi-check2-circle"></i> Unlocked Preview
                            </div>
                            {{-- Note: Full written report content / generation route to be built --}}
                            <span class="written-report-tag">Full Written Report &amp; Action Plan</span>
                        </div>
                        <h3 class="written-report-title">Comprehensive Dimension Breakdown</h3>
                        <p class="written-report-intro">
                            Here is an initial preview of your personalized growth recommendations and tailored micro-habits based on your complete assessment profile.
                        </p>

                        <div class="written-report-grid">
                            <div class="report-box">
                                <div class="report-box-icon"><i class="bi bi-lightning-charge-fill"></i></div>
                                <h4>Identified Strengths</h4>
                                <p>Strong self-reflection habits and organized task orientation. You respond thoughtfully rather than impulsively, offering stability to those around you.</p>
                            </div>
                            <div class="report-box">
                                <div class="report-box-icon"><i class="bi bi-shield-heart-fill"></i></div>
                                <h4>Growth Focus Areas</h4>
                                <p>Energy replenishment and stress resilience. Introducing intentional decompression breaks after intense social or cognitive tasks will prevent burnout.</p>
                            </div>
                            <div class="report-box">
                                <div class="report-box-icon"><i class="bi bi-compass-fill"></i></div>
                                <h4>Counselor Discussion Guide</h4>
                                <p>Consider exploring tailored boundary-setting strategies and stress reframing tools with your counselor to optimize your daily wellbeing.</p>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                {{-- Locked Teaser version for production --}}
                <div class="written-report-teaser">
                    <div class="locked-icon-wrap"><i class="bi bi-lock-fill"></i></div>
                    <p class="locked-teaser-text">
                        <strong>Full Written Report &amp; Action Plan</strong> is locked. Unlock to view detailed dimension deep-dives, personalized growth tools, and counselor discussion prompts.
                    </p>
                </div>
            @endif
        </div>

        {{-- ── Safety-Net Note (Always visible, calm & supportive) ── --}}
        <div class="safety-net-card">
            <div class="safety-net-icon">
                <i class="bi bi-heart-pulse-fill"></i>
            </div>
            <div class="safety-net-body">
                <p class="safety-net-text">
                    If you're going through something difficult right now, support is available.
                    <a href="{{ url('/counselors') }}" class="safety-net-link">Talk to a counselor today</a> or
                    {{-- TODO: Connect to dedicated crisis-resources page or external emergency hotline before going live --}}
                    <a href="#" class="safety-net-link">find urgent support resources</a>.
                </p>
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

        .results-sub { font-size: 0.95rem; color: var(--muted); }

        /* ── Teaser / Core Results Card ── */
        .teaser-card {
            background: var(--cream-light);
            border-radius: 20px;
            border: 1px solid rgba(123,107,53,0.12);
            box-shadow: 0 8px 32px rgba(0,0,0,0.08);
            display: flex;
            overflow: hidden;
        }

        .teaser-left {
            flex: 0 0 320px;
            padding: 2.5rem;
            border-right: 1px solid rgba(123,107,53,0.1);
            display: flex;
            flex-direction: column;
        }

        .teaser-label {
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 0.6rem;
        }

        .personality-hero {
            font-family: 'IM Fell English', serif;
            font-size: 2.1rem;
            color: var(--primary);
            margin-bottom: 0.4rem;
            line-height: 1.25;
        }

        .personality-code-wrap { margin-bottom: 1.2rem; }

        .personality-code {
            display: inline-block;
            font-size: 0.78rem;
            font-weight: 700;
            color: var(--muted);
            letter-spacing: 1.5px;
            text-transform: uppercase;
            background: rgba(123,107,53,0.08);
            padding: 0.2rem 0.65rem;
            border-radius: 6px;
        }

        .personality-desc {
            font-size: 0.9rem;
            color: var(--muted);
            line-height: 1.7;
        }

        .teaser-right {
            flex: 1;
            padding: 2.5rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        /* ── Dimension Score Bars ── */
        .score-bars {
            display: flex;
            flex-direction: column;
            gap: 1.2rem;
        }

        .score-bar-item {
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
        }

        .bar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .bar-label {
            font-size: 0.82rem;
            color: var(--dark);
            font-weight: 600;
        }

        .bar-pct {
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--primary);
        }

        .bar-track {
            width: 100%;
            height: 8px;
            background: rgba(123,107,53,0.12);
            border-radius: 99px;
            overflow: hidden;
        }

        .bar-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--primary), var(--gold));
            border-radius: 99px;
            transition: width 1s ease;
        }

        .dimension-context {
            font-size: 0.76rem;
            color: var(--muted);
            line-height: 1.4;
            margin: 0;
        }

        /* ── Disclaimer ── */
        .results-disclaimer {
            font-size: 0.8rem;
            color: var(--muted);
            text-align: center;
            margin: -0.5rem 0 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .results-disclaimer i { color: var(--gold); }

        /* ── Summary Card ── */
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
            font-size: 1.15rem;
            color: var(--dark);
            margin: 0;
        }

        .summary-body {
            padding: 1.8rem 2rem;
            font-size: 0.95rem;
            color: var(--muted);
            line-height: 1.8;
        }

        .summary-lead { margin-bottom: 1rem; }

        .summary-detail { margin-top: 0.8rem; }

        .summary-gated-teaser {
            margin-top: 1rem;
            padding-top: 0.8rem;
            border-top: 1px dashed rgba(123,107,53,0.2);
        }

        .summary-teaser-faded {
            font-size: 0.95rem;
            color: var(--muted);
            line-height: 1.8;
            opacity: 0.45;
            filter: blur(2px);
            user-select: none;
            margin-bottom: 0.8rem;
        }

        .summary-blur-cta {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.88rem;
            color: var(--muted);
            font-weight: 500;
        }

        .summary-blur-cta i { color: var(--gold); }
        .summary-blur-cta a { color: var(--primary); font-weight: 700; text-decoration: underline; }

        /* ── Next Steps Card ── */
        .next-steps-card {
            background: var(--cream-light);
            border-radius: 20px;
            border: 1px solid rgba(123,107,53,0.12);
            padding: 2.5rem 2.2rem;
            box-shadow: 0 8px 32px rgba(0,0,0,0.06);
            text-align: center;
        }

        .next-steps-title {
            font-family: 'IM Fell English', serif;
            font-size: 1.9rem;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }

        .next-steps-sub {
            font-size: 0.92rem;
            color: var(--muted);
            max-width: 680px;
            margin: 0 auto 1.8rem;
            line-height: 1.6;
        }

        .next-steps-buttons {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 1.2rem;
            flex-wrap: wrap;
        }

        .btn-cta-primary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--gold);
            color: #fff;
            font-weight: 700;
            font-size: 0.95rem;
            padding: 0.8rem 2.2rem;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.2s;
            box-shadow: 0 4px 14px rgba(196,168,64,0.3);
        }

        .btn-cta-primary:hover {
            background: var(--primary);
            color: #fff;
            transform: translateY(-1px);
        }

        .btn-cta-secondary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: transparent;
            border: 1.5px solid var(--primary);
            color: var(--primary);
            font-weight: 700;
            font-size: 0.95rem;
            padding: 0.8rem 2rem;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-cta-secondary:hover {
            background: rgba(123,107,53,0.08);
            color: var(--primary-dark);
            transform: translateY(-1px);
        }

        /* ── Written Report Preview ── */
        .written-report-section {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(123,107,53,0.12);
            text-align: left;
        }

        .written-report-card {
            background: var(--cream);
            border-radius: 14px;
            padding: 1.8rem 2rem;
            border: 1px solid rgba(123,107,53,0.15);
        }

        .written-report-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.8rem;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .written-report-badge {
            font-size: 0.75rem;
            font-weight: 700;
            color: #27ae60;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: rgba(39,174,96,0.1);
            padding: 0.25rem 0.7rem;
            border-radius: 99px;
        }

        .written-report-tag {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--muted);
        }

        .written-report-title {
            font-family: 'IM Fell English', serif;
            font-size: 1.35rem;
            color: var(--dark);
            margin-bottom: 0.4rem;
        }

        .written-report-intro {
            font-size: 0.88rem;
            color: var(--muted);
            margin-bottom: 1.4rem;
            line-height: 1.5;
        }

        .written-report-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1rem;
        }

        .report-box {
            background: var(--cream-light);
            border-radius: 10px;
            padding: 1.2rem;
            border: 1px solid rgba(123,107,53,0.1);
        }

        .report-box-icon {
            font-size: 1.2rem;
            color: var(--gold);
            margin-bottom: 0.5rem;
        }

        .report-box h4 {
            font-size: 0.92rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.4rem;
        }

        .report-box p {
            font-size: 0.82rem;
            color: var(--muted);
            line-height: 1.5;
            margin: 0;
        }

        .written-report-teaser {
            margin-top: 1.8rem;
            padding: 1.2rem 1.6rem;
            background: rgba(123,107,53,0.06);
            border-radius: 12px;
            border: 1px dashed rgba(123,107,53,0.2);
            display: flex;
            align-items: center;
            gap: 1rem;
            text-align: left;
        }

        .locked-icon-wrap {
            font-size: 1.3rem;
            color: var(--gold);
            flex-shrink: 0;
        }

        .locked-teaser-text {
            font-size: 0.85rem;
            color: var(--muted);
            margin: 0;
            line-height: 1.5;
        }

        /* ── Safety-Net Note ── */
        .safety-net-card {
            background: rgba(123,107,53,0.06);
            border-radius: 12px;
            border: 1px solid rgba(123,107,53,0.14);
            padding: 1.1rem 1.6rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .safety-net-icon {
            font-size: 1.4rem;
            color: var(--gold);
            flex-shrink: 0;
        }

        .safety-net-body { flex: 1; }

        .safety-net-text {
            font-size: 0.85rem;
            color: var(--muted);
            margin: 0;
            line-height: 1.5;
        }

        .safety-net-link {
            color: var(--primary);
            font-weight: 600;
            text-decoration: underline;
            transition: color 0.2s;
        }

        .safety-net-link:hover { color: var(--primary-dark); }

        @media (max-width: 768px) {
            .teaser-card { flex-direction: column; }
            .teaser-left { border-right: none; border-bottom: 1px solid rgba(123,107,53,0.1); flex: auto; }
            .next-steps-buttons { flex-direction: column; }
            .btn-cta-primary, .btn-cta-secondary { width: 100%; justify-content: center; }
            .written-report-top { flex-direction: column; align-items: flex-start; }
        }
    </style>

    <script>
        const raw = localStorage.getItem('werta_scores');
        let scores = null;
        try {
            scores = raw ? JSON.parse(raw) : null;
        } catch (e) {
            scores = null;
        }

        if (!scores) {
            @if($devPreviewUnlockAll)
            // In dev preview mode: provide realistic fallback scores if localStorage is empty
            scores = {
                social:    { label: 'Social & Energy', score: 18, pct: 65 },
                thinking:  { label: 'Thinking & Decision Making', score: 21, pct: 80 },
                structure: { label: 'Structure & Lifestyle', score: 19, pct: 70 },
                wellbeing: { label: 'Emotional Wellbeing', score: 16, pct: 55 },
                stress:    { label: 'Stress & Coping', score: 13, pct: 40 },
                growth:    { label: 'Self-Awareness & Growth', score: 22, pct: 85 }
            };
            @else
            window.location.href = '/assessment';
            @endif
        }

        if (scores) {
            // Fill score bars
            document.querySelectorAll('.score-bar-item').forEach(item => {
                const key = item.dataset.key;
                if (scores[key]) {
                    const pct = scores[key].pct;
                    const fill = item.querySelector('.bar-fill');
                    const pctEl = item.querySelector('.bar-pct');
                    if (fill) fill.style.width = pct + '%';
                    if (pctEl) pctEl.textContent = pct + '%';
                }
            });

            // Determine personality archetype (4 dimensions)
            const social    = scores.social?.pct    ?? 50;
            const thinking  = scores.thinking?.pct  ?? 50;
            const structure = scores.structure?.pct ?? 50;
            const wellbeing = scores.wellbeing?.pct ?? 50;

            const dim1 = social    >= 50 ? 'E' : 'I';
            const dim2 = thinking  >= 50 ? 'T' : 'F';
            const dim3 = structure >= 50 ? 'J' : 'P';
            const dim4 = wellbeing >= 50 ? 'S' : 'N';
            const code = `${dim1}${dim2}–${dim3}${dim4}`;

            const archetypes = {
                'ET–JS': 'The Structured Connector',
                'ET–JN': 'The Strategic Organizer',
                'ET–PS': 'The Dynamic Problem-Solver',
                'ET–PN': 'The Adaptive Innovator',
                'EF–JS': 'The Empathetic Harmonizer',
                'EF–JN': 'The Visionary Guide',
                'EF–PS': 'The Caring Free-Spirit',
                'EF–PN': 'The Inspiring Explorer',
                'IT–JS': 'The Grounded Analyst',
                'IT–JN': 'The Quiet Strategist',
                'IT–PS': 'The Practical Realist',
                'IT–PN': 'The Deep Conceptualizer',
                'IF–JS': 'The Dedicated Supporter',
                'IF–JN': 'The Thoughtful Idealist',
                'IF–PS': 'The Gentle Observer',
                'IF–PN': 'The Creative Seeker'
            };

            const heroEl = document.getElementById('personalityHero');
            if (heroEl) {
                heroEl.textContent = archetypes[code] || 'The Mindful Explorer';
            }
            const typeEl = document.getElementById('personalityType');
            if (typeEl) {
                typeEl.textContent = `Profile Pattern: ${code}`;
            }
        }
    </script>

@endsection
