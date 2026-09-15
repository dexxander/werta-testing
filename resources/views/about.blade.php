@extends('layouts.app')
@section('content')
    @include('partials.navbar')

    <style>
        html { scroll-behavior: smooth; }
        section[id] { scroll-margin-top: 100px; }
        .about-header { padding: 70px 0 50px; text-align: center; }
        .about-header .sec-heading { margin: 0 auto 1rem; max-width: 700px; }
        .about-header .sec-text { max-width: 640px; margin: 0 auto; text-align: center; }
        .about-sec-compact { padding: calc(var(--section-py) * 0.75) 0; }
        .about-sec-expanded { padding: calc(var(--section-py) * 1.15) 0; }
        .step-row { display: flex; gap: 2rem; margin-top: 2.5rem; }
        .step-item { flex: 1; text-align: center; }
        .step-number { width: 48px; height: 48px; border-radius: var(--radius-full); background: var(--gold); color: white; font-family: 'IM Fell English', serif; font-size: var(--text-xl); display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; }
        .step-item h5 { font-weight: 700; font-size: var(--text-md); margin-bottom: 0.4rem; color: var(--dark); }
        .step-item p { font-size: var(--text-base); color: var(--muted); line-height: 1.6; margin: 0; }
        .trust-card { background: var(--cream-light); border: 1px solid rgba(123,107,53,0.15); border-radius: var(--radius-lg); padding: 1.8rem; height: 100%; }
        .bg-light-cream .trust-card { background: #ffffff; }
        .trust-card i { font-size: var(--text-2xl); color: var(--gold); margin-bottom: 1rem; display: block; }
        .trust-card h5 { font-weight: 700; font-size: var(--text-md); margin-bottom: 0.5rem; color: var(--dark); }
        .trust-card p { font-size: var(--text-base); color: var(--muted); line-height: 1.65; margin: 0; }
        .team-card { text-align: center; height: 100%; }
        .team-photo { width: 175px; height: 175px; border-radius: var(--radius-full); background: rgba(123,107,53,0.12); border: 2px solid rgba(196,168,64,0.3); margin: 0 auto 1.2rem; display: flex; align-items: center; justify-content: center; color: var(--muted); font-size: var(--text-sm); }
        .team-card h5 { font-weight: 700; font-size: var(--text-md); margin-bottom: 0.2rem; color: var(--dark); }
        .team-card p.role { font-size: var(--text-base); color: var(--gold); font-weight: 600; margin-bottom: 0.4rem; }
        .team-card p.bio { font-size: var(--text-base); color: var(--muted); line-height: 1.6; }
        .disclaimer-box { background: rgba(44,36,22,0.04); border-left: 4px solid var(--gold); border-radius: var(--radius-sm); padding: 1.5rem 1.8rem; font-size: var(--text-base); color: var(--muted); line-height: 1.7; }
        .placeholder-note { display: inline-block; background: #dc2626; border: 1px solid #b91c1c; border-radius: 4px; padding: 3px 10px; font-size: var(--text-xs); font-weight: 700; letter-spacing: 0.5px; text-transform: uppercase; color: #ffffff; margin-bottom: 0.75rem; }
        .image-placeholder-box { border: 2px dashed rgba(123,107,53,0.3); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center; color: var(--muted); font-size: var(--text-sm); font-weight: 600; background: rgba(123,107,53,0.04); min-height: 180px; }
        .cta-band { background: var(--dark); padding: var(--section-py) 0; text-align: center; }
        .cta-band .sec-heading { color: white; margin-bottom: 1.5rem; }
        .cta-band-actions { display: flex; gap: 1rem; justify-content: center; }
        @media (max-width: 991px) { .step-row { flex-wrap: wrap; } .step-item { flex: 0 0 calc(50% - 1rem); } }
        @media (max-width: 575px) { .step-item { flex: 0 0 100%; } }
    </style>

    <!-- SECTION 1: HERO / PAGE HEADER -->
    <section class="about-sec bg-cream about-header">
        <div class="container">
            <p class="pill-label">About Werta</p>
            <h1 class="sec-heading">Advancing Mental Wellbeing Across Malaysia</h1>
            <p class="sec-text">A Malaysian digital mental health platform connecting individuals, families, and licensed professionals through accessible, culturally sensitive care.</p>
        </div>
    </section>
    <hr class="section-divider">

    <!-- SECTION 2: OUR STORY -->
    <section class="about-sec about-sec-expanded bg-light-cream" id="our-story">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <p class="pill-label">Our Story</p>
                    <h2 class="sec-heading">Bridging the Mental Health Care Gap</h2>
                    <p class="sec-text">
                        Across Malaysia, many individuals face emotional and psychological challenges in silence. While dedicated and qualified counselors practice throughout the country, persistent barriers—such as social stigma, geographical constraints, and language differences—frequently prevent people from accessing timely support.
                    </p>
                    <p class="sec-text mt-3">
                        Werta was created to bridge this divide. We believe that reaching out for guidance should be safe, straightforward, and culturally resonant. By combining guided self-reflection, psychoeducational learning, and direct connections to licensed mental health professionals, Werta offers a welcoming entry point into compassionate care.
                    </p>
                    <p class="sec-text mt-3">
                        Currently developed as a working prototype, Werta demonstrates how digital innovation can empower practitioners and make emotional wellbeing support accessible to everyone in English, Bahasa Malaysia, and Mandarin.
                    </p>
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

    <!-- SECTION 3: MISSION & VISION -->
    <section class="about-sec about-sec-compact bg-cream" id="mission-vision">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-6">
                    <p class="pill-label">Our Vision</p>
                    <p class="sec-text">
                        To position Malaysia as a leading regional hub for accessible, inclusive, and high-quality mental health support — where every individual, regardless of background, has the tools and guidance to thrive emotionally and psychologically.
                    </p>
                </div>
                <div class="col-lg-6">
                    <p class="pill-label">Our Mission</p>
                    <p class="sec-text">
                        To drive inclusive mental health support by empowering licensed counselors, accelerating community awareness, and advancing culturally sensitive psychological care — delivered through ethical, compassionate, and impactful solutions in English, Bahasa Malaysia, and Mandarin.
                    </p>
                </div>
            </div>
        </div>
    </section>
    <hr class="section-divider">

    <!-- SECTION 4: WHO WE SERVE -->
    <section class="about-sec bg-light-cream" id="who-we-serve">
        <div class="container">
            <div class="text-center mb-5">
                <p class="pill-label">Who We Serve</p>
                <h2 class="sec-heading" style="max-width:600px; margin:0 auto;">Support Designed for Every Perspective</h2>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="trust-card">
                        <i class="bi bi-person-heart"></i>
                        <h5>Clients</h5>
                        <p>Individuals navigating personal challenges, life transitions, or emotional distress who need confidential self-reflection tools, structured learning, and direct access to licensed counselors.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="trust-card">
                        <i class="bi bi-person-badge"></i>
                        <h5>Counselors</h5>
                        <p>Registered mental health professionals seeking a unified digital practice platform to manage bookings, hold secure virtual or in-person sessions, and connect with clients across Malaysia.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="trust-card">
                        <i class="bi bi-people"></i>
                        <h5>Parents &amp; Guardians</h5>
                        <p>Families and caregivers who want transparent, ethical visibility into a dependent's wellbeing journey while respecting appropriate boundaries and professional guidance.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <hr class="section-divider">

    <!-- SECTION 5: HOW WERTA WORKS -->
    <section class="about-sec bg-cream" id="how-it-works">
        <div class="container">
            <div class="text-center mb-2">
                <p class="pill-label">How Werta Works</p>
                <h2 class="sec-heading" style="max-width:600px; margin:0 auto;">A Thoughtful Path to Care</h2>
            </div>
            <div class="step-row">
                <div class="step-item">
                    <div class="step-number">1</div>
                    <h5>Take an Assessment</h5>
                    <p>Complete a guided self-reflection questionnaire to understand your current wellbeing and stress patterns.</p>
                </div>
                <div class="step-item">
                    <div class="step-number">2</div>
                    <h5>Review Your Profile</h5>
                    <p>Receive an archetype breakdown with personalized growth focus areas and actionable wellbeing insights.</p>
                </div>
                <div class="step-item">
                    <div class="step-number">3</div>
                    <h5>Connect with a Counselor</h5>
                    <p>Explore counselor profiles to match with a qualified practitioner aligned with your needs and language preferences.</p>
                </div>
                <div class="step-item">
                    <div class="step-number">4</div>
                    <h5>Continue with Care</h5>
                    <p>Attend tailored counseling sessions, track daily moods, and access interactive psychoeducational modules.</p>
                </div>
            </div>
        </div>
    </section>
    <hr class="section-divider">

    <!-- SECTION 6: OUR VALUES -->
    <section class="about-sec about-sec-compact bg-light-cream" id="values">
        <div class="container">
            <div class="text-center mb-5">
                <p class="pill-label">Our Values</p>
                <h2 class="sec-heading" style="max-width:600px; margin:0 auto;">Principles That Anchor Our Work</h2>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="trust-card">
                        <i class="bi bi-shield-lock-fill"></i>
                        <h5>Privacy First</h5>
                        <p>We treat personal and emotional disclosures with absolute discretion, ensuring user trust through ethical handling of sensitive data.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="trust-card">
                        <i class="bi bi-heart-fill"></i>
                        <h5>Compassion</h5>
                        <p>Every feature and interaction is designed with empathy, offering a safe, non-judgmental space for healing and personal growth.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="trust-card">
                        <i class="bi bi-globe2"></i>
                        <h5>Inclusivity</h5>
                        <p>Care should reflect Malaysia's rich diversity, providing culturally attuned support in English, Bahasa Malaysia, and Mandarin.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="trust-card">
                        <i class="bi bi-mortarboard-fill"></i>
                        <h5>Education</h5>
                        <p>We provide accessible psychoeducational resources that empower individuals and families with lifelong mental health literacy.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <hr class="section-divider">

    <!-- SECTION 7: THE TEAM -->
    <section class="about-sec about-sec-expanded bg-cream" id="team">
        <div class="container text-center">
            <span class="placeholder-note">Filler: The Team</span>
            <p class="pill-label">The Team Behind Werta</p>
            <h2 class="sec-heading" style="max-width:600px; margin:0 auto 0.8rem;">Dedicated to Mental Health Innovation</h2>
            <p class="sec-text text-center mx-auto mb-5" style="max-width:680px;">Developed by a team of five under academic supervision, Werta is a working prototype exploring how digital tools can enhance mental wellbeing and care delivery in Malaysia.</p>
            <div class="row g-4 justify-content-center">
                <div class="col-lg-4 col-md-6">
                    <div class="team-card">
                        <div class="team-photo">Photo</div>
                        <h5>Team Member 1</h5>
                        <p class="role">Role Title</p>
                        <p class="bio">Short bio to be added.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="team-card">
                        <div class="team-photo">Photo</div>
                        <h5>Team Member 2</h5>
                        <p class="role">Role Title</p>
                        <p class="bio">Short bio to be added.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="team-card">
                        <div class="team-photo">Photo</div>
                        <h5>Team Member 3</h5>
                        <p class="role">Role Title</p>
                        <p class="bio">Short bio to be added.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="team-card">
                        <div class="team-photo">Photo</div>
                        <h5>Team Member 4</h5>
                        <p class="role">Role Title</p>
                        <p class="bio">Short bio to be added.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="team-card">
                        <div class="team-photo">Photo</div>
                        <h5>Team Member 5</h5>
                        <p class="role">Role Title</p>
                        <p class="bio">Short bio to be added.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <hr class="section-divider">

    <!-- SECTION 8: TRUST & PRIVACY -->
    <section class="about-sec bg-light-cream" id="trust-privacy">
        <div class="container" id="trust-credentials">
            <div class="text-center mb-5">
                <span class="placeholder-note">Filler: Trust &amp; Credentials</span>
                <p class="pill-label">Trust &amp; Privacy</p>
                <h2 class="sec-heading" style="max-width:700px; margin:0 auto;">Our Commitments to Your Safety and Care</h2>
            </div>
            <div class="row g-4 mb-5">
                <div class="col-md-4">
                    <div class="trust-card">
                        <i class="bi bi-patch-check-fill"></i>
                        <h5>Vetted Professional Standards</h5>
                        <p>We are building rigorous verification protocols to ensure all practicing counselors on Werta hold recognized credentials and professional standing before offering sessions.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="trust-card">
                        <i class="bi bi-shield-lock-fill"></i>
                        <h5>Confidential Records &amp; Sessions</h5>
                        <p>We are committed to strict privacy standards. Your self-reflection results, appointment logs, and personal notes are treated with absolute discretion.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="trust-card">
                        <i class="bi bi-mortarboard-fill"></i>
                        <h5>Evidence-Informed Content</h5>
                        <p>Our self-assessment questions and educational modules are developed with qualified psychological input to provide grounded, constructive guidance.</p>
                    </div>
                </div>
            </div>

            <div class="row g-4" id="privacy">
                <div class="col-lg-6">
                    <p class="pill-label">Privacy Commitment</p>
                    <div class="disclaimer-box">
                        We treat all personal details, assessment reflections, and communication as strictly confidential. Your information is never sold, shared, or disclosed to unauthorized parties without your clear consent. As a working prototype, we are establishing responsible data handling and privacy practices to safeguard every user who engages with our platform.
                    </div>
                </div>
                <div class="col-lg-6">
                    <p class="pill-label">Please Note</p>
                    <div class="disclaimer-box">
                        Werta's assessments are self-reflection tools, not clinical diagnoses. Full results are best discussed with a counselor. This platform does not replace professional medical advice, diagnosis, or clinical psychiatric treatment. If you are experiencing acute distress, a mental health crisis, or thoughts of self-harm, please contact emergency services or reach out to a 24/7 crisis hotline immediately.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 9: CTA -->
    <section class="cta-band">
        <div class="container">
            <h2 class="sec-heading">Ready to Take the Next Step?</h2>
            <p class="sec-text" style="color: rgba(255,255,255,0.8); text-align: center; max-width: 600px; margin: -0.5rem auto 2rem;">
                Whether you want to explore your wellbeing with a self-guided assessment or connect directly with a licensed counselor, Werta is here to support you.
            </p>
            <div class="cta-band-actions">
                <a href="{{ url('/assessment') }}" class="btn-cta-filled">Take the Assessment</a>
                <a href="{{ url('/counselors') }}" class="btn-cta-outline">Browse Counselors</a>
            </div>
        </div>
    </section>

    @include('partials.footer')
@endsection
