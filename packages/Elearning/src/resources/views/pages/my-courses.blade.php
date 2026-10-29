@extends('elearning::layout')

@section('elearning-body')
@php
    // TEMP DUMMY DATA START
    $my_courses = [
        [
            'id' => 1,
            'title' => 'Web Development Bootcamp',
            'instructor' => 'Dr. Alan Smith',
            'progress' => 65,
            'last_accessed' => '2 hours ago',
            'thumbnail_icon' => 'code-slash'
        ],
        [
            'id' => 2,
            'title' => 'Advanced Data Science',
            'instructor' => 'Sarah Johnson',
            'progress' => 30,
            'last_accessed' => '1 day ago',
            'thumbnail_icon' => 'bar-chart-line'
        ],
        [
            'id' => 3,
            'title' => 'UI/UX Principles',
            'instructor' => 'Michael Chen',
            'progress' => 100,
            'last_accessed' => '1 week ago',
            'thumbnail_icon' => 'palette2'
        ]
    ];
    // TEMP DUMMY DATA END
@endphp

<section class="el-section bg-cream-light" id="my-courses">
    <div class="el-container">
        <div class="mb-5 d-flex justify-content-between align-items-end">
            <div>
                <a href="{{ route('elearning.dashboard') }}" style="color:var(--primary);text-decoration:none;font-size:var(--text-base);font-weight:700;"><i class="bi bi-arrow-left"></i> {{ __('Back to Dashboard') }}</a>
                <h2 class="el-heading mt-3">{{ __('My Enrolled Courses') }}</h2>
                <p class="el-subtext">{{ __('Pick up right where you left off.') }}</p>
            </div>
            <div>
                <a href="{{ route('elearning.courses') }}" class="el-btn-outline">{{ __('Browse More') }}</a>
            </div>
        </div>

        <div class="el-card mb-4" style="padding:1.25rem;">
            <div class="row g-3 align-items-center">
                <div class="col-lg-5">
                    <label for="my-course-search" class="visually-hidden">Search enrolled courses</label>
                    <div class="el-course-search" style="background:var(--cream);">
                        <i class="bi bi-search" aria-hidden="true"></i>
                        <input id="my-course-search" type="search" placeholder="Search your courses" aria-label="Search enrolled courses">
                    </div>
                </div>
                <div class="col-lg-3">
                    <select id="my-course-status" class="form-select" aria-label="Filter enrolled courses">
                        <option value="all">All courses</option>
                        <option value="active">In progress</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
                <div class="col-lg-4">
                    <div style="display:flex;align-items:center;gap:0.75rem;">
                        <div style="width:42px;height:42px;border-radius:var(--radius-full);background:rgba(196,168,64,0.15);display:flex;align-items:center;justify-content:center;color:var(--primary);"><i class="bi bi-calendar-check"></i></div>
                        <div style="flex:1;">
                            <div style="display:flex;justify-content:space-between;font-size:var(--text-sm);font-weight:700;"><span>Weekly study goal</span><span id="el-goal-count">0 / 5 lessons</span></div>
                            <div style="height:6px;background:var(--cream);border-radius:var(--radius-pill);overflow:hidden;margin-top:0.45rem;"><div id="el-goal-progress" style="height:100%;width:0;background:var(--gold);border-radius:var(--radius-pill);transition:width 0.25s;"></div></div>
                        </div>
                        <button id="el-goal-add" type="button" class="el-btn-outline" style="padding:0.4rem 0.65rem;" aria-label="Log one completed lesson"><i class="bi bi-plus-lg"></i></button>
                    </div>
                </div>
            </div>
            <p id="el-my-course-count" class="el-course-result-count" aria-live="polite" style="margin-bottom:0;"></p>
        </div>

        <div class="row g-4">
            @foreach($my_courses as $course)
                <div class="col-md-6 col-lg-4 el-my-course-card" data-course-title="{{ strtolower($course['title']) }}" data-course-status="{{ $course['progress'] == 100 ? 'completed' : 'active' }}">
                    <div class="el-card h-100 d-flex flex-column">
                        <div style="width:100%;height:160px;background:var(--cream);border-radius:var(--radius-md);margin-bottom:1.2rem;display:flex;align-items:center;justify-content:center;">
                            <i class="bi bi-{{ $course['thumbnail_icon'] }}" style="font-size:3rem;color:var(--gold);opacity:0.3;"></i>
                        </div>
                        <h5 style="font-weight:700;font-size:1.1rem;margin-bottom:0.3rem;">{{ \App\Support\SiteText::translate($course['title']) }}</h5>
                        <p style="font-size:var(--text-sm);color:var(--muted);margin-bottom:1rem;">{{ __('Instructor:') }} {{ \App\Support\SiteText::translate($course['instructor']) }}</p>
                        
                        <div style="margin-bottom:1rem;">
                            <div style="display:flex;justify-content:space-between;font-size:var(--text-sm);margin-bottom:0.4rem;font-weight:600;">
                                <span>{{ __('Progress') }}</span>
                                <span>{{ \App\Support\SiteText::translate($course['progress']) }}%</span>
                            </div>
                            <div style="width:100%;height:6px;background:var(--cream);border-radius:var(--radius-pill);overflow:hidden;">
                                <div style="width:{{ $course['progress'] }}%;height:100%;background:var(--gold);border-radius:var(--radius-pill);"></div>
                            </div>
                        </div>
                        
                        <div style="margin-top:auto;display:flex;align-items:center;justify-content:space-between;">
                            <span style="font-size:var(--text-sm);color:var(--muted);"><i class="bi bi-clock-history"></i> {{ \App\Support\SiteText::translate($course['last_accessed']) }}</span>
                            @if($course['progress'] == 100)
                                <a href="{{ route('elearning.course-content', ['id' => $course['id']]) }}" class="el-btn-outline" style="padding:0.4rem 1rem;font-size:0.85rem;">{{ __('Review') }}</a>
                            @else
                                <a href="{{ route('elearning.course-content', ['id' => $course['id']]) }}" class="el-btn-primary" style="padding:0.4rem 1rem;font-size:0.85rem;">{{ __('Resume') }}</a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const search = document.getElementById('my-course-search');
        const status = document.getElementById('my-course-status');
        const result = document.getElementById('el-my-course-count');
        const cards = Array.from(document.querySelectorAll('.el-my-course-card'));
        const goalCount = document.getElementById('el-goal-count');
        const goalProgress = document.getElementById('el-goal-progress');
        const goalKey = 'werta-elearning-weekly-goal';
        let completedLessons = Math.min(Number(localStorage.getItem(goalKey) || 0), 5);

        function renderGoal() {
            goalCount.textContent = completedLessons + ' / 5 lessons';
            goalProgress.style.width = (completedLessons / 5 * 100) + '%';
        }
        function filterCourses() {
            const query = search.value.trim().toLowerCase();
            const selected = status.value;
            let visible = 0;
            cards.forEach(function (card) {
                const show = (!query || card.dataset.courseTitle.includes(query)) && (selected === 'all' || selected === card.dataset.courseStatus);
                card.hidden = !show;
                if (show) visible++;
            });
            result.textContent = visible + ' enrolled course' + (visible === 1 ? '' : 's') + ' shown';
        }
        search.addEventListener('input', filterCourses);
        status.addEventListener('change', filterCourses);
        document.getElementById('el-goal-add').addEventListener('click', function () {
            completedLessons = completedLessons >= 5 ? 0 : completedLessons + 1;
            localStorage.setItem(goalKey, completedLessons);
            renderGoal();
        });
        renderGoal();
        filterCourses();
    });
</script>
@endsection
