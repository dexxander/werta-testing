@extends('layouts.app')

@section('content')
    @include('partials.navbar')

    <div class="email-wrapper">

        {{-- Security badge --}}
        <div class="secure-badge">
            <i class="bi bi-shield-check"></i>
            Your connection is 100% Secure
        </div>

        <div class="email-container">

            {{-- Left: email form --}}
            <div class="email-left">
                <h2 class="email-heading">Please save your results below:</h2>
                <p class="email-sub">Enter your email to view your personalized wellbeing report.</p>

                <div class="email-form">
                    <input type="email" id="emailInput" class="email-input" placeholder="email@example.com">

                    <label class="agree-label">
                        <input type="checkbox" id="agreeCheck">
                        <span>
                            I agree to receive my wellbeing results. Your data will be processed
                            in accordance with our <a href="#">Privacy Policy</a>.
                        </span>
                    </label>

                    <button class="btn-view-results" id="btnView" onclick="goToResults()">
                        View My Results <i class="bi bi-arrow-right"></i>
                    </button>

                    <p class="email-note" id="emailError" style="display:none; color:#c0392b; font-size:var(--text-sm); margin-top:0.5rem;">
                        Please enter a valid email and tick the agreement.
                    </p>
                </div>
            </div>

            {{-- Divider --}}
            <div class="email-divider"></div>

            {{-- Right: Werta stats --}}
            <div class="email-right">
                <h3 class="stats-heading">Why Werta?</h3>

                <div class="stat-rows">
                    <div class="stat-row">
                        <div class="stat-icon"><i class="bi bi-journal-text"></i></div>
                        <div>
                            <p class="stat-num">12,480+</p>
                            <p class="stat-desc">Assessments Taken</p>
                        </div>
                    </div>
                    <div class="stat-row">
                        <div class="stat-icon"><i class="bi bi-people"></i></div>
                        <div>
                            <p class="stat-num">10,200+</p>
                            <p class="stat-desc">Satisfied Users</p>
                        </div>
                    </div>
                    <div class="stat-row">
                        <div class="stat-icon"><i class="bi bi-graph-up"></i></div>
                        <div>
                            <p class="stat-num">200+</p>
                            <p class="stat-desc">Data Points Analyzed</p>
                        </div>
                    </div>
                    <div class="stat-row">
                        <div class="stat-icon"><i class="bi bi-star"></i></div>
                        <div>
                            <p class="stat-num">91.4%</p>
                            <p class="stat-desc">Results Rated as Accurate</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <style>
        body { background: var(--cream); }

        .email-wrapper {
            min-height: calc(100vh - 80px);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem 1rem;
            gap: 2rem;
        }

        /* ── Security badge ── */
        .secure-badge {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: var(--text-base-lg);
            font-weight: 700;
            color: var(--primary);
        }

        .secure-badge i { font-size: 1.3rem; color: var(--gold); }

        /* ── Container ── */
        .email-container {
            background: var(--cream-light);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-md);
            border: 1px solid rgba(123,107,53,0.1);
            max-width: 820px;
            width: 100%;
            display: flex;
            align-items: stretch;
            overflow: hidden;
        }

        /* ── Left ── */
        .email-left {
            flex: 1;
            padding: 2.5rem 2.5rem;
        }

        .email-heading {
            font-family: 'IM Fell English', serif;
            font-size: var(--text-xl);
            color: var(--primary);
            margin-bottom: 0.5rem;
        }

        .email-sub {
            font-size: var(--text-base);
            color: var(--muted);
            margin-bottom: 1.8rem;
        }

        .email-form { display: flex; flex-direction: column; gap: 1.2rem; }

        .email-input {
            width: 100%;
            padding: 0.85rem 1.2rem;
            border: 1.5px solid rgba(123,107,53,0.25);
            border-radius: var(--radius-md);
            font-size: var(--text-base-lg);
            background: #fff;
            color: var(--dark);
            outline: none;
            transition: border-color 0.2s;
        }

        .email-input:focus { border-color: var(--gold); }

        .agree-label {
            display: flex;
            align-items: flex-start;
            gap: 0.7rem;
            font-size: var(--text-sm);
            color: var(--muted);
            line-height: 1.5;
            cursor: pointer;
        }

        .agree-label input { margin-top: 3px; accent-color: var(--primary); flex-shrink: 0; }
        .agree-label a { color: var(--primary); font-weight: 600; }

        .btn-view-results {
            background: var(--primary);
            color: #fff;
            border: none;
            padding: 0.85rem;
            border-radius: var(--radius-md);
            font-size: var(--text-base-lg);
            font-weight: 700;
            cursor: pointer;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: background 0.2s;
        }

        .btn-view-results:hover { background: var(--primary-dark); }

        /* ── Divider ── */
        .email-divider {
            width: 1px;
            background: rgba(123,107,53,0.12);
            margin: 2rem 0;
        }

        /* ── Right ── */
        .email-right {
            flex: 1;
            padding: 2.5rem 2.5rem;
        }

        .stats-heading {
            font-family: 'IM Fell English', serif;
            font-size: var(--text-xl);
            color: var(--dark);
            margin-bottom: 1.8rem;
        }

        .stat-rows { display: flex; flex-direction: column; gap: 1.4rem; }

        .stat-row {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .stat-icon {
            width: 46px; height: 46px;
            background: rgba(123,107,53,0.1);
            border-radius: var(--radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .stat-num {
            font-weight: 700;
            font-size: var(--text-md);
            color: var(--dark);
            margin-bottom: 0.1rem;
        }

        .stat-desc { font-size: var(--text-sm); color: var(--muted); }
    </style>

    <script>
        function goToResults() {
            const email = document.getElementById('emailInput').value.trim();
            const agreed = document.getElementById('agreeCheck').checked;
            const err = document.getElementById('emailError');

            if (!email || !email.includes('@') || !agreed) {
                err.style.display = 'block';
                return;
            }

            err.style.display = 'none';
            localStorage.setItem('werta_email', email);
            window.location.href = '/assessment/results';
        }
    </script>

@endsection
