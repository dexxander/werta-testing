<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Werta</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/Werta_Logo.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/Werta_Logo.png') }}">

    <!-- Yang ni bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Kalau yang ni bootstrap icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Kalau yg ni google fonts -->
    <link href="https://fonts.googleapis.com/css2?family=IM+Fell+English:ital@0;1&family=Lato:wght@300;400;700&family=Great+Vibes&display=swap" rel="stylesheet">

    <style>
        :root {
            /* ─── Colors ─── */
            --cream:        #F5EFE0;
            --cream-light:  #FDFAF4;
            --primary:      #7B6B35;
            --primary-dark: #574B22;
            --gold:         #C4A840;
            --dark:         #2C2416;
            --muted:        #6B6455;

            /* ─── Type scale ─── */
            --text-xs:      0.7rem;   /* Micro-labels, step indicators, pill tags (0.65–0.72rem cluster) */
            --text-sm:      0.8rem;   /* Small captions, secondary meta, disclaimers (0.75–0.83rem cluster) */
            --text-base:    0.9rem;   /* True body/nav baseline (absorbs 0.875rem, 0.88rem, 0.9rem) */
            --text-base-lg: 0.95rem;  /* Prominent body, buttons, CTAs, lead text */
            --text-md:      1rem;     /* 16px standard baseline */
            --text-lg:      1.2rem;   /* Compact subheadings, card titles (1.15–1.2rem) */
            --text-xl:      1.4rem;   /* Feature headings, medium titles (1.3–1.4rem) */
            --text-2xl:     1.8rem;   /* Section subheadings, quiz titles (1.5–1.9rem) */
            --text-3xl:     2rem;     /* Major section headings (2.0–2.25rem) */
            --text-4xl:     2.8rem;   /* Hero subheadings (2.4–3.0rem) */

            /* ─── Radius ─── */
            --radius-sm:   6px;     /* Small badges, chips, code pills (4–6px cluster) */
            --radius-md:   8px;     /* Buttons, form inputs, dropdown menus */
            --radius-lg:   12px;    /* Inner cards, feature boxes (10–14px cluster) */
            --radius-xl:   20px;    /* Outer container cards, quiz/summary cards */
            --radius-pill: 99px;    /* Progress bars, rounded pills */
            --radius-full: 50%;     /* Circular avatars, dots, radio circles */

            /* ─── Section rhythm ─── */
            --section-py:  80px;    /* Symmetric vertical section padding */

            /* ─── Elevation ─── */
            --shadow-sm:   0 2px 8px rgba(0, 0, 0, 0.06);     /* Subtle element elevation */
            --shadow-md:   0 8px 32px rgba(0, 0, 0, 0.08);    /* Main card elevation */
            --shadow-lg:   0 12px 32px rgba(44, 36, 22, 0.1); /* Elevated dropdowns & modals */
        }

        * { box-sizing: border-box; }

        body {
            background-color: var(--cream);
            font-family: 'Lato', sans-serif;
            color: var(--dark);
            margin: 0;
        }

        /* ─── NAVBAR ────────────────────────────────────── */
        .site-navbar {
            background-color: var(--cream-light);
            padding: 1.2rem 0;
            box-shadow: var(--shadow-sm);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0.6rem 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }

        .navbar-brand-wrap {
            display: flex;
            align-items: center;
            text-decoration: none;
        }

        .brand-title {
            display: flex;
            align-items: baseline;
            gap: 1px;
        }

        .brand-w {
            font-family: 'Great Vibes', cursive;
            font-size: 4.2rem;
            color: var(--gold);
            line-height: 1;
        }

        .brand-rest {
            font-family: 'Lato', sans-serif;
            font-size: 2rem;
            font-weight: 700;
            letter-spacing: 5px;
            color: var(--primary);
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 0.3rem;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .nav-links a {
            font-size: var(--text-base);
            color: var(--dark);
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: var(--radius-sm);
            font-weight: 500;
            transition: color 0.2s;
        }

        .nav-links a:hover { color: var(--primary); }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .btn-account {
            background: var(--gold);
            color: white;
            border: none;
            padding: 0.55rem 1.4rem;
            border-radius: var(--radius-sm);
            font-weight: 600;
            font-size: var(--text-base-lg);
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: background 0.2s;
        }

        .btn-account:hover { background: var(--primary); }

        .account-dropdown { position: relative; }

        .account-dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            padding-top: 8px;
            min-width: 180px;
            z-index: 999;
        }

        .account-dropdown-menu-inner {
            background: var(--cream-light);
            border: 1px solid rgba(123,107,53,0.15);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-md);
            overflow: hidden;
        }

        .account-dropdown:hover .account-dropdown-menu { display: block; }

        .account-dropdown-menu a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0.75rem 1.2rem;
            font-size: var(--text-base);
            font-weight: 600;
            color: var(--dark);
            text-decoration: none;
            transition: background 0.15s;
        }

        .account-dropdown-menu a:hover {
            background: rgba(196,168,64,0.12);
            color: var(--primary);
        }

        .account-dropdown-menu a i { font-size: var(--text-md); color: var(--gold); }

        .account-dropdown-menu .divider {
            border: none;
            border-top: 1px solid rgba(123,107,53,0.12);
            margin: 0;
        }

        /* ─── HERO ──────────────────────────────────────── */
        .hero {
            padding: 80px 0 70px;
            position: relative;
            overflow: hidden;
        }

        .hero-video {
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            object-fit: cover;
            z-index: 0;
        }

        .hero-overlay {
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(44, 36, 22, 0.55);
            z-index: 1;
        }

        .hero .container { position: relative; z-index: 2; }

        /* Force all Bootstrap containers to match navbar max-width */
        .container { max-width: 1200px !important; }

        .hero-heading {
            font-family: 'IM Fell English', serif;
            font-size: var(--text-4xl);
            line-height: 1.15;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #ffffff;
            margin-bottom: 1rem;
        }

        /* NOTE [Design System Flag]:
           The classes below (.btn-cta-filled, .btn-cta-outline, .pill-label, .sec-heading) parallel
           Elearning's component classes (.el-btn-primary, .el-btn-outline, .el-pill, .el-heading)
           with overlapping visual intent. Consolidating them into a unified component CSS system
           should be done in a dedicated, deliberate design-system pass with side-by-side visual
           testing, rather than an ad-hoc merge, due to broad site-wide usage across Home, About,
           Assessments, and Articles. */
        .btn-cta-filled {
            background: var(--primary-dark);
            color: white;
            border: none;
            padding: 0.85rem 2.2rem;
            border-radius: var(--radius-sm);
            font-weight: 700;
            font-size: var(--text-base-lg);
            text-decoration: none;
            transition: background 0.2s;
            display: inline-block;
        }

        .btn-cta-filled:hover { background: var(--dark); color: white; }

        .btn-cta-outline {
            background: transparent;
            color: white;
            border: 1.5px solid white;
            padding: 0.85rem 2.2rem;
            border-radius: var(--radius-sm);
            font-weight: 700;
            font-size: var(--text-base-lg);
            text-decoration: none;
            transition: all 0.2s;
            display: inline-block;
        }

        .btn-cta-outline:hover { background: white; color: var(--primary-dark); }

        /* ─── SECTION COMMONS ───────────────────────────── */
        .section-divider {
            border: none;
            border-top: 1px solid rgba(123,107,53,0.18);
            margin: 0;
        }

        .pill-label {
            font-size: var(--text-sm);
            font-weight: 700;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 0.6rem;
        }

        .sec-heading {
            font-family: 'IM Fell English', serif;
            font-size: 2.4rem;
            color: var(--dark);
            margin-bottom: 1.4rem;
            line-height: 1.25;
        }

        .sec-text {
            font-size: var(--text-md);
            line-height: 1.85;
            color: #4a4440;
            text-align: justify;
        }

        /* ─── OVERLAPPING IMAGES ───────────────────────── */
        .overlap-images {
            position: relative;
            height: 340px;
        }

        .overlap-img-back {
            position: absolute;
            top: 0; left: 0;
            width: 72%; height: 290px;
            object-fit: cover;
            border-radius: var(--radius-lg);
            z-index: 1;
        }

        .overlap-img-front {
            position: absolute;
            bottom: 0; right: 0;
            width: 45%; height: 310px;
            object-fit: cover;
            border-radius: var(--radius-lg);
            z-index: 2;
            box-shadow: -6px 6px 24px rgba(0,0,0,0.18);
        }

        .overlap-img-back--right { left: auto; right: 0; }
        .overlap-img-front--left { right: auto; left: 0; }

        /* ─── ABOUT SECTIONS ────────────────────────────── */
        .about-sec { padding: var(--section-py) 0; }
        .about-sec.bg-light-cream { background-color: var(--cream-light); }
        .about-sec.bg-cream       { background-color: var(--cream); }

        /* ─── FEATURES SECTION ──────────────────────────── */
        .features-sec {
            padding: var(--section-py) 0;
            background: var(--dark);
            color: white;
        }

        .features-sec .sec-heading { color: white; }

        .features-sec .sec-sub {
            color: rgba(255,255,255,0.6);
            font-size: var(--text-md);
            margin-bottom: 3rem;
        }

        .feature-card {
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: var(--radius-lg);
            padding: 1.8rem;
            height: 100%;
            transition: background 0.2s;
        }

        .feature-card:hover { background: rgba(255,255,255,0.1); }

        .feature-icon { font-size: var(--text-3xl); color: var(--gold); margin-bottom: 1rem; }

        .feature-card h5 { font-weight: 700; font-size: var(--text-md); margin-bottom: 0.5rem; }

        .feature-card p {
            font-size: var(--text-base);
            color: rgba(255,255,255,0.6);
            line-height: 1.65;
            margin: 0;
        }

        /* ─── FOOTER ────────────────────────────────────── */
        footer {
            background-color: var(--cream-light);
            border-top: 1px solid rgba(123,107,53,0.15);
        }

        .footer-top {
            max-width: 1200px;
            margin: 0 auto;
            padding: 60px 2rem 40px;
            display: flex;
            justify-content: space-between;
            gap: 3rem;
        }

        .footer-brand {
            display: flex;
            flex-direction: column;
            gap: 0.8rem;
            max-width: 260px;
        }

        .footer-brand p { font-size: var(--text-base); color: var(--muted); line-height: 1.7; margin: 0; }

        .footer-brand span { font-size: var(--text-sm); color: var(--muted); display: flex; align-items: center; gap: 6px; }

        .footer-brand span i { color: var(--gold); }

        .footer-col { display: flex; flex-direction: column; gap: 0.7rem; }

        .footer-col h4 {
            font-size: 0.85rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--dark);
            margin: 0 0 0.4rem 0;
        }

        .footer-col a { font-size: var(--text-base); color: var(--muted); text-decoration: none; transition: color 0.2s; }

        .footer-col a:hover { color: var(--gold); }

        .footer-bottom {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1.2rem 2rem;
            border-top: 1px solid rgba(123,107,53,0.12);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .footer-bottom p { font-size: var(--text-sm); color: var(--muted); margin: 0; }

        .footer-bottom div { display: flex; gap: 1.5rem; }

        .footer-bottom div a { font-size: var(--text-sm); color: var(--muted); text-decoration: none; transition: color 0.2s; }

        .footer-bottom div a:hover { color: var(--primary); }

        /* ─── RESPONSIVE ────────────────────────────────── */
        @media (max-width: 991px) {
            .navbar-inner { padding: 0.4rem 1.2rem; }
            .hero-heading { font-size: 2.5rem; }
            .nav-links, .nav-actions { display: none; }
        }
    </style>
</head>
<body>

    @yield('content')

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
