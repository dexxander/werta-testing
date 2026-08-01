{{-- ============================================================
     TEMP DUMMY DATA — FOR PREVIEW ONLY
     This @php block overrides $learning_paths with fake data so
     you can see the UI (including the journey-line + waypoint
     bobbing animation, which need at least a few items to look
     right). It only exists in this file — your controller's
     'learning_paths' => [] empty state is untouched.

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
            <p class="el-subtext el-subtext--light mx-auto">Structured career tracks designed to take you from beginner to industry-ready professional.</p>
        </div>

        @if(!empty($learning_paths) && count($learning_paths) > 0)
            <div class="row g-4 el-path-grid position-relative">
                @foreach($learning_paths as $path)
                    <div class="col-12 col-md-6 col-lg z-1">
                        <a href="{{ $path['url'] ?? '#' }}" class="el-path-card text-decoration-none d-block h-100">
                            
                            {{-- Waypoint Icon: matches the hero's floating badges & stat icons --}}
                            <div class="el-feature-icon mx-auto mb-4 el-path-waypoint">
                                <i class="bi bi-{{ $path['icon'] ?? 'signpost-2' }}"></i>
                            </div>
                            
                            <h5 class="text-white mb-2">{{ $path['name'] ?? '' }}</h5>
                            <p class="mb-0" style="color: var(--muted);">
                                {{ $path['course_count'] ?? 0 }} courses
                            </p>
                        </a>
                    </div>
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
    /* Scoped styles for Learning Paths */
    .el-path-card {
        background-color: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 1rem;
        padding: 2.5rem 1.5rem;
        text-align: center;
        transition: transform 0.3s ease, border-color 0.3s ease;
    }

    .el-path-card:hover {
        transform: translateY(-4px);
        border-color: var(--gold);
    }

    /* The Journey Line (Desktop only) connecting the waypoints */
    @media (min-width: 992px) {
        .el-path-grid::before {
            content: '';
            position: absolute;
            top: 4.25rem; /* Aligns with the center of the el-feature-icon */
            left: 5%;
            right: 5%;
            border-top: 2px dashed var(--gold);
            opacity: 0.25;
            z-index: 0;
        }
    }

    /* Waypoint floating animation */
    .el-path-waypoint {
        animation: el-float-waypoint 4s ease-in-out infinite;
    }

    /* Stagger the bobbing animation so the path feels organic, not robotic */
    .el-path-grid > div:nth-child(even) .el-path-waypoint {
        animation-delay: -2s;
    }
    
    .el-path-grid > div:nth-child(3n) .el-path-waypoint {
        animation-delay: -1s;
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