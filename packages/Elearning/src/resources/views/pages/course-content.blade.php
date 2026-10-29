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
                    <div style="width:100%;aspect-ratio:16/9;background:var(--dark);display:flex;align-items:center;justify-content:center;position:relative;">
                        <i class="bi bi-play-circle-fill" style="font-size:4rem;color:var(--gold);cursor:pointer;transition:transform 0.2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'"></i>
                        <div style="position:absolute;bottom:1rem;left:1rem;color:#fff;font-size:var(--text-sm);background:rgba(0,0,0,0.5);padding:0.2rem 0.6rem;border-radius:4px;">
                            {{ \App\Support\SiteText::translate($course_details['current_lesson']) }}
                        </div>
                    </div>
                    
                    <div style="padding:2rem;">
                        <h3 style="font-family:'IM Fell English',serif;font-weight:700;color:var(--dark);margin-bottom:0.5rem;">{{ \App\Support\SiteText::translate($course_details['current_lesson']) }}</h3>
                        <p style="color:var(--muted);margin-bottom:1.5rem;">{{ __('Instructor:') }} {{ \App\Support\SiteText::translate($course_details['instructor']) }}</p>
                        
                        <div style="display:flex;gap:1rem;">
                            <button class="el-btn-outline"><i class="bi bi-chevron-left"></i> {{ __('Previous') }}</button>
                            <button class="el-btn-primary el-complete-lesson" type="button"><i class="bi bi-check2-circle"></i> {{ __('Mark lesson complete') }}</button>
                        </div>
                    </div>
                </div>
                
                {{-- Tabs --}}
                <div class="el-card">
                    <ul class="nav nav-tabs" style="border-bottom:2px solid var(--cream);margin-bottom:1.5rem;">
                        <li class="nav-item">
                            <a class="nav-link active" style="color:var(--dark);font-weight:700;border:none;border-bottom:2px solid var(--primary);background:transparent;" href="#">{{ __('Overview') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" style="color:var(--muted);border:none;background:transparent;" href="#">{{ __('Resources (2)') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" style="color:var(--muted);border:none;background:transparent;" href="#">Q&A</a>
                        </li>
                    </ul>
                    <div style="color:var(--muted);font-size:var(--text-base-lg);line-height:1.6;">
                        <p>{{ __('In this lesson, you will learn the core concepts behind flexible layouts using CSS Flexbox. We will cover flex containers, flex items, main and cross axis alignments, and responsive behaviors.') }}</p>
                        <p>{{ __('Make sure to download the starter files from the resources tab before proceeding with the coding exercises.') }}</p>
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
                </div>
            </div>
            
            {{-- Sidebar / Syllabus --}}
            <div class="col-lg-4">
                <div class="el-card h-100">
                    <h5 style="font-weight:700;margin-bottom:1rem;">{{ __('Course Content') }}</h5>
                    <div style="margin-bottom:1.5rem;">
                        <div style="display:flex;justify-content:space-between;font-size:var(--text-sm);margin-bottom:0.4rem;font-weight:600;">
                            <span>{{ __('Overall Progress') }}</span>
                            <span>{{ \App\Support\SiteText::translate($course_details['progress']) }}%</span>
                        </div>
                            <div style="width:100%;height:6px;background:var(--cream);border-radius:var(--radius-pill);overflow:hidden;">
                            <div id="el-lesson-progress" style="width:{{ $course_details['progress'] }}%;height:100%;background:var(--gold);border-radius:var(--radius-pill);"></div>
                        </div>
                    </div>
                    
                    <div>
                        @foreach($course_details['modules'] as $module)
                            <div style="margin-bottom:1rem;">
                                <div style="font-weight:700;font-size:var(--text-base-lg);padding:0.75rem;background:var(--cream);border-radius:var(--radius-sm);display:flex;justify-content:space-between;align-items:center;">
                                    {{ \App\Support\SiteText::translate($module['title']) }}
                                    <i class="bi bi-chevron-down"></i>
                                </div>
                                <div style="padding:0.5rem 0;">
                                    @foreach($module['lessons'] as $lesson)
                                        <div class="el-lesson-row" data-lesson-title="{{ $lesson['title'] }}" style="padding:0.75rem 1rem;font-size:var(--text-sm);display:flex;justify-content:space-between;align-items:center;border-left:2px solid {{ !empty($lesson['active']) ? 'var(--primary)' : 'transparent' }};background:{{ !empty($lesson['active']) ? 'rgba(196,168,64,0.05)' : 'transparent' }};cursor:pointer;" onmouseover="this.style.background='rgba(196,168,64,0.05)'" onmouseout="this.style.background='{{ !empty($lesson['active']) ? 'rgba(196,168,64,0.05)' : 'transparent' }}'">
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
                                        </div>
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
    document.addEventListener('DOMContentLoaded', function () {
        const key = 'werta-elearning-progress-{{ $course_id ?? 1 }}';
        const completeButton = document.querySelector('.el-complete-lesson');
        const currentTitle = @json($course_details['current_lesson']);
        const rows = Array.from(document.querySelectorAll('.el-lesson-row'));
        const progressBar = document.getElementById('el-lesson-progress');
        const saved = JSON.parse(localStorage.getItem(key) || '[]');
        const current = rows.find(function (row) { return row.dataset.lessonTitle === currentTitle; });
        const quiz = document.getElementById('el-lesson-quiz');
        const quizStatus = document.getElementById('el-quiz-status');
        const quizFeedback = document.getElementById('el-quiz-feedback');
        const quizReset = document.getElementById('el-quiz-reset');
        const quizKey = 'werta-elearning-quiz-{{ $course_id ?? 1 }}';
        const notes = document.getElementById('el-lesson-notes');
        const notesKey = 'werta-elearning-notes-{{ $course_id ?? 1 }}-{{ md5($course_details['current_lesson']) }}';
        const timerDisplay = document.getElementById('el-timer-display');
        const timerToggle = document.getElementById('el-timer-toggle');
        const timerReset = document.getElementById('el-timer-reset');
        const timerDuration = document.getElementById('el-timer-duration');
        const sessionCount = document.getElementById('el-session-count');
        const sessionKey = 'werta-elearning-focus-sessions';
        let timerSeconds = Number(timerDuration.value) * 60;
        let timerInterval = null;

        function updateProgress() {
            const total = rows.length;
            const done = rows.filter(function (row) { return saved.includes(row.dataset.lessonTitle); }).length;
            if (progressBar && total) progressBar.style.width = Math.max({{ $course_details['progress'] }}, Math.round((done / total) * 100)) + '%';
        }
        if (saved.includes(currentTitle) && completeButton) {
            completeButton.classList.add('is-complete');
            completeButton.innerHTML = '<i class="bi bi-check-circle-fill"></i> Lesson completed';
        }
        if (completeButton) completeButton.addEventListener('click', function () {
            if (!saved.includes(currentTitle)) saved.push(currentTitle);
            localStorage.setItem(key, JSON.stringify(saved));
            completeButton.classList.add('is-complete');
            completeButton.innerHTML = '<i class="bi bi-check-circle-fill"></i> Lesson completed';
            updateProgress();
        });
        updateProgress();

        if (notes) {
            notes.value = localStorage.getItem(notesKey) || '';
            notes.addEventListener('input', function () {
                localStorage.setItem(notesKey, notes.value);
                document.getElementById('el-notes-saved').textContent = 'Saved just now';
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
                return;
            }
            timerToggle.textContent = 'Pause';
            timerInterval = setInterval(function () {
                timerSeconds--;
                renderTimer();
                if (timerSeconds <= 0) {
                    stopTimer();
                    const totalSessions = Number(localStorage.getItem(sessionKey) || 0) + 1;
                    localStorage.setItem(sessionKey, totalSessions);
                    sessionCount.textContent = totalSessions;
                    alert('Focus session complete. Take a short break before continuing.');
                    timerSeconds = Number(timerDuration.value) * 60;
                    renderTimer();
                }
            }, 1000);
        });
        timerReset.addEventListener('click', function () {
            stopTimer();
            timerSeconds = Number(timerDuration.value) * 60;
            renderTimer();
        });
        renderTimer();
    });
</script>
@endsection
