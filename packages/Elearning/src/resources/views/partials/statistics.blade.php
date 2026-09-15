{{-- ============================================================
     TEMP DUMMY DATA — FOR PREVIEW ONLY
     This @php block overrides $statistics with fake numbers so you
     can see the count-up animation actually count up to something.
     It only exists in this file — your controller's zeroed-out
     'statistics' array is untouched.

     TO REMOVE: delete the entire @php ... @endphp block below
     (from "TEMP DUMMY DATA START" to "TEMP DUMMY DATA END").
     Once deleted, $statistics goes back to whatever the controller
     actually passes in (currently all zeros).
     ============================================================ --}}
@php
    // TEMP DUMMY DATA START
    $statistics = [
        'total_students' => 85400,
        'courses_available' => 1240,
        'certificates_issued' => 62800,
    ];
    // TEMP DUMMY DATA END
@endphp

{{-- Learning Statistics Section --}}
<section class="el-section bg-cream-light">
    <div class="el-container">
        <div class="text-center mb-5">
            <div class="el-pill">By The Numbers</div>
            <h2 class="el-heading">Learning Statistics</h2>
        </div>
        <div class="row g-4 text-center">
            <div class="col-6 col-md-4">
                <div class="el-card el-stat-card">
                    <div class="el-feature-icon el-stat-icon">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div class="el-stat-number" data-count-to="{{ $statistics['total_students'] ?? 0 }}">0</div>
                    <div class="el-stat-label">Total Students</div>
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="el-card el-stat-card">
                    <div class="el-feature-icon el-stat-icon">
                        <i class="bi bi-journal-bookmark-fill"></i>
                    </div>
                    <div class="el-stat-number" data-count-to="{{ $statistics['courses_available'] ?? 0 }}">0</div>
                    <div class="el-stat-label">Courses Available</div>
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="el-card el-stat-card">
                    <div class="el-feature-icon el-stat-icon">
                        <i class="bi bi-patch-check-fill"></i>
                    </div>
                    <div class="el-stat-number" data-count-to="{{ $statistics['certificates_issued'] ?? 0 }}">0</div>
                    <div class="el-stat-label">Certificates Issued</div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    /* ─── STATISTICS: CARD LAYOUT ─────────────────────────── */
    .el-stat-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 2.25rem 1.5rem;
        height: 100%;
    }

    .el-stat-icon {
        margin-bottom: 1.25rem;
    }

    .el-stat-icon i {
        font-size: 1.4rem;
    }
</style>

{{-- Count-up animation, respects reduced-motion and only runs once per element --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        const counters = document.querySelectorAll('.el-stat-number[data-count-to]');

        const animateCount = function (el) {
            const target = parseInt(el.getAttribute('data-count-to'), 10) || 0;

            if (prefersReducedMotion || target === 0) {
                el.textContent = target.toLocaleString();
                return;
            }

            const duration = 1200;
            const start = performance.now();

            function tick(now) {
                const progress = Math.min((now - start) / duration, 1);
                const eased = 1 - Math.pow(1 - progress, 3);
                el.textContent = Math.round(eased * target).toLocaleString();
                if (progress < 1) {
                    requestAnimationFrame(tick);
                }
            }

            requestAnimationFrame(tick);
        };

        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        animateCount(entry.target);
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.5 });

            counters.forEach(function (el) { observer.observe(el); });
        } else {
            counters.forEach(function (el) { animateCount(el); });
        }
    });
</script>