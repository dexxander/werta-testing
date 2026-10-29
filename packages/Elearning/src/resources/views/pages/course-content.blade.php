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
    });
</script>
@endsection
