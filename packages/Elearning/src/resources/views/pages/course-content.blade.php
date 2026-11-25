@extends('elearning::layout')

@section('elearning-body')
@php
    // TEMP DUMMY DATA START
    $course_details = [
        'title' => __('Web Development Bootcamp - Module :number', ['number' => $course_id ?? 1]),
        'instructor' => 'Dr. Alan Smith',
        'progress' => 65,
        'current_lesson' => 'Understanding CSS Flexbox',
        'modules' => [
            [
                'title' => 'Module 1: HTML Basics',
                'completed' => true,
                'lessons' => [
                    ['title' => 'Introduction to HTML5', 'duration' => '15:00', 'completed' => true],
                    ['title' => 'Semantic Elements', 'duration' => '22:30', 'completed' => true]
                ]
            ],
            [
                'title' => 'Module 2: Advanced CSS',
                'completed' => false,
                'lessons' => [
                    ['title' => 'Understanding CSS Flexbox', 'duration' => '28:15', 'completed' => false, 'active' => true],
                    ['title' => 'CSS Grid Layouts', 'duration' => '35:00', 'completed' => false],
                    ['title' => 'Responsive Design', 'duration' => '40:20', 'completed' => false]
                ]
            ]
        ]
    ];
    // TEMP DUMMY DATA END
@endphp

<section class="el-section bg-cream-light" style="padding-top:2rem;padding-bottom:2rem;">
    <div class="el-container">
        
        <div class="mb-4">
            <a href="{{ route('elearning.my-courses') }}" style="color:var(--primary);text-decoration:none;font-size:var(--text-base);font-weight:700;"><i class="bi bi-arrow-left"></i> {{ __('Back to My Courses') }}</a>
        </div>

        <div class="row g-4">
            {{-- Main Content Area --}}
            <div class="col-lg-8">
                <div class="el-card" style="padding:0;overflow:hidden;margin-bottom:1.5rem;">
                    {{-- Video Placeholder --}}
                    <div id="el-lesson-player" style="width:100%;aspect-ratio:16/9;background:radial-gradient(circle at 70% 25%,rgba(196,168,64,.2),transparent 30%),linear-gradient(135deg,#211f1a,#51452d);display:flex;align-items:center;justify-content:center;position:relative;">
                        <button id="el-player-toggle" type="button" aria-label="Play lesson preview" aria-pressed="false" style="width:84px;height:84px;border:1px solid rgba(255,255,255,.35);border-radius:50%;background:rgba(255,255,255,.1);color:var(--gold);font-size:2.6rem;display:grid;place-items:center;backdrop-filter:blur(8px);transition:transform .3s,background .3s;">
                            <i class="bi bi-play-fill" aria-hidden="true"></i>
                        </button>
                        <span id="el-player-status" style="position:absolute;top:1rem;left:1rem;color:rgba(255,255,255,.75);font-size:var(--text-xs);letter-spacing:.12em;text-transform:uppercase;">Lesson preview</span>
                        <div style="position:absolute;bottom:1rem;left:1rem;color:#fff;font-size:var(--text-sm);background:rgba(0,0,0,0.5);padding:0.2rem 0.6rem;border-radius:4px;">
                            <span id="el-player-lesson-title">{{ \App\Support\SiteText::translate($course_details['current_lesson']) }}</span>
                        </div>
                    </div>
                    
                    <div style="padding:2rem;">
                        <h3 id="el-current-lesson-title" style="font-family:'IM Fell English',serif;font-weight:700;color:var(--dark);margin-bottom:0.5rem;">{{ \App\Support\SiteText::translate($course_details['current_lesson']) }}</h3>
                        <p style="color:var(--muted);margin-bottom:1.5rem;">{{ __('Instructor:') }} {{ \App\Support\SiteText::translate($course_details['instructor']) }}</p>
                        
                        <div style="display:flex;gap:1rem;">
                            <button id="el-lesson-previous" class="el-btn-outline" type="button"><i class="bi bi-chevron-left"></i> {{ __('Previous') }}</button>
                            <button class="el-btn-primary el-complete-lesson" type="button"><i class="bi bi-check2-circle"></i> {{ __('Mark lesson complete') }}</button>
                            <button id="el-lesson-next" class="el-btn-outline" type="button">{{ __('Next') }} <i class="bi bi-chevron-right"></i></button>
                        </div>
                    </div>
                </div>
                
                {{-- Tabs --}}
                <div class="el-card">
                    <ul class="nav nav-tabs el-lesson-tabs" role="tablist" style="border-bottom:2px solid var(--cream);margin-bottom:1.5rem;">
                        <li class="nav-item">
                            <button class="nav-link active" id="el-tab-overview" type="button" role="tab" aria-selected="true" data-panel="overview" style="color:var(--dark);font-weight:700;border:none;border-bottom:2px solid var(--primary);background:transparent;">{{ __('Overview') }}</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" id="el-tab-resources" type="button" role="tab" aria-selected="false" data-panel="resources" style="color:var(--muted);border:none;background:transparent;">{{ __('Resources (2)') }}</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" id="el-tab-qa" type="button" role="tab" aria-selected="false" data-panel="qa" style="color:var(--muted);border:none;background:transparent;">Q&A</button>
                        </li>
                    </ul>
                    <div id="el-panel-overview" class="el-lesson-panel" role="tabpanel" aria-labelledby="el-tab-overview" style="color:var(--muted);font-size:var(--text-base-lg);line-height:1.6;">
                        <p>{{ __('In this lesson, you will learn the core concepts behind flexible layouts using CSS Flexbox. We will cover flex containers, flex items, main and cross axis alignments, and responsive behaviors.') }}</p>
                        <p>{{ __('Make sure to download the starter files from the resources tab before proceeding with the coding exercises.') }}</p>
                    </div>
                    <div id="el-panel-resources" class="el-lesson-panel" role="tabpanel" aria-labelledby="el-tab-resources" hidden style="color:var(--muted);line-height:1.7;">
                        <h5 style="color:var(--dark);font-weight:700;">Lesson resources</h5>
                        <div class="el-resource-row"><i class="bi bi-file-earmark-text"></i><span><strong>Flexbox quick reference</strong><small>CSS properties covered in this lesson</small></span><span class="el-resource-type">Reading</span></div>
                        <div class="el-resource-row"><i class="bi bi-code-square"></i><span><strong>Starter layout exercise</strong><small>Practice the examples from the lesson</small></span><span class="el-resource-type">Practice</span></div>
                    </div>
                    <div id="el-panel-qa" class="el-lesson-panel" role="tabpanel" aria-labelledby="el-tab-qa" hidden style="color:var(--muted);line-height:1.7;">
                        <h5 style="color:var(--dark);font-weight:700;">Questions for this lesson</h5>
                        <p>Use your private notes below to capture questions to revisit with your instructor.</p>
                        <button type="button" class="el-btn-outline" id="el-qa-notes">Add a question to my notes</button>
                    </div>
                </div>

                <div class="el-card" style="margin-top:1.5rem;">
                    <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:1rem;margin-bottom:1rem;">
                        <div>
                            <div class="el-pill" style="margin-bottom:0.35rem;">Knowledge check</div>
                            <h4 style="font-family:'IM Fell English',serif;margin:0;color:var(--dark);">Test your Flexbox understanding</h4>
                        </div>
                        <span id="el-quiz-status" style="font-size:var(--text-sm);font-weight:700;color:var(--muted);">Not attempted</span>
                    </div>
                    <form id="el-lesson-quiz">
                        <fieldset style="border:0;padding:0;margin:0 0 1rem;">
                            <legend style="font-size:var(--text-base);font-weight:700;margin-bottom:0.65rem;">1. Which property controls the main axis direction?</legend>
                            <label style="display:block;margin:0.4rem 0;"><input type="radio" name="q1" value="a"> align-items</label>
                            <label style="display:block;margin:0.4rem 0;"><input type="radio" name="q1" value="b"> flex-direction</label>
                            <label style="display:block;margin:0.4rem 0;"><input type="radio" name="q1" value="c"> justify-content</label>
                        </fieldset>
                        <fieldset style="border:0;padding:0;margin:0 0 1rem;">
                            <legend style="font-size:var(--text-base);font-weight:700;margin-bottom:0.65rem;">2. What does <code>justify-content: space-between</code> do?</legend>
                            <label style="display:block;margin:0.4rem 0;"><input type="radio" name="q2" value="a"> Centers every item</label>
                            <label style="display:block;margin:0.4rem 0;"><input type="radio" name="q2" value="b"> Adds equal space around items</label>
                            <label style="display:block;margin:0.4rem 0;"><input type="radio" name="q2" value="c"> Places the first and last items at the edges</label>
                        </fieldset>
                        <fieldset style="border:0;padding:0;margin:0 0 1rem;">
                            <legend style="font-size:var(--text-base);font-weight:700;margin-bottom:0.65rem;">3. Which property allows items to move onto multiple lines?</legend>
                            <label style="display:block;margin:0.4rem 0;"><input type="radio" name="q3" value="a"> flex-wrap</label>
                            <label style="display:block;margin:0.4rem 0;"><input type="radio" name="q3" value="b"> flex-grow</label>
                            <label style="display:block;margin:0.4rem 0;"><input type="radio" name="q3" value="c"> order</label>
                        </fieldset>
                        <button class="el-btn-primary" type="submit">Submit quiz</button>
                        <button class="el-btn-outline" id="el-quiz-reset" type="button" style="display:none;margin-left:0.5rem;">Retake</button>
                        <p id="el-quiz-feedback" style="margin:1rem 0 0;color:var(--muted);" aria-live="polite"></p>
                    </form>
                </div>

                <div class="el-card" style="margin-top:1.5rem;">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.8rem;">
                        <h4 style="font-family:'IM Fell English',serif;margin:0;color:var(--dark);">Private lesson notes</h4>
                        <span id="el-notes-saved" style="font-size:var(--text-sm);color:var(--muted);">Autosaved</span>
                    </div>
                    <textarea id="el-lesson-notes" rows="5" placeholder="Write reflections, questions, or reminders for this lesson..." style="width:100%;border:1px solid rgba(123,107,53,0.2);border-radius:var(--radius-sm);padding:0.85rem;background:var(--cream-light);color:var(--dark);resize:vertical;"></textarea>
                </div>

                <div class="el-card" style="margin-top:1.5rem;background:var(--dark);color:#fff;">
                    <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:1rem;">
                        <div>
                            <div class="el-pill" style="color:var(--gold);margin-bottom:0.35rem;">Focus mode</div>
                            <h4 style="font-family:'IM Fell English',serif;margin:0;color:#fff;">Study session timer</h4>
                            <p style="color:rgba(255,255,255,0.6);font-size:var(--text-sm);margin:0.4rem 0 0;">Keep a distraction-free rhythm while you learn.</p>
                        </div>
                        <span style="font-size:var(--text-sm);color:rgba(255,255,255,0.6);"><i class="bi bi-check-circle"></i> <span id="el-session-count">0</span> sessions</span>
                    </div>
                    <div style="display:flex;align-items:center;gap:1rem;flex-wrap:wrap;margin-top:1.25rem;">
                        <select id="el-timer-duration" style="border:1px solid rgba(255,255,255,0.25);background:rgba(255,255,255,0.08);color:#fff;border-radius:var(--radius-sm);padding:0.55rem 0.7rem;">
                            <option value="25">25 min focus</option>
                            <option value="50">50 min focus</option>
                            <option value="5">5 min quick start</option>
                        </select>
                        <strong id="el-timer-display" style="font-size:2rem;letter-spacing:2px;min-width:110px;">25:00</strong>
                        <button id="el-timer-toggle" type="button" class="el-btn-primary" style="background:var(--gold);color:var(--dark);">Start focus</button>
                        <button id="el-timer-reset" type="button" class="el-btn-outline el-btn-outline--light" style="padding:0.55rem 0.9rem;">Reset</button>
                    </div>
                    <p id="el-timer-status" role="status" aria-live="polite" style="color:rgba(255,255,255,.65);font-size:var(--text-sm);margin:.8rem 0 0;"></p>
                </div>
            </div>
            
            {{-- Sidebar / Syllabus --}}
            <div class="col-lg-4">
                <div class="el-card h-100">
                    <h5 style="font-weight:700;margin-bottom:1rem;">{{ __('Course Content') }}</h5>
                    <div style="margin-bottom:1.5rem;">
                        <div style="display:flex;justify-content:space-between;font-size:var(--text-sm);margin-bottom:0.4rem;font-weight:600;">
                            <span>{{ __('Overall Progress') }}</span>
                            <span id="el-progress-label">{{ \App\Support\SiteText::translate($course_details['progress']) }}%</span>
                        </div>
                            <div style="width:100%;height:6px;background:var(--cream);border-radius:var(--radius-pill);overflow:hidden;">
                            <div id="el-lesson-progress" style="width:{{ $course_details['progress'] }}%;height:100%;background:var(--gold);border-radius:var(--radius-pill);"></div>
                        </div>
                    </div>
                    
                    <div>
                        @foreach($course_details['modules'] as $module)
                            <div style="margin-bottom:1rem;">
                                <button type="button" class="el-module-toggle" aria-expanded="true" aria-controls="el-module-lessons-{{ $loop->index }}" style="width:100%;text-align:left;font-weight:700;font-size:var(--text-base-lg);padding:0.75rem;background:var(--cream);border:0;border-radius:var(--radius-sm);display:flex;justify-content:space-between;align-items:center;color:var(--dark);">
                                    {{ \App\Support\SiteText::translate($module['title']) }}
                                    <i class="bi bi-chevron-down"></i>
                                </button>
                                <div id="el-module-lessons-{{ $loop->index }}" class="el-module-lessons" aria-hidden="false" style="padding:0.5rem 0;">
                                    @foreach($module['lessons'] as $lesson)
                                        <button type="button" class="el-lesson-row" data-lesson-title="{{ $lesson['title'] }}" data-duration="{{ $lesson['duration'] }}" data-completed="{{ !empty($lesson['completed']) ? 'true' : 'false' }}" aria-current="{{ !empty($lesson['active']) ? 'true' : 'false' }}" style="width:100%;text-align:left;padding:0.75rem 1rem;font-size:var(--text-sm);display:flex;justify-content:space-between;align-items:center;border:0;border-left:2px solid {{ !empty($lesson['active']) ? 'var(--primary)' : 'transparent' }};background:{{ !empty($lesson['active']) ? 'rgba(196,168,64,0.05)' : 'transparent' }};cursor:pointer;">
                                            <div style="display:flex;align-items:center;gap:0.75rem;">
                                                @if(!empty($lesson['completed']))
                                                    <i class="bi bi-check-circle-fill" style="color:var(--success, #28a745);"></i>
                                                @elseif(!empty($lesson['active']))
                                                    <i class="bi bi-play-circle-fill" style="color:var(--primary);"></i>
                                                @else
                                                    <i class="bi bi-circle" style="color:var(--muted);"></i>
                                                @endif
                                                <span style="color:{{ !empty($lesson['active']) ? 'var(--dark)' : 'var(--muted)' }};font-weight:{{ !empty($lesson['active']) ? '700' : '400' }};">{{ \App\Support\SiteText::translate($lesson['title']) }}</span>
                                            </div>
                                            <span style="color:var(--muted);font-size:var(--text-xs);">{{ \App\Support\SiteText::translate($lesson['duration']) }}</span>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    function initElearningCourseContent() {
        const key = 'werta-elearning-progress-{{ $course_id ?? 1 }}';
        const courseId = String(@json((string) ($course_id ?? 1)));
        const courseTitle = @json($course_details['title']);
        const courseInstructor = @json($course_details['instructor']);
        const completeButton = document.querySelector('.el-complete-lesson');
        let currentTitle = @json($course_details['current_lesson']);
        const rows = Array.from(document.querySelectorAll('.el-lesson-row'));
        const progressBar = document.getElementById('el-lesson-progress');
        let saved = [];
        try {
            const storedProgress = JSON.parse(localStorage.getItem(key) || '[]');
            saved = Array.isArray(storedProgress) ? storedProgress : [];
        } catch (error) {
            localStorage.removeItem(key);
        }
        const quiz = document.getElementById('el-lesson-quiz');
        const quizStatus = document.getElementById('el-quiz-status');
        const quizFeedback = document.getElementById('el-quiz-feedback');
        const quizReset = document.getElementById('el-quiz-reset');
        const quizKey = 'werta-elearning-quiz-{{ $course_id ?? 1 }}';
        const notes = document.getElementById('el-lesson-notes');
        const notesKey = function () { return 'werta-elearning-notes-{{ $course_id ?? 1 }}-' + encodeURIComponent(currentTitle); };
        const timerDisplay = document.getElementById('el-timer-display');
        const timerToggle = document.getElementById('el-timer-toggle');
        const timerReset = document.getElementById('el-timer-reset');
        const timerDuration = document.getElementById('el-timer-duration');
        const sessionCount = document.getElementById('el-session-count');
        const sessionKey = 'werta-elearning-focus-sessions';
        let timerSeconds = Number(timerDuration.value) * 60;
        let timerInterval = null;
        rows.filter(function (row) { return row.dataset.completed === 'true'; }).forEach(function (row) {
            if (!saved.includes(row.dataset.lessonTitle)) saved.push(row.dataset.lessonTitle);
        });

        function saveProgress() { localStorage.setItem(key, JSON.stringify(saved)); }
        function syncCourseProgress(progress) {
            let courses = [];
            try {
                const storedCourses = JSON.parse(localStorage.getItem('werta-elearning-enrolled-courses') || '[]');
                courses = Array.isArray(storedCourses) ? storedCourses : [];
            } catch (error) {
                localStorage.removeItem('werta-elearning-enrolled-courses');
            }
            let course = courses.find(function (item) { return String(item.id) === courseId; });
            if (!course) {
                course = { id: courseId, title: courseTitle, instructor: courseInstructor, icon: 'journal-bookmark' };
                courses.push(course);
            }
            course.progress = progress;
            localStorage.setItem('werta-elearning-enrolled-courses', JSON.stringify(courses));
        }
        function selectLesson(row) {
            if (!row) return;
            currentTitle = row.dataset.lessonTitle;
            document.getElementById('el-current-lesson-title').textContent = currentTitle;
            document.getElementById('el-player-lesson-title').textContent = currentTitle;
            rows.forEach(function (item) {
                item.setAttribute('aria-current', item === row ? 'true' : 'false');
                item.classList.toggle('is-active', item === row);
            });
            const completed = saved.includes(currentTitle);
            completeButton.classList.toggle('is-complete', completed);
            completeButton.innerHTML = completed ? '<i class="bi bi-check-circle-fill"></i> Lesson completed' : '<i class="bi bi-check2-circle"></i> Mark lesson complete';
            notes.value = localStorage.getItem(notesKey()) || '';
            const playerStatus = document.getElementById('el-player-status');
            playerStatus.textContent = 'Lesson preview';
            document.getElementById('el-player-toggle').setAttribute('aria-pressed', 'false');
            document.getElementById('el-player-toggle').innerHTML = '<i class="bi bi-play-fill" aria-hidden="true"></i>';
            const currentIndex = rows.indexOf(row);
            document.getElementById('el-lesson-previous').disabled = currentIndex <= 0;
            const nextButton = document.getElementById('el-lesson-next');
            nextButton.disabled = rows.length === 0;
            nextButton.innerHTML = currentIndex >= rows.length - 1
                ? 'Finish course <i class="bi bi-check2-circle" aria-hidden="true"></i>'
                : 'Next <i class="bi bi-chevron-right" aria-hidden="true"></i>';
        }

        function updateProgress() {
            const total = rows.length;
            const done = rows.filter(function (row) { return saved.includes(row.dataset.lessonTitle); }).length;
            const percent = total ? Math.round((done / total) * 100) : 0;
            if (progressBar) progressBar.style.width = percent + '%';
            syncCourseProgress(percent);
            const progressLabel = document.getElementById('el-progress-label');
            if (progressLabel) progressLabel.textContent = percent + '%';
            rows.forEach(function (row) {
                const icon = row.querySelector('.bi');
                if (icon) {
                    icon.className = saved.includes(row.dataset.lessonTitle) ? 'bi bi-check-circle-fill' : (row.dataset.lessonTitle === currentTitle ? 'bi bi-play-circle-fill' : 'bi bi-circle');
                    icon.style.color = saved.includes(row.dataset.lessonTitle) ? 'var(--success, #28a745)' : (row.dataset.lessonTitle === currentTitle ? 'var(--primary)' : 'var(--muted)');
                }
            });
        }
        if (saved.includes(currentTitle) && completeButton) {
            completeButton.classList.add('is-complete');
            completeButton.innerHTML = '<i class="bi bi-check-circle-fill"></i> Lesson completed';
        }
        if (completeButton) completeButton.addEventListener('click', function () {
            const isNewCompletion = !saved.includes(currentTitle);
            if (isNewCompletion) saved.push(currentTitle);
            saveProgress();
            if (isNewCompletion) {
                const goalKey = 'werta-elearning-weekly-goal';
                const weeklyGoal = Math.min(Number(localStorage.getItem(goalKey) || 0) + 1, 5);
                localStorage.setItem(goalKey, String(weeklyGoal));
            }
            completeButton.classList.add('is-complete');
            completeButton.innerHTML = '<i class="bi bi-check-circle-fill"></i> Lesson completed';
            updateProgress();
        });
        updateProgress();
        const initialRow = rows.find(function (row) { return row.dataset.lessonTitle === currentTitle; });
        if (initialRow) selectLesson(initialRow);

        document.getElementById('el-lesson-previous').addEventListener('click', function () {
            const index = rows.findIndex(function (row) { return row.dataset.lessonTitle === currentTitle; });
            selectLesson(rows[Math.max(index - 1, 0)]);
        });
        document.getElementById('el-lesson-next').addEventListener('click', function () {
            const index = rows.findIndex(function (row) { return row.dataset.lessonTitle === currentTitle; });
            if (index >= rows.length - 1) {
                completeButton.click();
                return;
            }
            selectLesson(rows[index + 1]);
        });
        rows.forEach(function (row) { row.addEventListener('click', function () { selectLesson(row); }); });
        document.querySelectorAll('.el-module-toggle').forEach(function (button) {
            button.addEventListener('click', function () {
                const expanded = button.getAttribute('aria-expanded') === 'true';
                const panel = button.nextElementSibling;
                button.setAttribute('aria-expanded', expanded ? 'false' : 'true');
                panel.classList.toggle('is-collapsed', expanded);
                panel.setAttribute('aria-hidden', expanded ? 'true' : 'false');
                panel.inert = expanded;
            });
        });
        document.getElementById('el-player-toggle').addEventListener('click', function () {
            const playing = this.getAttribute('aria-pressed') !== 'true';
            this.setAttribute('aria-pressed', playing ? 'true' : 'false');
            this.innerHTML = playing ? '<i class="bi bi-pause-fill" aria-hidden="true"></i>' : '<i class="bi bi-play-fill" aria-hidden="true"></i>';
            document.getElementById('el-player-status').textContent = playing ? 'Preview playing' : 'Lesson preview';
        });
        document.querySelectorAll('.el-lesson-tabs [data-panel]').forEach(function (tab) {
            tab.addEventListener('click', function () {
                document.querySelectorAll('.el-lesson-tabs [data-panel]').forEach(function (item) {
                    const active = item === tab;
                    item.classList.toggle('active', active);
                    item.setAttribute('aria-selected', active ? 'true' : 'false');
                });
                document.querySelectorAll('.el-lesson-panel').forEach(function (panel) {
                    panel.hidden = panel.id !== 'el-panel-' + tab.dataset.panel;
                });
            });
        });
        document.getElementById('el-qa-notes').addEventListener('click', function () {
            const marker = 'Question: ';
            notes.value += (notes.value ? '\n\n' : '') + marker;
            notes.focus();
            notes.dispatchEvent(new Event('input', { bubbles: true }));
        });

        if (notes) {
            notes.value = localStorage.getItem(notesKey()) || '';
            notes.addEventListener('input', function () {
                localStorage.setItem(notesKey(), notes.value);
                const savedLabel = document.getElementById('el-notes-saved');
                if (savedLabel) savedLabel.textContent = 'Saved just now';
            });
        }
        const previousScore = localStorage.getItem(quizKey);
        if (previousScore !== null) {
            quizStatus.textContent = previousScore + '/3 passed';
            quizFeedback.textContent = previousScore >= 2 ? 'Great work — this knowledge check is complete.' : 'Keep practising and try the quiz again.';
            quizReset.style.display = 'inline-block';
        }
        quiz.addEventListener('submit', function (event) {
            event.preventDefault();
            const answers = { q1: 'b', q2: 'c', q3: 'a' };
            let score = 0;
            Object.keys(answers).forEach(function (question) {
                const selected = quiz.querySelector('input[name="' + question + '"]:checked');
                if (selected && selected.value === answers[question]) score++;
            });
            localStorage.setItem(quizKey, score);
            quizStatus.textContent = score + '/3 ' + (score >= 2 ? 'passed' : 'to review');
            quizFeedback.textContent = score >= 2 ? 'Great work — this knowledge check is complete.' : 'Review the lesson and try again to reach the 2/3 pass mark.';
            quizReset.style.display = 'inline-block';
        });
        quizReset.addEventListener('click', function () {
            quiz.reset();
            quizStatus.textContent = 'Not attempted';
            quizFeedback.textContent = '';
            quizReset.style.display = 'none';
            localStorage.removeItem(quizKey);
        });

        function renderTimer() {
            const minutes = String(Math.floor(timerSeconds / 60)).padStart(2, '0');
            const seconds = String(timerSeconds % 60).padStart(2, '0');
            timerDisplay.textContent = minutes + ':' + seconds;
        }
        function stopTimer() {
            clearInterval(timerInterval);
            timerInterval = null;
            timerToggle.textContent = 'Start focus';
        }
        sessionCount.textContent = localStorage.getItem(sessionKey) || '0';
        timerDuration.addEventListener('change', function () {
            stopTimer();
            timerSeconds = Number(this.value) * 60;
            renderTimer();
        });
        timerToggle.addEventListener('click', function () {
            if (timerInterval) {
                stopTimer();
                document.getElementById('el-timer-status').textContent = 'Session paused.';
                return;
            }
            document.getElementById('el-timer-status').textContent = 'Stay focused. You can pause at any time.';
            timerToggle.textContent = 'Pause';
            timerInterval = setInterval(function () {
                timerSeconds--;
                renderTimer();
                if (timerSeconds <= 0) {
                    stopTimer();
                    const totalSessions = Number(localStorage.getItem(sessionKey) || 0) + 1;
                    localStorage.setItem(sessionKey, totalSessions);
                    sessionCount.textContent = totalSessions;
                    document.getElementById('el-timer-status').textContent = 'Focus session complete. Take a short break before continuing.';
                    timerSeconds = Number(timerDuration.value) * 60;
                    renderTimer();
                }
            }, 1000);
        });
        timerReset.addEventListener('click', function () {
            stopTimer();
            timerSeconds = Number(timerDuration.value) * 60;
            renderTimer();
            document.getElementById('el-timer-status').textContent = 'Timer reset.';
        });
        renderTimer();
    }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initElearningCourseContent);
    else initElearningCourseContent();
</script>
@endsection
