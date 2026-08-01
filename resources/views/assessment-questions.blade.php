@extends('layouts.app')

@section('content')
    @include('partials.navbar')

    <div class="quiz-wrapper">

        {{-- ── Progress bar ── --}}
        <div class="quiz-progress-bar">
            <div class="quiz-progress-inner">
                <span class="progress-label">Section <span id="currentPage">1</span> of 6</span>
                <div class="progress-track">
                    <div class="progress-fill" id="progressFill" style="width:16.6%"></div>
                </div>
                <span class="progress-pct" id="progressPct">17%</span>
            </div>
        </div>

        {{-- ── Quiz card ── --}}
        <div class="quiz-card">

            {{-- ═══════════════════ PAGE 1 ═══════════════════ --}}
            <div class="quiz-page active" id="page-1">
                <p class="quiz-section-pill">Section 1 of 6</p>
                <h2 class="quiz-section-title">Social &amp; Energy</h2>
                <p class="quiz-section-sub">How you interact with the world around you</p>

                <div class="questions-list">
                    @foreach([
                        'I feel energized after spending time with a large group of people.',
                        'I prefer to think out loud rather than reflect quietly before speaking.',
                        'I enjoy meeting new people and starting conversations.',
                        'I find social gatherings exciting rather than draining.',
                        'I tend to have a wide circle of friends rather than a few close ones.',
                    ] as $i => $q)
                    <div class="question-item">
                        <p class="question-text"><span class="q-num">{{ $i + 1 }}.</span> {{ $q }}</p>
                        <div class="likert-scale">
                            @foreach(['Strongly Disagree','Disagree','Neutral','Agree','Strongly Agree'] as $val => $label)
                            <label class="likert-option">
                                <input type="radio" name="q1_{{ $i+1 }}" value="{{ $val+1 }}">
                                <span class="likert-dot"></span>
                                <span class="likert-label">{{ $label }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- ═══════════════════ PAGE 2 ═══════════════════ --}}
            <div class="quiz-page" id="page-2">
                <p class="quiz-section-pill">Section 2 of 6</p>
                <h2 class="quiz-section-title">Thinking &amp; Decision Making</h2>
                <p class="quiz-section-sub">How you process information and make decisions</p>

                <div class="questions-list">
                    @foreach([
                        'I rely more on logic and facts than feelings when making decisions.',
                        'I notice patterns and details that others often miss.',
                        'I prefer to think about future possibilities rather than focus on the present.',
                        'I find it easier to stay objective even in emotional situations.',
                        'I trust data and evidence more than gut feelings.',
                    ] as $i => $q)
                    <div class="question-item">
                        <p class="question-text"><span class="q-num">{{ $i + 6 }}.</span> {{ $q }}</p>
                        <div class="likert-scale">
                            @foreach(['Strongly Disagree','Disagree','Neutral','Agree','Strongly Agree'] as $val => $label)
                            <label class="likert-option">
                                <input type="radio" name="q2_{{ $i+1 }}" value="{{ $val+1 }}">
                                <span class="likert-dot"></span>
                                <span class="likert-label">{{ $label }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- ═══════════════════ PAGE 3 ═══════════════════ --}}
            <div class="quiz-page" id="page-3">
                <p class="quiz-section-pill">Section 3 of 6</p>
                <h2 class="quiz-section-title">Structure &amp; Lifestyle</h2>
                <p class="quiz-section-sub">How you organize your life and handle plans</p>

                <div class="questions-list">
                    @foreach([
                        'I prefer to have a detailed plan rather than going with the flow.',
                        'I feel uncomfortable when my routine is suddenly disrupted.',
                        'I like to finish one task completely before starting another.',
                        'I keep my workspace organized and tidy.',
                        'I set deadlines for myself even when none are required.',
                    ] as $i => $q)
                    <div class="question-item">
                        <p class="question-text"><span class="q-num">{{ $i + 11 }}.</span> {{ $q }}</p>
                        <div class="likert-scale">
                            @foreach(['Strongly Disagree','Disagree','Neutral','Agree','Strongly Agree'] as $val => $label)
                            <label class="likert-option">
                                <input type="radio" name="q3_{{ $i+1 }}" value="{{ $val+1 }}">
                                <span class="likert-dot"></span>
                                <span class="likert-label">{{ $label }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- ═══════════════════ PAGE 4 ═══════════════════ --}}
            <div class="quiz-page" id="page-4">
                <p class="quiz-section-pill">Section 4 of 6</p>
                <h2 class="quiz-section-title">Emotional Wellbeing</h2>
                <p class="quiz-section-sub">How you feel about yourself and your life right now</p>

                <div class="questions-list">
                    @foreach([
                        'I generally feel positive and hopeful about my future.',
                        'I feel satisfied with the relationships in my life.',
                        'I am able to manage my emotions effectively in difficult situations.',
                        'I feel a sense of meaning and purpose in what I do daily.',
                        'I find it easy to bounce back after a tough day.',
                    ] as $i => $q)
                    <div class="question-item">
                        <p class="question-text"><span class="q-num">{{ $i + 16 }}.</span> {{ $q }}</p>
                        <div class="likert-scale">
                            @foreach(['Strongly Disagree','Disagree','Neutral','Agree','Strongly Agree'] as $val => $label)
                            <label class="likert-option">
                                <input type="radio" name="q4_{{ $i+1 }}" value="{{ $val+1 }}">
                                <span class="likert-dot"></span>
                                <span class="likert-label">{{ $label }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- ═══════════════════ PAGE 5 ═══════════════════ --}}
            <div class="quiz-page" id="page-5">
                <p class="quiz-section-pill">Section 5 of 6</p>
                <h2 class="quiz-section-title">Stress &amp; Coping</h2>
                <p class="quiz-section-sub">How you respond to pressure and challenges</p>

                <div class="questions-list">
                    @foreach([
                        'I often feel overwhelmed by the responsibilities in my life.',
                        'When stressed, I tend to withdraw and spend time alone.',
                        'I find healthy ways to cope when things get difficult.',
                        'I have someone I can talk to when I am going through a hard time.',
                        'I tend to worry about things that are outside of my control.',
                    ] as $i => $q)
                    <div class="question-item">
                        <p class="question-text"><span class="q-num">{{ $i + 21 }}.</span> {{ $q }}</p>
                        <div class="likert-scale">
                            @foreach(['Strongly Disagree','Disagree','Neutral','Agree','Strongly Agree'] as $val => $label)
                            <label class="likert-option">
                                <input type="radio" name="q5_{{ $i+1 }}" value="{{ $val+1 }}">
                                <span class="likert-dot"></span>
                                <span class="likert-label">{{ $label }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- ═══════════════════ PAGE 6 ═══════════════════ --}}
            <div class="quiz-page" id="page-6">
                <p class="quiz-section-pill">Section 6 of 6</p>
                <h2 class="quiz-section-title">Self-Awareness &amp; Growth</h2>
                <p class="quiz-section-sub">How well you know yourself and your desire to grow</p>

                <div class="questions-list">
                    @foreach([
                        'I regularly reflect on my thoughts, feelings, and behaviors.',
                        'I am open to receiving feedback even when it is critical.',
                        'I actively try to learn new things about myself and the world.',
                        'I recognize when my mental health needs attention and I seek help.',
                        'I believe I have the ability to change and grow as a person.',
                    ] as $i => $q)
                    <div class="question-item">
                        <p class="question-text"><span class="q-num">{{ $i + 26 }}.</span> {{ $q }}</p>
                        <div class="likert-scale">
                            @foreach(['Strongly Disagree','Disagree','Neutral','Agree','Strongly Agree'] as $val => $label)
                            <label class="likert-option">
                                <input type="radio" name="q6_{{ $i+1 }}" value="{{ $val+1 }}">
                                <span class="likert-dot"></span>
                                <span class="likert-label">{{ $label }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- ── Navigation ── --}}
            <div class="quiz-nav">
                <button class="btn-quiz-back" id="btnBack" onclick="changePage(-1)" style="display:none;">
                    <i class="bi bi-arrow-left"></i> Back
                </button>
                <button class="btn-quiz-next" id="btnNext" onclick="changePage(1)">
                    Next <i class="bi bi-arrow-right"></i>
                </button>
                <button class="btn-quiz-submit" id="btnSubmit" onclick="submitQuiz()" style="display:none;">
                    Submit <i class="bi bi-check-lg"></i>
                </button>
            </div>

        </div>
    </div>

    <style>
        body { background: var(--cream); }

        /* ── Wrapper ──────────────────────────────────────── */
        .quiz-wrapper {
            min-height: calc(100vh - 80px);
            padding: 2.5rem 1rem 4rem;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* ── Progress bar ─────────────────────────────────── */
        .quiz-progress-bar {
            width: 100%;
            max-width: 780px;
            margin-bottom: 1.5rem;
        }

        .quiz-progress-inner {
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .progress-label, .progress-pct {
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--muted);
            white-space: nowrap;
        }

        .progress-track {
            flex: 1;
            height: 8px;
            background: rgba(123,107,53,0.15);
            border-radius: 99px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--primary), var(--gold));
            border-radius: 99px;
            transition: width 0.4s ease;
        }

        /* ── Quiz card ────────────────────────────────────── */
        .quiz-card {
            background: var(--cream-light);
            border-radius: 20px;
            padding: 2.5rem 3rem;
            max-width: 780px;
            width: 100%;
            box-shadow: 0 8px 32px rgba(0,0,0,0.08);
            border: 1px solid rgba(123,107,53,0.1);
        }

        /* ── Page show/hide ───────────────────────────────── */
        .quiz-page { display: none; }
        .quiz-page.active { display: block; }

        /* ── Section header ───────────────────────────────── */
        .quiz-section-pill {
            display: inline-block;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--primary);
            background: rgba(123,107,53,0.1);
            padding: 0.25rem 0.8rem;
            border-radius: 20px;
            margin-bottom: 0.6rem;
        }

        .quiz-section-title {
            font-family: 'IM Fell English', serif;
            font-size: 1.8rem;
            color: var(--dark);
            margin-bottom: 0.3rem;
        }

        .quiz-section-sub {
            font-size: 0.9rem;
            color: var(--muted);
            margin-bottom: 2rem;
            padding-bottom: 1.2rem;
            border-bottom: 1px solid rgba(123,107,53,0.12);
        }

        /* ── Question items ───────────────────────────────── */
        .questions-list {
            display: flex;
            flex-direction: column;
            gap: 1.8rem;
        }

        .question-item {
            background: var(--cream);
            border-radius: 12px;
            padding: 1.2rem 1.4rem;
            border: 1px solid rgba(123,107,53,0.1);
        }

        .question-text {
            font-size: 0.97rem;
            color: var(--dark);
            font-weight: 500;
            margin-bottom: 1rem;
            line-height: 1.5;
        }

        .q-num {
            color: var(--primary);
            font-weight: 700;
            margin-right: 4px;
        }

        /* ── Likert scale ─────────────────────────────────── */
        .likert-scale {
            display: flex;
            justify-content: space-between;
            gap: 0.4rem;
        }

        .likert-option {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.4rem;
            cursor: pointer;
            flex: 1;
        }

        .likert-option input[type="radio"] { display: none; }

        .likert-dot {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            border: 2px solid rgba(123,107,53,0.3);
            background: #fff;
            transition: all 0.2s;
            display: block;
        }

        .likert-option input:checked ~ .likert-dot {
            background: var(--primary);
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(123,107,53,0.2);
        }

        .likert-option:hover .likert-dot {
            border-color: var(--gold);
            background: rgba(196,168,64,0.1);
        }

        .likert-label {
            font-size: 0.68rem;
            color: var(--muted);
            text-align: center;
            line-height: 1.3;
        }

        /* ── Navigation buttons ───────────────────────────── */
        .quiz-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 2.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(123,107,53,0.12);
        }

        .btn-quiz-back {
            background: transparent;
            border: 1.5px solid rgba(123,107,53,0.3);
            color: var(--muted);
            padding: 0.65rem 1.6rem;
            border-radius: 8px;
            font-size: 0.92rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
        }

        .btn-quiz-back:hover {
            border-color: var(--primary);
            color: var(--primary);
        }

        .btn-quiz-next, .btn-quiz-submit {
            background: var(--primary);
            color: #fff;
            border: none;
            padding: 0.65rem 2rem;
            border-radius: 8px;
            font-size: 0.92rem;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: background 0.2s;
            margin-left: auto;
        }

        .btn-quiz-next:hover, .btn-quiz-submit:hover {
            background: var(--primary-dark);
        }

        .btn-quiz-submit {
            background: var(--gold);
        }

        .btn-quiz-submit:hover {
            background: var(--primary);
        }
    </style>

    <script>
        let current = 1;
        const total = 6;

        function changePage(direction) {
            document.getElementById('page-' + current).classList.remove('active');
            current += direction;
            document.getElementById('page-' + current).classList.add('active');
            updateUI();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function updateUI() {
            const pct = Math.round((current / total) * 100);
            document.getElementById('progressFill').style.width = pct + '%';
            document.getElementById('progressPct').textContent = pct + '%';
            document.getElementById('currentPage').textContent = current;

            document.getElementById('btnBack').style.display   = current === 1 ? 'none' : 'flex';
            document.getElementById('btnNext').style.display   = current === total ? 'none' : 'flex';
            document.getElementById('btnSubmit').style.display = current === total ? 'flex' : 'none';
        }

        function submitQuiz() {
            // Collect all radio answers and compute per-section scores
            const sections = [
                { key:'social',    prefix:'q1', label:'Social & Energy' },
                { key:'thinking',  prefix:'q2', label:'Thinking & Decision Making' },
                { key:'structure', prefix:'q3', label:'Structure & Lifestyle' },
                { key:'wellbeing', prefix:'q4', label:'Emotional Wellbeing' },
                { key:'stress',    prefix:'q5', label:'Stress & Coping' },
                { key:'growth',    prefix:'q6', label:'Self-Awareness & Growth' },
            ];

            const scores = {};
            sections.forEach(sec => {
                let total = 0;
                for (let i = 1; i <= 5; i++) {
                    const el = document.querySelector(`input[name="${sec.prefix}_${i}"]:checked`);
                    total += el ? parseInt(el.value) : 3; // default neutral if skipped
                }
                scores[sec.key] = { label: sec.label, score: total, pct: Math.round(((total - 5) / 20) * 100) };
            });

            localStorage.setItem('werta_scores', JSON.stringify(scores));
            window.location.href = '/assessment/processing';
        }
    </script>

@endsection
