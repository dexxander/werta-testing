@extends('layouts.app')
@section('content')
    @include('partials.navbar')

    <style>
        html { scroll-behavior: smooth; }
        section[id] { scroll-margin-top: 100px; }
        .about-header { padding: 70px 0 50px; text-align: center; }
        .about-header .sec-heading { margin: 0 auto 1rem; max-width: 700px; }
        .about-header .sec-text { max-width: 640px; margin: 0 auto; text-align: center; }
        .step-row { display: flex; gap: 2rem; margin-top: 2.5rem; }
        .step-item { flex: 1; text-align: center; }
        .step-number { width: 48px; height: 48px; border-radius: var(--radius-full); background: var(--gold); color: white; font-family: 'IM Fell English', serif; font-size: var(--text-xl); display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; }
        .step-item h5 { font-weight: 700; font-size: var(--text-md); margin-bottom: 0.4rem; color: var(--dark); }
        .step-item p { font-size: var(--text-base); color: var(--muted); line-height: 1.6; margin: 0; }
        .trust-card { background: var(--cream-light); border: 1px solid rgba(123,107,53,0.15); border-radius: var(--radius-lg); padding: 1.8rem; height: 100%; }
        .trust-card i { font-size: var(--text-2xl); color: var(--gold); margin-bottom: 1rem; display: block; }
        .trust-card h5 { font-weight: 700; font-size: var(--text-md); margin-bottom: 0.5rem; color: var(--dark); }
        .trust-card p { font-size: var(--text-base); color: var(--muted); line-height: 1.65; margin: 0; }
        .value-badge { background: rgba(196,168,64,0.1); border: 1px solid rgba(196,168,64,0.3); border-radius: var(--radius-pill); padding: 0.6rem 1.4rem; font-size: var(--text-base); font-weight: 600; color: var(--primary-dark); display: inline-flex; align-items: center; gap: 8px; }
        .team-card { text-align: center; }
        .team-photo { width: 140px; height: 140px; border-radius: var(--radius-full); background: rgba(123,107,53,0.12); border: 2px solid rgba(196,168,64,0.3); margin: 0 auto 1rem; display: flex; align-items: center; justify-content: center; color: var(--muted); font-size: 0.75rem; }
        .team-card h5 { font-weight: 700; font-size: var(--text-md); margin-bottom: 0.2rem; color: var(--dark); }
        .team-card p.role { font-size: var(--text-base); color: var(--gold); font-weight: 600; margin-bottom: 0.4rem; }
        .team-card p.bio { font-size: var(--text-base); color: var(--muted); line-height: 1.6; }
        .disclaimer-box { background: rgba(44,36,22,0.04); border-left: 4px solid var(--gold); border-radius: var(--radius-sm); padding: 1.5rem 1.8rem; font-size: var(--text-base); color: var(--muted); line-height: 1.7; }
        .placeholder-note { display: inline-block; background: rgba(196,168,64,0.15); border: 1px dashed var(--gold); border-radius: 4px; padding: 2px 8px; font-size: var(--text-xs); font-weight: 700; letter-spacing: 0.5px; text-transform: uppercase; color: var(--primary-dark); margin-bottom: 0.6rem; }
        .image-placeholder-box { border: 2px dashed rgba(123,107,53,0.3); border-radius: 14px; display: flex; align-items: center; justify-content: center; color: var(--muted); font-size: 0.85rem; font-weight: 600; background: rgba(123,107,53,0.04); min-height: 180px; }
        .cta-band { background: var(--dark); padding: var(--section-py) 0; text-align: center; }
        .cta-band .sec-heading { color: white; margin-bottom: 1.5rem; }
        .cta-band-actions { display: flex; gap: 1rem; justify-content: center; }
        @media (max-width: 767px) { .step-row { flex-direction: column; } }
    </style>

    <!-- SECTION: HERO -->
    <section class="about-sec bg-cream about-header">
        <div class="container">
            <span class="placeholder-note">Filler: Intro Statement</span>
            <p class="pill-label">About Werta</p>
            <h1 class="sec-heading">Lorem Ipsum Dolor Sit Amet Consectetur</h1>
            <p class="sec-text">Adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua ut enim ad minim veniam.</p>
        </div>
    </section>
    <hr class="section-divider">

    <!-- SECTION: OUR STORY -->
    <section class="about-sec bg-light-cream" id="our-story">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <span class="placeholder-note">Filler: Our Story</span>
                    <p class="pill-label">Our Story</p>
                    <h2 class="sec-heading">Lorem Ipsum Dolor Sit Amet</h2>
                    <p class="sec-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
                </div>
                <div class="col-lg-6">
                    <div class="overlap-images">
                        <div class="overlap-img-back image-placeholder-box">Image Placeholder</div>
                        <div class="overlap-img-front image-placeholder-box">Image Placeholder</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <hr class="section-divider">

    <!-- SECTION: WHO WE SERVE -->
    <section class="about-sec bg-cream" id="who-we-serve">
        <div class="container">
            <span class="placeholder-note">Filler: Who We Serve</span>
            <div class="text-center mb-4">
                <p class="pill-label">Who We Serve</p>
                <h2 class="sec-heading" style="max-width:600px; margin:0 auto;">Lorem Ipsum Dolor Sit Amet Consectetur</h2>
            </div>
            <div class="row g-4">
                <div class="col-md-4"><div class="trust-card"><i class="bi bi-person-heart"></i><h5>Clients</h5><p>Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore.</p></div></div>
                <div class="col-md-4"><div class="trust-card"><i class="bi bi-person-badge"></i><h5>Counselors</h5><p>Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore.</p></div></div>
                <div class="col-md-4"><div class="trust-card"><i class="bi bi-people"></i><h5>Guardians</h5><p>Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore.</p></div></div>
            </div>
        </div>
    </section>
    <hr class="section-divider">

    <!-- SECTION: TRUST & CREDENTIALS -->
    <section class="about-sec bg-light-cream" id="trust-credentials">
        <div class="container">
            <span class="placeholder-note">Filler: Trust & Credentials</span>
            <div class="text-center mb-4"><p class="pill-label">Why You Can Trust Us</p><h2 class="sec-heading" style="max-width:700px; margin:0 auto;">Lorem Ipsum Dolor Sit Amet</h2></div>
            <div class="row g-4">
                <div class="col-md-4"><div class="trust-card"><i class="bi bi-patch-check-fill"></i><h5>Lorem Ipsum</h5><p>Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua ut enim ad minim veniam.</p></div></div>
                <div class="col-md-4"><div class="trust-card"><i class="bi bi-shield-lock-fill"></i><h5>Lorem Ipsum</h5><p>Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua ut enim ad minim veniam.</p></div></div>
                <div class="col-md-4"><div class="trust-card"><i class="bi bi-mortarboard-fill"></i><h5>Lorem Ipsum</h5><p>Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua ut enim ad minim veniam.</p></div></div>
            </div>
        </div>
    </section>

    <!-- SECTION: VALUES -->
    <section class="about-sec bg-cream" id="values">
        <div class="container text-center"><span class="placeholder-note">Filler: Values</span><p class="pill-label">Our Values</p><h2 class="sec-heading" style="max-width:600px; margin:0 auto 2rem;">Lorem Ipsum Dolor Sit Amet</h2><div class="d-flex flex-wrap justify-content-center gap-3"><span class="value-badge"><i class="bi bi-lock-fill"></i> Value One</span><span class="value-badge"><i class="bi bi-heart-fill"></i> Value Two</span><span class="value-badge"><i class="bi bi-globe2"></i> Value Three</span><span class="value-badge"><i class="bi bi-mortarboard-fill"></i> Value Four</span></div></div>
    </section>
    <hr class="section-divider">

    <!-- SECTION: THE TEAM -->
    <section class="about-sec bg-light-cream" id="team">
        <div class="container"><span class="placeholder-note">Filler: The Team</span><div class="text-center mb-5"><p class="pill-label">The Team Behind Werta</p><h2 class="sec-heading" style="max-width:600px; margin:0 auto;">Lorem Ipsum Dolor Sit Amet</h2></div><div class="row g-4 justify-content-center"><div class="col-md-3"><div class="team-card"><div class="team-photo">Photo</div><h5>Lorem Ipsum</h5><p class="role">Lorem Ipsum Title</p><p class="bio">Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod.</p></div></div></div></div>
    </section>
    <hr class="section-divider">

    <!-- SECTION: PRIVACY & DISCLAIMER -->
    <section class="about-sec bg-cream" id="privacy">
        <div class="container"><span class="placeholder-note">Filler: Privacy & Disclaimer</span><div class="row g-4"><div class="col-lg-6"><p class="pill-label">Privacy Commitment</p><div class="disclaimer-box">Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua ut enim ad minim veniam quis nostrud exercitation.</div></div><div class="col-lg-6"><p class="pill-label">Please Note</p><div class="disclaimer-box">Ut enim ad minim veniam quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat duis aute irure dolor in reprehenderit in voluptate velit esse.</div></div></div></div>
    </section>

    <!-- SECTION: CTA -->
    <section class="cta-band"><div class="container"><h2 class="sec-heading">Lorem Ipsum Dolor Sit Amet Consectetur</h2><div class="cta-band-actions"><a href="{{ url('/assessment') }}" class="btn-cta-filled">Take the Assessment</a><a href="{{ url('/counselors') }}" class="btn-cta-outline">Browse Counselors</a></div></div></section>

    @include('partials.footer')
@endsection
