{{-- Final CTA Section --}}
<section class="el-section bg-dark el-final-cta" style="padding:100px 0; position:relative; overflow:hidden;">

    {{-- Signature: oversized watermark anchoring the "you're almost there" study mood --}}
    <i class="bi bi-mortarboard-fill el-final-cta-watermark" aria-hidden="true"></i>

    {{-- Ambient study-tool icons, drifting gently --}}
    <i class="bi bi-pencil-fill el-final-cta-mote" style="top:22%; left:9%;" aria-hidden="true"></i>
    <i class="bi bi-lightbulb-fill el-final-cta-mote" style="top:68%; left:15%;" aria-hidden="true"></i>
    <i class="bi bi-bookmark-star-fill el-final-cta-mote" style="top:24%; right:11%;" aria-hidden="true"></i>
    <i class="bi bi-journal-text el-final-cta-mote" style="top:62%; right:17%;" aria-hidden="true"></i>

    <div class="el-container text-center" style="position:relative; z-index:2;">

        @if(!empty($statistics['certificates_issued']))
            <p class="el-final-cta-callback">
                <i class="bi bi-patch-check-fill"></i>
                {{ number_format($statistics['certificates_issued']) }} learners have already earned their certificate
            </p>
        @endif

        <div class="el-pill">Start Today</div>
        <h2 class="el-heading el-heading--light" style="font-size:2.6rem;max-width:700px;margin:0 auto 1rem;">
            Ready to Transform Your Future?
        </h2>
        <p class="el-subtext el-subtext--light mx-auto" style="margin-bottom:2rem;">
            Join thousands of learners who are building new skills, advancing their careers, and achieving their goals with Werta's expert-led courses.
        </p>
        <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
            <a href="#el-courses" class="el-btn-primary" style="padding:1rem 2.5rem;font-size:1rem;">
                <i class="bi bi-rocket-takeoff"></i> Get Started Now
            </a>
            <a href="#el-paths" class="el-btn-outline el-btn-outline--light" style="padding:1rem 2.5rem;font-size:1rem;">
                Explore Learning Paths
            </a>
        </div>

        <p class="el-final-cta-note">No credit card required &middot; Learn at your own pace &middot; Cancel anytime</p>
    </div>
</section>

<style>
    /* ─── FINAL CTA: WATERMARK ────────────────────────────── */
    .el-final-cta-watermark {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 26rem;
        line-height: 1;
        color: rgba(255,255,255,0.03);
        z-index: 1;
        pointer-events: none;
    }

    /* ─── FINAL CTA: AMBIENT STUDY ICONS ──────────────────── */
    .el-final-cta-mote {
        position: absolute;
        font-size: 1.5rem;
        color: rgba(196,168,64,0.35);
        z-index: 1;
        pointer-events: none;
        animation: elCtaFloat 6s ease-in-out infinite;
    }

    .el-final-cta-mote:nth-of-type(2) { animation-duration: 7s; animation-delay: 0.4s; }
    .el-final-cta-mote:nth-of-type(3) { animation-duration: 5.5s; animation-delay: 0.8s; }
    .el-final-cta-mote:nth-of-type(4) { animation-duration: 6.5s; animation-delay: 1.2s; }

    @keyframes elCtaFloat {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-14px); }
    }

    @media (prefers-reduced-motion: reduce) {
        .el-final-cta-mote { animation: none; }
    }

    @media (max-width: 767px) {
        .el-final-cta-watermark { font-size: 14rem; }
        .el-final-cta-mote { display: none; }
    }

    /* ─── FINAL CTA: CERTIFICATE CALLBACK LINE ────────────── */
    .el-final-cta-callback {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.85rem;
        color: rgba(255,255,255,0.6);
        background: rgba(255,255,255,0.06);
        border: 1px solid rgba(255,255,255,0.12);
        border-radius: 20px;
        padding: 0.5rem 1.1rem;
        margin-bottom: 1.5rem;
    }

    .el-final-cta-callback i {
        color: var(--gold);
    }

    /* ─── FINAL CTA: REASSURANCE NOTE ─────────────────────── */
    .el-final-cta-note {
        margin: 1.5rem 0 0;
        font-size: 0.82rem;
        color: rgba(255,255,255,0.45);
    }
</style>