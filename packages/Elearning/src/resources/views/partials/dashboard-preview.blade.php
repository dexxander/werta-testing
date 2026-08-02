{{-- ============================================================
     TEMP DUMMY DATA — FOR PREVIEW ONLY
     This @php block sets preview variables so you can see the
     dashboard populated. Every place these variables are used below
     falls back to a real empty state via `?? []` / `?? 0` / @if
     checks — so deleting this block is the ONLY step needed to
     revert. Nothing else in this file needs to change.

     TO REMOVE: delete the entire @php ... @endphp block below
     (from "TEMP DUMMY DATA START" to "TEMP DUMMY DATA END").
     ============================================================ --}}
@php
    // TEMP DUMMY DATA START
    $dashboard_courses = [
        ['title' => 'Advanced React & Next.js', 'icon' => 'code-slash', 'progress' => 68, 'next_lesson' => 'Server Components'],
        ['title' => 'UI/UX Design Fundamentals', 'icon' => 'palette2', 'progress' => 42, 'next_lesson' => 'Design Systems'],
        ['title' => 'Data Visualization with Python', 'icon' => 'bar-chart-line', 'progress' => 15, 'next_lesson' => 'Intro to Matplotlib'],
    ];
    $dashboard_overall_progress = 54;
    $dashboard_goals = [
        ['label' => 'Complete 3 lessons', 'done' => true],
        ['label' => 'Finish weekly quiz', 'done' => true],
        ['label' => 'Submit project draft', 'done' => false],
    ];
    $dashboard_streak_days = 12;
    $dashboard_streak_active_days = 5; // out of 7 this week
    // TEMP DUMMY DATA END
@endphp

{{-- Student Dashboard Preview Section --}}
<section class="el-section bg-cream">
    <div class="el-container">
        <div class="text-center mb-5">
            <div class="el-pill">Your Learning Hub</div>
            <h2 class="el-heading">Student Dashboard Preview</h2>
            <p class="el-subtext mx-auto">A premium dashboard experience to track your learning journey, goals, and achievements.</p>
        </div>

        <div class="el-dashboard-mock">
            <div class="el-dashboard-topbar">
                <span class="el-dashboard-dot red"></span>
                <span class="el-dashboard-dot yellow"></span>
                <span class="el-dashboard-dot green"></span>
                <span style="color:rgba(255,255,255,0.5);font-size:0.75rem;margin-left:12px;">Werta — Student Dashboard</span>
            </div>
            <div class="el-dash-body">
                <div class="row g-4">
                    {{-- Active Courses --}}
                    <div class="col-md-8">
                        <div class="el-dash-panel h-100">
                            <div class="el-dash-panel-header">
                                <h6>Active Courses</h6>
                                <span class="el-dash-view-all">View All</span>
                            </div>

                            @if(!empty($dashboard_courses))
                                <div class="el-dash-course-list">
                                    @foreach($dashboard_courses as $course)
                                        <div class="el-dash-course">
                                            <div class="el-feature-icon el-dash-course-icon">
                                                <i class="bi bi-{{ $course['icon'] ?? 'journal-text' }}"></i>
                                            </div>
                                            <div class="el-dash-course-info">
                                                <div class="el-dash-course-top">
                                                    <h6>{{ $course['title'] ?? '' }}</h6>
                                                    <span>{{ $course['progress'] ?? 0 }}%</span>
                                                </div>
                                                <div class="el-dash-progress-bar">
                                                    <div class="el-dash-progress-fill" style="width: {{ $course['progress'] ?? 0 }}%;"></div>
                                                </div>
                                                @if(!empty($course['next_lesson']))
                                                    <p class="el-dash-course-next">Next: {{ $course['next_lesson'] }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="el-dash-empty">
                                    <i class="bi bi-journal-text"></i>
                                    <p>No active courses yet. Start your learning journey today.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Right Sidebar --}}
                    <div class="col-md-4">
                        {{-- Progress --}}
                        <div class="el-dash-panel el-dash-panel--tight mb-3">
                            <h6 class="el-dash-panel-title">Progress Tracker</h6>
                            <div class="el-dash-progress-row">
                                <div class="el-dash-ring" style="--pct: {{ $dashboard_overall_progress ?? 0 }};">
                                    <div class="el-dash-ring-inner">{{ $dashboard_overall_progress ?? 0 }}%</div>
                                </div>
                                <p>Overall completion</p>
                            </div>
                        </div>

                        {{-- Goals --}}
                        <div class="el-dash-panel el-dash-panel--tight mb-3">
                            <h6 class="el-dash-panel-title">Weekly Goals</h6>
                            @if(!empty($dashboard_goals))
                                <ul class="el-dash-goal-list">
                                    @foreach($dashboard_goals as $goal)
                                        <li class="el-dash-goal {{ !empty($goal['done']) ? 'is-done' : '' }}">
                                            <i class="bi bi-{{ !empty($goal['done']) ? 'check-circle-fill' : 'circle' }}"></i>
                                            {{ $goal['label'] ?? '' }}
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="el-dash-empty-text">No goals set yet.</p>
                            @endif
                        </div>

                        {{-- Streak --}}
                        <div class="el-dash-panel el-dash-panel--tight">
                            <h6 class="el-dash-panel-title"><i class="bi bi-fire" style="color:var(--gold);"></i> Learning Streak</h6>
                            <p class="el-dash-streak-count">{{ $dashboard_streak_days ?? 0 }} <span>days</span></p>
                            <div class="el-dash-streak-dots">
                                @for($i = 0; $i < 7; $i++)
                                    <span class="{{ $i < ($dashboard_streak_active_days ?? 0) ? 'is-active' : '' }}"></span>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    /* ─── DASHBOARD PREVIEW: LAYOUT ───────────────────────── */
    .el-dash-body {
        padding: 2rem;
        background: var(--cream-light);
    }

    .el-dash-panel {
        background: var(--cream);
        border-radius: 12px;
        padding: 1.5rem;
        transition: box-shadow 0.25s ease;
    }

    .el-dash-panel:hover {
        box-shadow: 0 10px 28px rgba(44,36,22,0.08);
    }

    .el-dash-panel--tight {
        padding: 1.2rem;
    }

    .el-dash-panel-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.1rem;
    }

    .el-dash-panel-header h6,
    .el-dash-panel-title {
        font-weight: 700;
        margin: 0;
        font-size: 0.9rem;
        color: var(--dark);
    }

    .el-dash-panel-title {
        margin-bottom: 0.9rem;
    }

    .el-dash-view-all {
        font-size: 0.75rem;
        color: var(--gold);
        font-weight: 700;
        cursor: pointer;
    }

    /* ─── ACTIVE COURSES ───────────────────────────────────── */
    .el-dash-course-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .el-dash-course {
        display: flex;
        gap: 1rem;
        align-items: flex-start;
        background: var(--cream-light);
        border-radius: 10px;
        padding: 1rem;
        transition: background 0.2s ease, transform 0.2s ease;
    }

    .el-dash-course:hover {
        background: rgba(196,168,64,0.08);
        transform: translateX(3px);
    }

    .el-dash-course-icon {
        flex-shrink: 0;
    }

    .el-dash-course-info {
        flex: 1;
        min-width: 0;
    }

    .el-dash-course-top {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        gap: 0.5rem;
        margin-bottom: 0.4rem;
    }

    .el-dash-course-top h6 {
        font-weight: 700;
        font-size: 0.88rem;
        margin: 0;
        color: var(--dark);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .el-dash-course-top span {
        font-size: 0.78rem;
        font-weight: 700;
        color: var(--gold);
        flex-shrink: 0;
    }

    .el-dash-progress-bar {
        width: 100%;
        height: 5px;
        border-radius: 4px;
        background: rgba(123,107,53,0.12);
        overflow: hidden;
        margin-bottom: 0.4rem;
    }

    .el-dash-progress-fill {
        height: 100%;
        background: var(--gold);
        border-radius: 4px;
        transition: width 0.6s ease;
    }

    .el-dash-course-next {
        font-size: 0.75rem;
        color: var(--muted);
        margin: 0;
    }

    .el-dash-empty,
    .el-dash-empty-text {
        text-align: center;
        padding: 2.5rem 1rem;
    }

    .el-dash-empty i {
        font-size: 2.5rem;
        color: var(--gold);
        opacity: 0.3;
        display: block;
        margin-bottom: 0.8rem;
    }

    .el-dash-empty p,
    .el-dash-empty-text {
        font-size: 0.85rem;
        color: var(--muted);
        margin: 0;
    }

    /* ─── PROGRESS RING (conic-gradient, no SVG needed) ────── */
    .el-dash-progress-row {
        display: flex;
        align-items: center;
        gap: 0.9rem;
    }

    .el-dash-ring {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        flex-shrink: 0;
        background: conic-gradient(var(--gold) calc(var(--pct) * 1%), rgba(123,107,53,0.12) 0);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.6s ease;
    }

    .el-dash-ring-inner {
        width: 46px;
        height: 46px;
        border-radius: 50%;
        background: var(--cream);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.78rem;
        color: var(--primary-dark);
    }

    .el-dash-progress-row p {
        font-size: 0.8rem;
        color: var(--muted);
        margin: 0;
    }

    /* ─── WEEKLY GOALS ─────────────────────────────────────── */
    .el-dash-goal-list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 0.6rem;
    }

    .el-dash-goal {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        font-size: 0.82rem;
        color: var(--muted);
    }

    .el-dash-goal i {
        color: rgba(123,107,53,0.35);
        font-size: 0.9rem;
    }

    .el-dash-goal.is-done {
        color: var(--dark);
        text-decoration: line-through;
        text-decoration-color: rgba(44,36,22,0.3);
    }

    .el-dash-goal.is-done i {
        color: var(--gold);
    }

    /* ─── STREAK ───────────────────────────────────────────── */
    .el-dash-streak-count {
        font-size: 1.6rem;
        font-weight: 700;
        color: var(--primary-dark);
        margin: 0 0 0.7rem;
    }

    .el-dash-streak-count span {
        font-size: 0.8rem;
        color: var(--muted);
        font-weight: 400;
    }

    .el-dash-streak-dots {
        display: flex;
        gap: 0.4rem;
    }

    .el-dash-streak-dots span {
        width: 16px;
        height: 16px;
        border-radius: 4px;
        background: rgba(123,107,53,0.12);
        transition: background 0.3s ease;
    }

    .el-dash-streak-dots span.is-active {
        background: var(--gold);
    }

    @media (prefers-reduced-motion: reduce) {
        .el-dash-panel,
        .el-dash-course,
        .el-dash-progress-fill,
        .el-dash-ring,
        .el-dash-streak-dots span {
            transition: none;
        }
    }
</style>