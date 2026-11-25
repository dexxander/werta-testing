@extends('elearning::layout')

@section('elearning-body')
    <section class="el-section bg-cream" style="padding-bottom:2rem;">
        <div class="el-container">
            <div class="el-course-toolbar" role="search" aria-label="Course catalogue filters">
                <div class="el-course-search">
                    <i class="bi bi-search" aria-hidden="true"></i>
                    <input id="el-course-search" type="search" value="{{ request('q') }}" placeholder="Search courses, instructors or topics" aria-label="Search courses">
                </div>
                <label class="el-course-filter">
                    <span class="visually-hidden">Filter by difficulty</span>
                    <select id="el-course-difficulty">
                        <option value="all">All levels</option>
                        <option value="beginner">Beginner</option>
                        <option value="intermediate">Intermediate</option>
                        <option value="advanced">Advanced</option>
                    </select>
                </label>
                <label class="el-course-filter">
                    <span class="visually-hidden">Filter by language</span>
                    <select id="el-course-language">
                        <option value="all">All languages</option>
                        <option value="english">English</option>
                        <option value="malay">Malay</option>
                        <option value="chinese">Chinese</option>
                    </select>
                </label>
                <button id="el-saved-toggle" class="el-btn-outline el-saved-toggle" type="button" aria-pressed="false">
                    <i class="bi bi-bookmark" aria-hidden="true"></i> Saved courses <span id="el-saved-count">0</span>
                </button>
            </div>
            <p id="el-course-result-count" class="el-course-result-count" aria-live="polite"></p>
        </div>
    </section>
    @include('elearning::partials.categories')
    <hr class="el-divider">
    @include('elearning::partials.featured-courses')

    <script>
        function initCourseCatalog() {
            const search = document.getElementById('el-course-search');
            const difficulty = document.getElementById('el-course-difficulty');
            const language = document.getElementById('el-course-language');
            const savedToggle = document.getElementById('el-saved-toggle');
            const count = document.getElementById('el-course-result-count');
            const savedCount = document.getElementById('el-saved-count');
            const cards = Array.from(document.querySelectorAll('.el-course-card'));
            const storageKey = 'werta-elearning-saved-courses';
            let saved = JSON.parse(localStorage.getItem(storageKey) || '[]');

            function renderSavedState() {
                document.querySelectorAll('[data-save-course]').forEach(function (button) {
                    const isSaved = saved.includes(String(button.dataset.saveCourse));
                    button.classList.toggle('is-saved', isSaved);
                    button.setAttribute('aria-pressed', isSaved ? 'true' : 'false');
                    button.setAttribute('aria-label', isSaved ? 'Remove saved course' : 'Save course');
                    button.innerHTML = '<i class="bi ' + (isSaved ? 'bi-bookmark-fill' : 'bi-bookmark') + '" aria-hidden="true"></i>';
                });
                savedCount.textContent = saved.length;
            }

            function filterCourses() {
                const query = search.value.trim().toLowerCase();
                const level = difficulty.value;
                const selectedLanguage = language.value;
                let visible = 0;
                cards.forEach(function (card) {
                    const matchesQuery = !query || card.dataset.search.includes(query);
                    const matchesLevel = level === 'all' || card.dataset.difficulty === level;
                    const matchesLanguage = selectedLanguage === 'all' || card.dataset.language === selectedLanguage;
                    const matchesSaved = !savedToggle.classList.contains('active') || saved.includes(card.dataset.courseId);
                    const show = matchesQuery && matchesLevel && matchesLanguage && matchesSaved;
                    card.closest('[class*="col-"]').hidden = !show;
                    if (show) visible++;
                });
                count.textContent = visible + ' course' + (visible === 1 ? '' : 's') + ' shown';
            }

            [search, difficulty, language].forEach(function (control) { control.addEventListener('input', filterCourses); });
            savedToggle.addEventListener('click', function () {
                this.classList.toggle('active');
                this.setAttribute('aria-pressed', this.classList.contains('active') ? 'true' : 'false');
                this.querySelector('i').className = this.classList.contains('active') ? 'bi bi-bookmarks-fill' : 'bi bi-bookmark';
                filterCourses();
            });
            document.addEventListener('click', function (event) {
                const button = event.target.closest('[data-save-course]');
                if (!button) return;
                event.preventDefault();
                const id = String(button.dataset.saveCourse);
                saved = saved.includes(id) ? saved.filter(function (item) { return item !== id; }) : saved.concat(id);
                localStorage.setItem(storageKey, JSON.stringify(saved));
                renderSavedState();
                filterCourses();
            });
            renderSavedState();
            filterCourses();
        }
        if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initCourseCatalog);
        else initCourseCatalog();
    </script>
@endsection
