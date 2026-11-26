@extends('elearning::layout')

@section('elearning-body')
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
                            <small style="display:block;color:var(--muted);margin-top:.35rem;">Progress updates when you complete a lesson.</small>
                        </div>
                    </div>
                </div>
            </div>
            <p id="el-my-course-count" class="el-course-result-count" aria-live="polite" style="margin-bottom:0;"></p>
        </div>

        <div id="el-my-course-grid" class="row g-4"></div>
        <div id="el-my-courses-empty" class="el-empty-state">
            <i class="bi bi-journal-plus"></i>
            <h5>{{ __('No enrolled courses yet') }}</h5>
            <p>{{ __('Enroll in a course and it will be saved here with your progress.') }}</p>
            <a href="{{ route('elearning.courses') }}" class="el-btn-primary">{{ __('Browse courses') }}</a>
        </div>
        <div id="el-my-courses-no-match" class="el-empty-state" hidden>
            <i class="bi bi-search"></i>
            <h5>{{ __('No matching courses') }}</h5>
            <p>{{ __('Try another search or choose a different progress filter.') }}</p>
        </div>
    </div>
</section>
<script>
    function initMyCourses() {
        const search = document.getElementById('my-course-search');
        const status = document.getElementById('my-course-status');
        const result = document.getElementById('el-my-course-count');
        let cards = Array.from(document.querySelectorAll('.el-my-course-card'));
        const courseGrid = document.getElementById('el-my-course-grid');
        const emptyState = document.getElementById('el-my-courses-empty');
        const noMatchState = document.getElementById('el-my-courses-no-match');
        const goalCount = document.getElementById('el-goal-count');
        const goalProgress = document.getElementById('el-goal-progress');
        const goalKey = 'werta-elearning-weekly-goal';
        let completedLessons = Math.min(Number(localStorage.getItem(goalKey) || 0), 5);

        let enrolledCourses = [];
        try {
            const storedCourses = JSON.parse(localStorage.getItem('werta-elearning-enrolled-courses') || '[]');
            enrolledCourses = Array.isArray(storedCourses) ? storedCourses : [];
        } catch (error) {
            localStorage.removeItem('werta-elearning-enrolled-courses');
        }
        enrolledCourses.forEach(function (course) {
            const existingCard = cards.find(function (card) { return card.dataset.courseId === String(course.id); });
            const progressPercent = Math.max(0, Math.min(100, Number(course.progress) || 0));
            if (existingCard) {
                existingCard.dataset.courseStatus = progressPercent >= 100 ? 'completed' : 'active';
                const label = existingCard.querySelector('.el-course-progress-label');
                const bar = existingCard.querySelector('.el-course-progress-bar');
                if (label) label.textContent = progressPercent + '%';
                if (bar) bar.style.width = progressPercent + '%';
                return;
            }
            const column = document.createElement('div');
            column.className = 'col-md-6 col-lg-4 el-my-course-card';
            column.dataset.courseId = String(course.id);
            column.dataset.courseTitle = String(course.title || '').toLowerCase();
            column.dataset.courseStatus = Number(course.progress) >= 100 ? 'completed' : 'active';
            const card = document.createElement('div');
            card.className = 'el-card h-100 d-flex flex-column';
            const icon = document.createElement('div');
            icon.className = 'el-enrolled-course-icon';
            icon.innerHTML = '<i class="bi bi-' + String(course.icon || 'journal-bookmark').replace(/[^a-z0-9-]/gi, '') + '" aria-hidden="true"></i>';
            const title = document.createElement('h5');
            title.className = 'el-enrolled-course-title';
            title.textContent = course.title || 'New course';
            const instructor = document.createElement('p');
            instructor.className = 'el-enrolled-course-instructor';
            instructor.textContent = 'Instructor: ' + (course.instructor || 'Werta instructor');
            const progress = document.createElement('div');
            progress.className = 'el-enrolled-course-progress';
            progress.innerHTML = '<span>Progress</span><strong>' + progressPercent + '%</strong><div><i style="width:' + progressPercent + '%"></i></div>';
            const resume = document.createElement('a');
            resume.className = 'el-btn-primary el-enrolled-course-resume';
            resume.href = '/elearning/course/' + encodeURIComponent(course.id) + '/content';
            resume.textContent = progressPercent >= 100 ? 'Review course' : (progressPercent > 0 ? 'Resume learning' : 'Start learning');
            card.append(icon, title, instructor, progress, resume);
            column.appendChild(card);
            if (courseGrid) courseGrid.appendChild(column);
        });
        cards = Array.from(document.querySelectorAll('.el-my-course-card'));

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
            emptyState.hidden = cards.length > 0;
            noMatchState.hidden = cards.length === 0 || visible > 0;
        }
        search.addEventListener('input', filterCourses);
        status.addEventListener('change', filterCourses);
        renderGoal();
        filterCourses();
    }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initMyCourses);
    else initMyCourses();
</script>
@endsection
