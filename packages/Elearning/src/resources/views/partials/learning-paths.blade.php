{{-- ============================================================
     TEMP DUMMY DATA — FOR PREVIEW ONLY
     This @php block overrides $learning_paths with fake data,
     including a 'courses' list per path (ordered foundational →
     advanced) so you can see the vertical roadmap ladder. It only
     exists in this file — your controller's 'learning_paths' => []
     empty state is untouched.

     TO REMOVE: delete the entire @php ... @endphp block below
     (from "TEMP DUMMY DATA START" to "TEMP DUMMY DATA END").
     Once deleted, $learning_paths goes back to whatever the
     controller actually passes in (currently an empty array).
     ============================================================ --}}
@php
    // TEMP DUMMY DATA START
    $learning_paths = [
        [
            'name' => 'Full Stack Developer',
            'url' => '#',
            'courses' => ['Introduction to Programming', 'HTML', 'CSS', 'Responsive Design', 'JavaScript', 'React.js'],
        ],
        [
            'name' => 'Data Analyst',
            'url' => '#',
            'courses' => ['Statistics Fundamentals', 'Excel for Analysts', 'SQL Basics', 'Data Visualization', 'Python for Data Analysis'],
        ],
        [
            'name' => 'Cybersecurity Specialist',
            'url' => '#',
            'courses' => ['Networking Basics', 'Security Fundamentals', 'Ethical Hacking', 'Incident Response', 'Security Compliance'],
        ],
        [
            'name' => 'UX/UI Designer',
            'url' => '#',
            'courses' => ['Design Thinking', 'Wireframing Basics', 'Figma Essentials', 'Design Systems'],
        ],
        [
            'name' => 'Digital Marketer',
            'url' => '#',
            'courses' => ['Marketing Fundamentals', 'SEO Basics', 'Social Media Strategy'],
        ],
    ];
    // TEMP DUMMY DATA END
@endphp

{{-- Learning Paths Section --}}
<section class="el-section" style="background-color: var(--dark);" id="el-paths">
    <div class="el-container">
        <div class="text-center mb-5">
            <div class="el-pill">Career Tracks</div>
            <h2 class="el-heading el-heading--light">Learning Paths</h2>
            <p class="el-subtext el-subtext--light mx-auto">Every track is a course-by-course climb — start at the bottom, build up one skill at a time, and graduate at the top.</p>
        </div>

        @if(!empty($learning_paths) && count($learning_paths) > 0)
            <div class="el-roadmap-columns">
                @foreach($learning_paths as $path)
                    <a href="{{ $path['url'] ?? '#' }}" class="el-roadmap-path">
                        @if(!empty($path['courses']))
                            <div class="el-roadmap-ladder">
                                @foreach($path['courses'] as $course)
                                    <div class="el-roadmap-course">{{ $course }}</div>
                                    <div class="el-roadmap-arrow"><i class="bi bi-chevron-up"></i></div>
                                @endforeach
                                <div class="el-roadmap-summit">
                                    <i class="bi bi-mortarboard-fill"></i> {{ $path['name'] ?? '' }}
                                </div>
                            </div>
                        @else
                            {{-- Graceful fallback for a path with no course breakdown yet --}}
                            <div class="el-roadmap-fallback">
                                <div class="el-feature-icon mx-auto mb-3">
                                    <i class="bi bi-{{ $path['icon'] ?? 'signpost-2' }}"></i>
                                </div>
                                <h5 class="text-white mb-1">{{ $path['name'] ?? '' }}</h5>
                                <p class="mb-0" style="color: var(--muted); font-size: var(--text-sm);">
                                    {{ $path['course_count'] ?? 0 }} courses
                                </p>
                            </div>
                        @endif
                    </a>
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
    /* ─── ROADMAP: COLUMN GRID (uneven "skyline", bottom-aligned) ─ */
    .el-roadmap-columns {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
        gap: 2.5rem 1.25rem;
        align-items: end;
    }

    .el-roadmap-path {
        text-decoration: none;
        display: block;
    }

    /* ─── LADDER: bottom-to-top course sequence ───────────── */
    .el-roadmap-ladder {
        display: flex;
        flex-direction: column-reverse;
        align-items: center;
        gap: 0.4rem;
    }

    .el-roadmap-course {
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        color: rgba(255,255,255,0.85);
        font-size: var(--text-sm);
        font-weight: 600;
        padding: 0.55rem 1rem;
        border-radius: var(--radius-md);
        text-align: center;
        width: 100%;
        transition: background 0.2s ease, border-color 0.2s ease, color 0.2s ease;
    }

    .el-roadmap-arrow {
        color: var(--gold);
        opacity: 0.5;
        font-size: var(--text-sm);
        line-height: 1;
        transition: opacity 0.2s ease, transform 0.2s ease;
    }

    .el-roadmap-summit {
        background: var(--gold);
        color: var(--dark);
        font-family: 'IM Fell English', serif;
        font-weight: 700;
        font-size: var(--text-base-lg);
        padding: 0.75rem 1.1rem;
        border-radius: var(--radius-lg);
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        width: 100%;
        box-shadow: 0 8px 24px rgba(196,168,64,0.25);
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    /* Hover: light up the whole climb, lift the summit */
    .el-roadmap-path:hover .el-roadmap-course {
        background: rgba(196,168,64,0.1);
        border-color: var(--gold);
        color: #fff;
    }

    .el-roadmap-path:hover .el-roadmap-arrow {
        opacity: 1;
        transform: translateY(-2px);
    }

    .el-roadmap-path:hover .el-roadmap-summit {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(196,168,64,0.4);
    }

    /* ─── FALLBACK CARD (path with no course breakdown yet) ─ */
    .el-roadmap-fallback {
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: var(--radius-lg);
        padding: 2rem 1.25rem;
        text-align: center;
        transition: border-color 0.2s ease, transform 0.2s ease;
    }

    .el-roadmap-path:hover .el-roadmap-fallback {
        border-color: var(--gold);
        transform: translateY(-4px);
    }

    @media (max-width: 767px) {
        .el-roadmap-columns {
            grid-template-columns: 1fr;
            gap: 3rem;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .el-roadmap-course,
        .el-roadmap-arrow,
        .el-roadmap-summit,
        .el-roadmap-fallback {
            transition: none;
        }
    }
</style>