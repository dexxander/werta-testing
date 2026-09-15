<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Werta – Analyzing</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/Werta_Logo.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=IM+Fell+English:ital@0;1&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        /* NOTE: Design tokens below are intentionally duplicated from layouts/app.blade.php.
           Keep in sync manually as this page is a standalone, distraction-free interstitial screen. */
        :root {
            --cream:        #F5EFE0;
            --cream-light:  #FDFAF4;
            --primary:      #7B6B35;
            --primary-dark: #574B22;
            --gold:         #C4A840;
            --dark:         #2C2416;
            --muted:        #6B6455;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background: var(--cream);
            font-family: 'Lato', sans-serif;
            color: var(--dark);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 2rem;
        }

        /* ── Spinner ── */
        .spinner-wrap {
            position: relative;
            width: 110px;
            height: 110px;
        }

        .spinner-ring {
            position: absolute;
            inset: 0;
            border-radius: 50%;
            border: 5px solid transparent;
            animation: spin var(--dur) linear infinite;
        }

        .ring-1 { border-top-color: var(--gold);         --dur: 1.2s; }
        .ring-2 { border-right-color: var(--primary);    --dur: 1.8s; inset: 12px; }
        .ring-3 { border-bottom-color: var(--primary-dark); --dur: 2.4s; inset: 24px; }

        .spinner-logo {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .spinner-logo img { width: 38px; height: auto; }

        @keyframes spin { to { transform: rotate(360deg); } }

        /* ── Steps ── */
        .steps {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.6rem;
        }

        .step {
            font-size: 0.92rem;
            color: var(--muted);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            opacity: 0;
            transform: translateY(8px);
            transition: opacity 0.5s ease, transform 0.5s ease;
        }

        .step.visible { opacity: 1; transform: translateY(0); }
        .step.done    { color: var(--primary); font-weight: 600; }

        .step-dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            background: var(--gold);
            flex-shrink: 0;
        }

        /* ── Heading ── */
        h1 {
            font-family: 'IM Fell English', serif;
            font-size: 1.6rem;
            color: var(--dark);
            text-align: center;
        }

        .sub {
            font-size: 0.85rem;
            color: var(--muted);
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="spinner-wrap">
        <div class="spinner-ring ring-1"></div>
        <div class="spinner-ring ring-2"></div>
        <div class="spinner-ring ring-3"></div>
        <div class="spinner-logo">
            <img src="{{ asset('images/Werta_Logo.png') }}" alt="Werta">
        </div>
    </div>

    <h1>Analyzing your responses…</h1>
    <p class="sub">Please wait while we process your assessment.</p>

    <div class="steps">
        <div class="step" id="s1"><span class="step-dot"></span> Collecting your answers</div>
        <div class="step" id="s2"><span class="step-dot"></span> Scoring across 6 dimensions</div>
        <div class="step" id="s3"><span class="step-dot"></span> Identifying your personality profile</div>
        <div class="step" id="s4"><span class="step-dot"></span> Generating your wellbeing summary</div>
        <div class="step" id="s5"><span class="step-dot"></span> Preparing your report</div>
    </div>

    {{-- Fallback escape hatch if JS redirect or network fails --}}
    <div id="fallback-wrap" style="opacity: 0; pointer-events: none; transition: opacity 0.5s ease; margin-top: 1rem; text-align: center;">
        <p style="font-size: 0.85rem; color: var(--muted); margin-bottom: 0.5rem;">Taking longer than expected?</p>
        <a href="{{ url('/assessment/email') }}" style="display: inline-block; font-size: 0.875rem; font-weight: 700; color: var(--primary); text-decoration: underline; text-underline-offset: 4px; padding: 0.35rem 0.75rem; border-radius: 6px; transition: color 0.2s ease;">
            Continue to Results &rarr;
        </a>
    </div>

    <script>
        const steps = ['s1','s2','s3','s4','s5'];
        let i = 0;

        function showNext() {
            if (i > 0) document.getElementById(steps[i-1]).classList.add('done');
            if (i < steps.length) {
                document.getElementById(steps[i]).classList.add('visible');
                i++;
                setTimeout(showNext, 700);
            } else {
                setTimeout(() => { window.location.href = '/assessment/email'; }, 600);
            }
        }

        setTimeout(showNext, 400);

        // Reveal fallback link if redirect takes longer than 5 seconds
        setTimeout(function() {
            const fallback = document.getElementById('fallback-wrap');
            if (fallback) {
                fallback.style.opacity = '1';
                fallback.style.pointerEvents = 'auto';
            }
        }, 5000);
    </script>
</body>
</html>
