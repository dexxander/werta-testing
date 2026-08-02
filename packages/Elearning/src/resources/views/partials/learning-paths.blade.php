{{-- ============================================================
     TEMP DUMMY DATA — FOR PREVIEW ONLY
     This @php block overrides $learning_paths with fake data so
     you can see the UI. It only exists in this file — your
     controller's 'learning_paths' => [] empty state is untouched.

     TO REMOVE: delete the entire @php ... @endphp block below
     (from "TEMP DUMMY DATA START" to "TEMP DUMMY DATA END").
     Once deleted, $learning_paths goes back to whatever the
     controller actually passes in (currently an empty array).
     ============================================================ --}}
@php
    // TEMP DUMMY DATA START
    $learning_paths = [
        ['name' => 'Full-Stack Developer', 'icon' => 'laptop', 'course_count' => 14, 'url' => '#'],
        ['name' => 'Data Analyst', 'icon' => 'bar-chart-line', 'course_count' => 9, 'url' => '#'],
        ['name' => 'UX/UI Designer', 'icon' => 'palette2', 'course_count' => 11, 'url' => '#'],
        ['name' => 'Digital Marketer', 'icon' => 'megaphone', 'course_count' => 8, 'url' => '#'],
        ['name' => 'Cybersecurity Specialist', 'icon' => 'shield-lock', 'course_count' => 10, 'url' => '#'],
    ];
    // TEMP DUMMY DATA END
@endphp

{{-- Learning Paths Section --}}
<section class="el-section" style="background-color: var(--dark);" id="el-paths">
    <div class="el-container">
        <div class="text-center mb-5 position-relative z-1">
            <div class="el-pill">Career Tracks</div>
            <h2 class="el-heading el-heading--light">Learning Paths</h2>
            <p class="el-subtext el-subtext--light mx-auto">Structured, stage-by-stage career tracks that take you from beginner to industry-ready professional — follow the path from left to right.</p>
        </div>

        @if(!empty($learning_paths) && count($learning_paths) > 0)
            <div class="row g-3 g-lg-2 align-items-stretch el-path-grid">
                @foreach($learning_paths as $path)
                    <div class="col-12 col-lg el-path-col">
                        <a href="{{ $path['url'] ?? '#' }}" class="el-path-card text-decoration-none d-block h-100">

                            <div class="el-path-icon-wrap">
                                <div class="el-feature-icon el-path-waypoint" style="animation-delay: -{{ $loop->index * 0.7 }}s;">
                                    <i class="bi bi-{{ $path['icon'] ?? 'signpost-2' }}"></i>
                                </div>
                                <span class="el-path-stage-badge">{{ $loop->iteration }}</span>
                            </div>

                            <span class="el-path-stage-label">Stage {{ $loop->iteration }}</span>
                            <h5 class="text-white mb-2">{{ $path['name'] ?? '' }}</h5>
                            <p class="mb-0" style="color: var(--muted);">
                                {{ $path['course_count'] ?? 0 }} courses
                            </p>
                        </a>
                    </div>

                    @if(!$loop->last)
                        {{-- Desktop: horizontal arrow between stages --}}
                        <div class="col-lg-auto d-none d-lg-flex align-items-center justify-content-center el-path-arrow">
                            <i class="bi bi-chevron-right"></i>
                        </div>
                        {{-- Mobile/tablet: vertical arrow between stacked stages --}}
                        <div class="col-12 d-lg-none text-center el-path-arrow-vertical">
                            <i class="bi bi-chevron-down"></i>
                        </div>
                    @endif
                @endforeach
            </div>
        @else
            {{-- Empty State: kept exactly as requested --}}
            <div class="el-empty-state el-empty-state--dark">
                <i class="bi bi-signpost-split"></i>
                <h5>Learning Paths Coming Soon</h5>
                <p>We are designing structured career tracks to guide your learning journey. Stay tuned.</p>
            </div>
        @endif
    </div>
</section>

<style>
    /* ─── LEARNING PATHS: STAGE CARDS ─────────────────────── */
    .el-path-card {
        background-color: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 1rem;
        padding: 2.25rem 1.25rem 2rem;
        text-align: center;
        transition: transform 0.3s ease, border-color 0.3s ease, background-color 0.3s ease;
    }

    .el-path-card:hover {
        transform: translateY(-4px);
        border-color: var(--gold);
        background-color: rgba(255, 255, 255, 0.06);
    }

    /* ─── STAGE NUMBER BADGE (overlaps the icon circle) ───── */
    .el-path-icon-wrap {
        position: relative;
        width: fit-content;
        margin: 0 auto 0.75rem;
    }

    .el-path-stage-badge {
        position: absolute;
        top: -6px;
        right: -6px;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: var(--gold);
        color: var(--dark);
        font-family: 'IM Fell English', serif;
        font-size: 0.8rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 6px rgba(0,0,0,0.35);
        border: 2px solid var(--dark);
    }

    .el-path-stage-label {
        display: block;
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: var(--gold);
        margin-bottom: 0.4rem;
    }

    /* ─── ARROWS BETWEEN STAGES ────────────────────────────── */
    .el-path-arrow {
        color: rgba(255,255,255,0.25);
        font-size: 1.4rem;
        padding: 0 0.25rem;
    }

    .el-path-arrow-vertical {
        color: rgba(255,255,255,0.25);
        font-size: 1.3rem;
        padding: 0.1rem 0;
    }

    /* ─── WAYPOINT FLOATING ANIMATION ──────────────────────── */
    .el-path-waypoint {
        animation: el-float-waypoint 4s ease-in-out infinite;
    }

    @keyframes el-float-waypoint {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-6px); }
    }

    /* Accessibility: Respect user motion preferences */
    @media (prefers-reduced-motion: reduce) {
        .el-path-card,
        .el-path-waypoint {
            transition: none;
            animation: none;
            transform: none !important;
        }
    }
</style>