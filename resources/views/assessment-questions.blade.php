@extends('layouts.app')

@section('content')
    @include('partials.navbar')

    <div class="quiz-wrapper">

        {{-- ── Section stepper + progress ── --}}
        <div class="quiz-progress-bar">
            <div class="section-stepper" id="sectionStepper">
                @foreach(['Social','Thinking','Structure','Wellbeing','Stress','Growth'] as $i => $label)
                <div class="step" id="step-{{ $i + 1 }}" data-step="{{ $i + 1 }}">
                    <span class="step-dot"><i class="bi bi-check-lg step-check"></i></span>
                    <span class="step-label">{{ $label }}</span>
                </div>
                @endforeach
            </div>
            <div class="quiz-progress-inner">
                <span class="progress-label"><span id="answeredCount">0</span> of 30 answered</span>
                <div class="progress-track">
                    <div class="progress-fill" id="progressFill" style="width:0%"></div>
                </div>
                <span class="progress-pct" id="progressPct">0%</span>
            </div>
        </div>

        {{-- ── Quiz card ── --}}
        <div class="quiz-card">

            {{-- ═══════════════════ PAGE 1 — Social & Energy ═══════════════════ --}}
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
                    <div class="question-item" id="q-q1_{{ $i+1 }}">
                        <p class="question-text">
                            <span class="q-num">{{ $i + 1 }}.</span> {{ $q }}
                            <i class="bi bi-check-circle-fill q-answered-icon"></i>
                        </p>
                        <div class="likert-scale">
                            @foreach(['Strongly Disagree','Disagree','Neutral','Agree','Strongly Agree'] as $val => $label)
                            <label class="likert-option">
                                <input type="radio" name="q1_{{ $i+1 }}" value="{{ $val+1 }}" onchange="onAnswer('q1_{{ $i+1 }}')">
                                <span class="likert-dot"></span>
                                <span class="likert-label">{{ $label }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- ═══════════════════ PAGE 2 — Thinking & Decision Making ═══════════════════ --}}
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
                    <div class="question-item" id="q-q2_{{ $i+1 }}">
                        <p class="question-text">
                            <span class="q-num">{{ $i + 6 }}.</span> {{ $q }}
                            <i class="bi bi-check-circle-fill q-answered-icon"></i>
                        </p>
                        <div class="likert-scale">
                            @foreach(['Strongly Disagree','Disagree','Neutral','Agree','Strongly Agree'] as $val => $label)
                            <label class="likert-option">
                                <input type="radio" name="q2_{{ $i+1 }}" value="{{ $val+1 }}" onchange="onAnswer('q2_{{ $i+1 }}')">
                                <span class="likert-dot"></span>
                                <span class="likert-label">{{ $label }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- ═══════════════════ PAGE 3 — Structure & Lifestyle ═══════════════════ --}}
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
                    <div class="question-item" id="q-q3_{{ $i+1 }}">
                        <p class="question-text">
                            <span class="q-num">{{ $i + 11 }}.</span> {{ $q }}
                            <i class="bi bi-check-circle-fill q-answered-icon"></i>
                        </p>
                        <div class="likert-scale">
                            @foreach(['Strongly Disagree','Disagree','Neutral','Agree','Strongly Agree'] as $val => $label)
                            <label class="likert-option">
                                <input type="radio" name="q3_{{ $i+1 }}" value="{{ $val+1 }}" onchange="onAnswer('q3_{{ $i+1 }}')">
                                <span class="likert-dot"></span>
                                <span class="likert-label">{{ $label }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- ═══════════════════ PAGE 4 — Emotional Wellbeing ═══════════════════ --}}
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
                    <div class="question-item" id="q-q4_{{ $i+1 }}">
                        <p class="question-text">
                            <span class="q-num">{{ $i + 16 }}.</span> {{ $q }}
                            <i class="bi bi-check-circle-fill q-answered-icon"></i>
                        </p>
                        <div class="likert-scale">
                            @foreach(['Strongly Disagree','Disagree','Neutral','Agree','Strongly Agree'] as $val => $label)
                            <label class="likert-option">
                                <input type="radio" name="q4_{{ $i+1 }}" value="{{ $val+1 }}" onchange="onAnswer('q4_{{ $i+1 }}')">
                                <span class="likert-dot"></span>
                                <span class="likert-label">{{ $label }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- ═══════════════════ PAGE 5 — Stress & Coping ═══════════════════ --}}
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
                    <div class="question-item" id="q-q5_{{ $i+1 }}">
                        <p class="question-text">
                            <span class="q-num">{{ $i + 21 }}.</span> {{ $q }}
                            <i class="bi bi-check-circle-fill q-answered-icon"></i>
                        </p>
                        <div class="likert-scale">
                            @foreach(['Strongly Disagree','Disagree','Neutral','Agree','Strongly Agree'] as $val => $label)
                            <label class="likert-option">
                                <input type="radio" name="q5_{{ $i+1 }}" value="{{ $val+1 }}" onchange="onAnswer('q5_{{ $i+1 }}')">
                                <span class="likert-dot"></span>
                                <span class="likert-label">{{ $label }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- ═══════════════════ PAGE 6 — Self-Awareness & Growth ═══════════════════ --}}
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
                    <div class="question-item" id="q-q6_{{ $i+1 }}">
                        <p class="question-text">
                            <span class="q-num">{{ $i + 26 }}.</span> {{ $q }}
                            <i class="bi bi-check-circle-fill q-answered-icon"></i>
                        </p>
                        <div class="likert-scale">
                            @foreach(['Strongly Disagree','Disagree','Neutral','Agree','Strongly Agree'] as $val => $label)
                            <label class="likert-option">
                                <input type="radio" name="q6_{{ $i+1 }}" value="{{ $val+1 }}" onchange="onAnswer('q6_{{ $i+1 }}')">
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
                <p class="quiz-nav-error" id="navError">Please answer all questions before continuing.</p>
                <div class="quiz-nav-buttons">
                    <button class="btn-quiz-back" id="btnBack" onclick="changePage(-1)" style="display:none;">
                        <i class="bi bi-arrow-left"></i> Back
                    </button>
                    <button class="btn-quiz-next" id="btnNext" onclick="attemptChangePage(1)">
                        Next <i class="bi bi-arrow-right"></i>
                    </button>
                    <button class="btn-quiz-submit" id="btnSubmit" onclick="submitQuiz()" style="display:none;">
                        Submit <i class="bi bi-check-lg"></i>
                    </button>
                </div>
            </div>

        </div>
    </div>

    <style>
        body { background: var(--cream); }

        .quiz-wrapper {
            min-height: calc(100vh - 80px);
            padding: 2.5rem 1rem 4rem;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* ── Section stepper ──────────────────────────────── */
        .quiz-progress-bar { width: 100%; max-width: 780px; margin-bottom: 1.5rem; }

        .section-stepper {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
        }

        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            flex: 1;
        }

        .step-dot {
            width: 22px;
            height: 22px;
            border-radius: var(--radius-full);
            border: 2px solid rgba(123,107,53,0.25);
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .step-check { font-size: 0.65rem; color: #fff; display: none; }

        .step.current .step-dot { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(123,107,53,0.15); }
        .step.done .step-dot { background: var(--primary); border-color: var(--primary); }
        .step.done .step-check { display: block; }

        .step-label { font-size: var(--text-xs); color: var(--muted); text-align: center; }
        .step.current .step-label { color: var(--primary); font-weight: 700; }

        /* ── Overall progress bar ─────────────────────────── */
        .quiz-progress-inner { display: flex; align-items: center; gap: 0.8rem; }
        .progress-label, .progress-pct { font-size: var(--text-sm); font-weight: 700; color: var(--muted); white-space: nowrap; }
        .progress-track { flex: 1; height: 8px; background: rgba(123,107,53,0.15); border-radius: var(--radius-pill); overflow: hidden; }
        .progress-fill { height: 100%; background: linear-gradient(90deg, var(--primary), var(--gold)); border-radius: var(--radius-pill); transition: width 0.4s ease; }

        /* ── Quiz card ─────────────────────────────────────── */
        .quiz-card {
            background: var(--cream-light);
            border-radius: var(--radius-xl);
            padding: 2.5rem 3rem;
            max-width: 780px;
            width: 100%;
            box-shadow: var(--shadow-md);
            border: 1px solid rgba(123,107,53,0.1);
        }

        .quiz-page { display: none; }
        .quiz-page.active { display: block; }

        .quiz-section-pill {
            display: inline-block;
            font-size: var(--text-sm);
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--primary);
            background: rgba(123,107,53,0.1);
            padding: 0.25rem 0.8rem;
            border-radius: var(--radius-pill);
            margin-bottom: 0.6rem;
        }

        .quiz-section-title { font-family: 'IM Fell English', serif; font-size: var(--text-2xl); color: var(--dark); margin-bottom: 0.3rem; }
        .quiz-section-sub { font-size: var(--text-base); color: var(--muted); margin-bottom: 2rem; padding-bottom: 1.2rem; border-bottom: 1px solid rgba(123,107,53,0.12); }

        .questions-list { display: flex; flex-direction: column; gap: 1.8rem; }

        /* ── Question items ───────────────────────────────── */
        .question-item {
            background: var(--cream);
            border-radius: var(--radius-lg);
            padding: 1.2rem 1.4rem;
            border: 1px solid rgba(123,107,53,0.1);
            transition: border-color 0.25s, background 0.25s;
        }

        .question-item.answered {
            border-color: var(--primary);
            background: rgba(123,107,53,0.05);
        }

        .question-item.error {
            border-color: #c0392b;
            animation: shake 0.3s;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-4px); }
            75% { transform: translateX(4px); }
        }

        .question-text {
            font-size: var(--text-base-lg);
            color: var(--dark);
            font-weight: 500;
            margin-bottom: 1rem;
            line-height: 1.5;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .q-num { color: var(--primary); font-weight: 700; margin-right: 4px; }

        .q-answered-icon {
            font-size: var(--text-base);
            color: var(--primary);
            opacity: 0;
            transform: scale(0.5);
            transition: all 0.25s;
            margin-left: auto;
        }

        .question-item.answered .q-answered-icon { opacity: 1; transform: scale(1); }

        /* ── Likert scale ─────────────────────────────────── */
        .likert-scale { display: flex; justify-content: space-between; gap: 0.4rem; }
        .likert-option { display: flex; flex-direction: column; align-items: center; gap: 0.4rem; cursor: pointer; flex: 1; position: relative; }

        .likert-option input[type="radio"] {
            position: absolute;
            opacity: 0;
            width: 28px;
            height: 28px;
        }

        .likert-dot {
            width: 28px;
            height: 28px;
            border-radius: var(--radius-full);
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

        .likert-option input:focus-visible ~ .likert-dot {
            outline: 2px solid var(--gold);
            outline-offset: 2px;
        }

        .likert-option:hover .likert-dot { border-color: var(--gold); background: rgba(196,168,64,0.1); }
        .likert-label { font-size: var(--text-xs); color: var(--muted); text-align: center; line-height: 1.3; }

        /* ── Navigation ────────────────────────────────────── */
        .quiz-nav { margin-top: 2.5rem; padding-top: 1.5rem; border-top: 1px solid rgba(123,107,53,0.12); }

        .quiz-nav-error {
            font-size: var(--text-base);
            color: #c0392b;
            margin-bottom: 0.8rem;
            display: none;
        }

        .quiz-nav-error.show { display: block; }

        .quiz-nav-buttons { display: flex; justify-content: space-between; align-items: center; }

        .btn-quiz-back {
            background: transparent;
            border: 1.5px solid rgba(123,107,53,0.3);
            color: var(--muted);
            padding: 0.65rem 1.6rem;
            border-radius: var(--radius-md);
            font-size: var(--text-base-lg);
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
        }

        .btn-quiz-back:hover { border-color: var(--primary); color: var(--primary); }

        .btn-quiz-next, .btn-quiz-submit {
            background: var(--primary);
            color: #fff;
            border: none;
            padding: 0.65rem 2rem;
            border-radius: var(--radius-md);
            font-size: var(--text-base-lg);
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: background 0.2s;
            margin-left: auto;
        }

        .btn-quiz-next:hover, .btn-quiz-submit:hover { background: var(--primary-dark); }
        .btn-quiz-submit { background: var(--gold); }
        .btn-quiz-submit:hover { background: var(--primary); }
        .btn-quiz-submit:disabled { opacity: 0.5; cursor: not-allowed; }
    </style>

    <script>
        let current = 1;
        const total = 6;
        const answers = {}; // tracks every answered question across all pages

        const sections = [
            { key:'social',    prefix:'q1', label:'Social & Energy',            count:5 },
            { key:'thinking',  prefix:'q2', label:'Thinking & Decision Making', count:5 },
            { key:'structure', prefix:'q3', label:'Structure & Lifestyle',      count:5 },
            { key:'wellbeing', prefix:'q4', label:'Emotional Wellbeing',        count:5 },
            { key:'stress',    prefix:'q5', label:'Stress & Coping',            count:5 },
            { key:'growth',    prefix:'q6', label:'Self-Awareness & Growth',    count:5 },
        ];

        function onAnswer(name) {
            const el = document.querySelector(`input[name="${name}"]:checked`);
            answers[name] = el ? parseInt(el.value) : null;

            const card = document.getElementById('q-' + name);
            card.classList.remove('error');
            card.classList.add('answered');

            updateProgress();

            // auto-scroll to next unanswered question on this page
            const currentPrefix = sections[current - 1].prefix;
            for (let i = 1; i <= 5; i++) {
                const qName = `${currentPrefix}_${i}`;
                if (!answers[qName]) {
                    document.getElementById('q-' + qName)
                        .scrollIntoView({ behavior: 'smooth', block: 'center' });
                    break;
                }
            }
        }

        function pageComplete(pageNum) {
            const prefix = sections[pageNum - 1].prefix;
            for (let i = 1; i <= 5; i++) {
                if (!answers[`${prefix}_${i}`]) return false;
            }
            return true;
        }

        function attemptChangePage(direction) {
            if (direction > 0 && !pageComplete(current)) {
                const prefix = sections[current - 1].prefix;
                let firstUnanswered = null;
                for (let i = 1; i <= 5; i++) {
                    const qName = `${prefix}_${i}`;
                    if (!answers[qName]) {
                        const card = document.getElementById('q-' + qName);
                        card.classList.remove('error');
                        void card.offsetWidth; // force reflow so the animation can replay
                        card.classList.add('error');
                        if (!firstUnanswered) firstUnanswered = card;
                    }
                }
                document.getElementById('navError').classList.add('show');
                firstUnanswered.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return;
            }
            document.getElementById('navError').classList.remove('show');
            changePage(direction);
        }

        function changePage(direction) {
            document.getElementById('navError').classList.remove('show');
            document.getElementById('page-' + current).classList.remove('active');
            current += direction;
            document.getElementById('page-' + current).classList.add('active');
            updateUI();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function updateProgress() {
            const answeredCount = Object.values(answers).filter(v => v !== null).length;
            const pct = Math.round((answeredCount / 30) * 100);
            document.getElementById('progressFill').style.width = pct + '%';
            document.getElementById('progressPct').textContent = pct + '%';
            document.getElementById('answeredCount').textContent = answeredCount;

            sections.forEach((sec, idx) => {
                const stepEl = document.getElementById('step-' + (idx + 1));
                stepEl.classList.toggle('done', pageComplete(idx + 1));
            });

            document.getElementById('btnSubmit').disabled = answeredCount < 30;
        }

        function updateUI() {
            document.getElementById('btnBack').style.display   = current === 1 ? 'none' : 'flex';
            document.getElementById('btnNext').style.display   = current === total ? 'none' : 'flex';
            document.getElementById('btnSubmit').style.display = current === total ? 'flex' : 'none';

            sections.forEach((sec, idx) => {
                document.getElementById('step-' + (idx + 1))
                    .classList.toggle('current', idx + 1 === current);
            });
        }

        function submitQuiz() {
            if (Object.values(answers).filter(v => v !== null).length < 30) {
                attemptChangePage(1);
                return;
            }

            const scores = {};
            sections.forEach(sec => {
                let sectionScore = 0;
                for (let i = 1; i <= 5; i++) {
                    sectionScore += answers[`${sec.prefix}_${i}`];
                }
                scores[sec.key] = { label: sec.label, score: sectionScore, pct: Math.round(((sectionScore - 5) / 20) * 100) };
            });

            localStorage.setItem('werta_scores', JSON.stringify(scores));
            window.location.href = '/assessment/processing';
        }

        updateUI();
    </script>

@endsection
