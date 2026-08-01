{{-- eLearning Hero Section --}}
<section class="hero el-hero" style="padding: 110px 0 100px; position: relative; overflow: hidden;">
    <div style="position:absolute;top:0;left:0;width:100%;height:100%;background:linear-gradient(135deg, rgba(44,36,22,0.85) 0%, rgba(87,75,34,0.75) 100%);z-index:1;"></div>

    {{-- Signature element: a learning-journey path from "explore" to "certify", not just a decorative squiggle --}}
    <svg class="el-hero-path" viewBox="0 0 1200 500" preserveAspectRatio="none"
         style="position:absolute;top:0;left:0;width:100%;height:100%;z-index:1;opacity:0.16;pointer-events:none;">
        <path d="M -50 420 C 250 420, 300 120, 620 160 S 1050 380, 1300 60"
              fill="none" stroke="var(--gold)" stroke-width="2" stroke-dasharray="2 14" stroke-linecap="round" />
    </svg>

    <div class="el-hero-waypoint" style="top: 78%; left: 19%;"><i class="bi bi-search"></i></div>
    <div class="el-hero-waypoint" style="top: 28%; left: 51%;"><i class="bi bi-code-slash"></i></div>
    <div class="el-hero-waypoint" style="top: 34%; left: 76%;"><i class="bi bi-palette2"></i></div>
    <div class="el-hero-waypoint el-hero-waypoint--gold" style="top: 12%; left: 87%;"><i class="bi bi-mortarboard-fill"></i></div>

    <div class="container" style="position:relative;z-index:2;">
        <div class="row align-items-center">
            <div class="col-lg-7 el-hero-copy">
                <div class="el-pill">E-Learning Platform</div>
                <h1 class="hero-heading" style="font-size:3rem;margin-bottom:1.2rem;">
                    Learn New Skills.<br><span style="color:var(--gold);">Earn</span> Real Certificates.
                </h1>
                <p style="font-size:1.1rem;color:rgba(255,255,255,0.75);line-height:1.8;max-width:520px;margin-bottom:0;">
                    Self-paced courses taught by expert instructors — go from your first lesson to a certificate you can show off, on a schedule that works for you.
                </p>

                <div class="el-hero-search">
                    <input type="text" placeholder="Search for courses, topics, or skills...">
                    <button><i class="bi bi-search"></i> Search</button>
                </div>

                @if(!empty($categories))
                    <div class="el-hero-chips">
                        <span class="el-hero-chip-label">Popular:</span>
                        @foreach($categories as $category)
                            <a href="{{ $category['url'] ?? '#el-courses' }}" class="el-hero-chip">{{ $category['name'] }}</a>
                        @endforeach
                    </div>
                @endif

                <div style="display:flex;gap:1rem;margin-top:2rem;flex-wrap:wrap;align-items:center;">
                    <a href="#el-courses" class="btn-cta-filled">Start Learning Free</a>
                    <a href="#el-paths" class="btn-cta-outline">View Learning Paths</a>
                </div>

                @if(!empty($statistics['total_students']))
                    <p class="el-hero-trust">
                        <i class="bi bi-people-fill" style="color:var(--gold);"></i>
                        Join {{ $statistics['total_students'] }} learners already growing their skills
                    </p>
                @endif
            </div>

            <div class="col-lg-5 d-none d-lg-block">
                <div class="el-hero-visual">
                    <div class="el-hero-card">
                        <div class="el-hero-card-media">
                            <i class="bi {{ !empty($continue_course) ? ($continue_course['icon'] ?? 'bi-play-circle-fill') : 'bi-play-circle-fill' }}"></i>
                        </div>
                        <div class="el-hero-card-body">
                            @if(!empty($continue_course))
                                <span class="el-hero-card-tag">Continue learning</span>
                                <h5>{{ $continue_course['title'] }}</h5>
                                <p>with {{ $continue_course['instructor_name'] }}</p>

                                <div class="el-hero-card-progress">
                                    <div class="el-hero-card-progress-bar">
                                        <div class="el-hero-card-progress-fill" style="width: {{ $continue_course['progress_percent'] }}%;"></div>
                                    </div>
                                    <span>{{ $continue_course['progress_percent'] }}% complete</span>
                                </div>

                                @if(!empty($continue_course['next_lesson_title']))
                                    <div class="el-hero-card-next">
                                        Next: {{ $continue_course['next_lesson_title'] }}
                                    </div>
                                @endif

                                <a href="{{ $continue_course['resume_url'] ?? '#' }}" class="el-hero-card-btn">
                                    Resume Lesson <i class="bi bi-arrow-right"></i>
                                </a>
                            @else
                                <span class="el-hero-card-tag">Get started</span>
                                <h5>Your learning journey starts here</h5>
                                <p>Browse courses and enrol in minutes — no experience needed.</p>
                                <a href="#el-courses" class="el-hero-card-btn">
                                    Explore Courses <i class="bi bi-arrow-right"></i>
                                </a>
                            @endif
                        </div>
                    </div>

                    @if(!empty($statistics['certificates_issued']))
                        <div class="el-hero-badge el-hero-badge--top">
                            <i class="bi bi-patch-check-fill"></i>
                            <div>
                                <strong>{{ $statistics['certificates_issued'] }}</strong>
                                <span>Certificates issued</span>
                            </div>
                        </div>
                    @endif

                    @if(!empty($statistics['expert_instructors']))
                        <div class="el-hero-badge el-hero-badge--bottom">
                            <i class="bi bi-mortarboard-fill"></i>
                            <div>
                                <strong>{{ $statistics['expert_instructors'] }}</strong>
                                <span>Expert instructors</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    /* ─── HERO: LEARNING-JOURNEY WAYPOINTS ────────────────── */
    .el-hero-waypoint {
        position: absolute;
        z-index: 1;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: rgba(255,255,255,0.08);
        border: 1px solid rgba(255,255,255,0.15);
        display: flex;
        align-items: center;
        justify-content: center;
        color: rgba(255,255,255,0.55);
        font-size: 0.95rem;
        pointer-events: none;
    }

    .el-hero-waypoint--gold {
        background: rgba(196,168,64,0.18);
        border-color: var(--gold);
        color: var(--gold);
    }

    @media (max-width: 991px) {
        .el-hero-waypoint { display: none; }
    }

    /* ─── HERO: SEARCH CHIPS ──────────────────────────────── */
    .el-hero-chips {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.6rem;
        margin-top: 1rem;
    }

    .el-hero-chip-label {
        font-size: 0.8rem;
        color: rgba(255,255,255,0.5);
        font-weight: 600;
        margin-right: 0.2rem;
    }

    .el-hero-chip {
        font-size: 0.8rem;
        color: rgba(255,255,255,0.75);
        border: 1px solid rgba(255,255,255,0.2);
        border-radius: 20px;
        padding: 0.35rem 0.9rem;
        text-decoration: none;
        transition: border-color 0.2s, color 0.2s, background 0.2s;
    }

    .el-hero-chip:hover {
        color: var(--dark);
        background: var(--gold);
        border-color: var(--gold);
    }

    /* ─── HERO: TRUST LINE ────────────────────────────────── */
    .el-hero-trust {
        margin: 1.5rem 0 0;
        font-size: 0.85rem;
        color: rgba(255,255,255,0.65);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* ─── HERO: VISUAL WRAPPER (card + floating badges) ───── */
    .el-hero-visual {
        position: relative;
        padding: 20px 30px 40px 0;
    }

    /* ─── HERO: LESSON-PREVIEW CARD ───────────────────────── */
    .el-hero-card {
        background: var(--cream-light);
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 24px 60px rgba(0,0,0,0.35);
    }

    .el-hero-card-media {
        height: 150px;
        background: linear-gradient(135deg, rgba(196,168,64,0.25), rgba(44,36,22,0.35));
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .el-hero-card-media i {
        font-size: 3rem;
        color: var(--gold);
    }

    .el-hero-card-body {
        padding: 1.75rem;
    }

    .el-hero-card-tag {
        display: inline-block;
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        color: var(--gold);
        margin-bottom: 0.6rem;
    }

    .el-hero-card-body h5 {
        font-weight: 700;
        font-size: 1.05rem;
        color: var(--dark);
        margin: 0 0 0.3rem;
    }

    .el-hero-card-body p {
        font-size: 0.85rem;
        color: var(--muted);
        margin: 0 0 1.25rem;
    }

    .el-hero-card-progress {
        margin-bottom: 0.9rem;
    }

    .el-hero-card-progress-bar {
        width: 100%;
        height: 6px;
        border-radius: 4px;
        background: rgba(123,107,53,0.15);
        overflow: hidden;
        margin-bottom: 0.5rem;
    }

    .el-hero-card-progress-fill {
        height: 100%;
        background: var(--gold);
        border-radius: 4px;
    }

    .el-hero-card-progress span {
        font-size: 0.78rem;
        color: var(--muted);
        font-weight: 600;
    }

    .el-hero-card-next {
        font-size: 0.82rem;
        color: var(--dark);
        background: rgba(123,107,53,0.06);
        border-radius: 8px;
        padding: 0.7rem 0.9rem;
        margin-bottom: 1.25rem;
    }

    .el-hero-card-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        width: 100%;
        background: var(--primary-dark);
        color: #fff;
        text-decoration: none;
        font-weight: 700;
        font-size: 0.9rem;
        padding: 0.8rem;
        border-radius: 8px;
        transition: background 0.2s;
    }

    .el-hero-card-btn:hover {
        background: var(--dark);
        color: #fff;
    }

    /* ─── HERO: FLOATING TRUST BADGES ──────────────────────── */
    .el-hero-badge {
        position: absolute;
        display: flex;
        align-items: center;
        gap: 0.7rem;
        background: var(--cream-light);
        border-radius: 12px;
        padding: 0.8rem 1.1rem;
        box-shadow: 0 12px 30px rgba(0,0,0,0.25);
        z-index: 3;
    }

    .el-hero-badge i {
        font-size: 1.3rem;
        color: var(--gold);
    }

    .el-hero-badge strong {
        display: block;
        font-size: 1rem;
        color: var(--dark);
        line-height: 1.1;
    }

    .el-hero-badge span {
        display: block;
        font-size: 0.7rem;
        color: var(--muted);
        white-space: nowrap;
    }

    .el-hero-badge--top {
        top: -10px;
        right: 0;
    }

    .el-hero-badge--bottom {
        bottom: 10px;
        left: -30px;
    }

    /* ─── HERO: LOAD-IN MOTION ────────────────────────────── */
    .el-hero-copy {
        animation: elHeroRise 0.6s ease both;
    }

    .el-hero-visual {
        animation: elHeroRise 0.6s 0.15s ease both;
    }

    .el-hero-badge--top {
        animation: elHeroRise 0.6s 0.4s ease both, elHeroFloat 4s 1s ease-in-out infinite;
        --el-badge-rotate: 4deg;
    }

    .el-hero-badge--bottom {
        animation: elHeroRise 0.6s 0.55s ease both, elHeroFloat 4.5s 1.2s ease-in-out infinite;
        --el-badge-rotate: -3deg;
    }

    @keyframes elHeroRise {
        from { opacity: 0; transform: translateY(16px) rotate(var(--el-badge-rotate, 0deg)); }
        to { opacity: 1; transform: translateY(0) rotate(var(--el-badge-rotate, 0deg)); }
    }

    @keyframes elHeroFloat {
        0%, 100% { transform: rotate(var(--el-badge-rotate, 0deg)) translateY(0); }
        50% { transform: rotate(var(--el-badge-rotate, 0deg)) translateY(-6px); }
    }

    @media (prefers-reduced-motion: reduce) {
        .el-hero-copy, .el-hero-visual, .el-hero-badge--top, .el-hero-badge--bottom {
            animation: none;
        }
        .el-hero-badge--top { transform: rotate(4deg); }
        .el-hero-badge--bottom { transform: rotate(-3deg); }
    }

    @media (max-width: 991px) {
        .el-hero-path { opacity: 0.08; }
    }
</style>