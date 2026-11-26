@extends('elearning::layout')

@section('elearning-body')
@php
    $selected_course = $selected_course ?? null;
    $course_title = $selected_course['title'] ?? 'Course not found';
    $course_topics = $selected_course['topics'] ?? [];
@endphp

<section class="el-section bg-cream-light" id="course-preview">
    <div class="el-container">
        <div class="mb-4">
            <a href="{{ route('elearning.courses') }}" style="color:var(--primary);text-decoration:none;font-size:var(--text-base);font-weight:700;"><i class="bi bi-arrow-left"></i> {{ __('Back to Courses') }}</a>
        </div>

        <div class="row g-4 align-items-start">
            <div class="col-lg-7">
                <div class="el-card">
                    <div class="el-pill">{{ __('Course Preview') }}</div>
                    <h2 class="el-heading">{{ \App\Support\SiteText::translate($course_title) }}</h2>
                    @if($selected_course)
                        <p class="el-subtext">{{ __('Learn what this course covers before enrolling. The lessons are locked until enrollment is complete.') }}</p>
                        <div style="display:flex;gap:1rem;flex-wrap:wrap;color:var(--muted);font-size:var(--text-base);margin-top:1.5rem;">
                            <span><i class="bi bi-person"></i> {{ \App\Support\SiteText::translate($selected_course['instructor']) }}</span>
                            <span><i class="bi bi-bar-chart"></i> {{ \App\Support\SiteText::translate($selected_course['difficulty']) }}</span>
                            <span><i class="bi bi-clock"></i> {{ \App\Support\SiteText::translate($selected_course['duration']) }}</span>
                        </div>
                        <div style="display:flex;gap:.75rem;flex-wrap:wrap;margin-top:1.5rem;">
                            @if(session('client_logged_in'))
                                <button type="button" onclick="enrollElearningCourse(this)" data-course-id="{{ $selected_course['id'] }}" data-course-title="{{ $selected_course['title'] }}" data-course-instructor="{{ $selected_course['instructor'] }}" data-course-icon="{{ $selected_course['icon'] ?? 'journal-bookmark' }}" class="el-btn-primary">{{ __('Enroll in this course') }}</button>
                            @else
                                <a href="{{ url('/client/login') }}" class="el-btn-primary">{{ __('Log in to enroll') }}</a>
                            @endif
                            <a href="{{ route('elearning.course-content', ['id' => $course_id]) }}" class="el-btn-outline">{{ __('Open lesson workspace') }}</a>
                        </div>
                    @else
                        <div class="el-empty-state">
                            <i class="bi bi-journal-bookmark"></i>
                            <h5>{{ __('Course not found') }}</h5>
                            <p>{{ __('This course is not in the current catalog. Browse available courses to continue.') }}</p>
                            <a href="{{ route('elearning.courses') }}" class="el-btn-outline">{{ __('Browse courses') }}</a>
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-lg-5">
                <div class="el-card">
                    <h5 style="font-weight:700;margin-bottom:1.2rem;">{{ __('Topics Inside This Course') }}</h5>
                    @if(count($course_topics) > 0)
                        <div style="display:flex;flex-direction:column;gap:0.65rem;">
                            @foreach($course_topics as $index => $topic)
                                <button type="button" class="el-topic-preview" onclick="openCourseTopic({{ (int) $course_id }})">
                                    <span><i class="bi bi-lock-fill"></i> {{ \App\Support\SiteText::translate($index + 1) }}. {{ \App\Support\SiteText::translate($topic) }}</span>
                                    <i class="bi bi-chevron-right"></i>
                                </button>
                            @endforeach
                        </div>
                        <p style="font-size:var(--text-sm);color:var(--muted);margin:1.2rem 0 0;"><i class="bi bi-info-circle"></i> {{ __('Select a topic to continue to the lesson workspace. Enroll first to save it to My Courses.') }}</p>
                    @else
                        <p style="font-size:var(--text-base);color:var(--muted);margin:0;">{{ __('This course has no topic list yet. Browse the catalog for courses with a full outline.') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<div id="enrollmentRequiredModal" class="el-modal-overlay">
    <div class="el-modal-content el-card">
        <button class="el-modal-close" onclick="closeEnrollmentRequiredModal()" aria-label="{{ __('Close') }}"><i class="bi bi-x-lg"></i></button>
        <div class="text-center">
            <div style="width:64px;height:64px;background:rgba(196,168,64,0.15);border-radius:var(--radius-full);display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;">
                <i class="bi bi-lock-fill" style="font-size:2rem;color:var(--primary);"></i>
            </div>
            <h4 style="font-family:'IM Fell English',serif;font-weight:700;color:var(--dark);margin-bottom:1rem;">{{ __('Enrollment Required') }}</h4>
            <p style="color:var(--muted);font-size:var(--text-base-lg);margin-bottom:2rem;">{{ __('Please enroll in this course first to open this topic and start learning.') }}</p>
            <button type="button" onclick="closeEnrollmentRequiredModal()" class="el-btn-primary" style="padding:0.75rem 1.5rem;">{{ __('Got It') }}</button>
        </div>
    </div>
</div>
<div id="elearningSuccessModal" class="el-modal-overlay">
    <div class="el-modal-content el-card">
        <button type="button" class="el-modal-close" onclick="closeElearningSuccessModal()" aria-label="{{ __('Close') }}"><i class="bi bi-x-lg"></i></button>
        <div class="text-center">
            <div style="width:64px;height:64px;background:rgba(40,199,64,0.15);border-radius:var(--radius-full);display:grid;place-items:center;margin:0 auto 1.5rem;"><i class="bi bi-check-circle-fill" style="font-size:2rem;color:#28c740;"></i></div>
            <h4 style="font-family:'IM Fell English',serif;font-weight:700;color:var(--dark);margin-bottom:1rem;">{{ __('Enrolled Successfully!') }}</h4>
            <p style="color:var(--muted);font-size:var(--text-base-lg);margin-bottom:2rem;">{{ __('Your course is saved in My Courses. Open the lesson workspace whenever you are ready to learn.') }}</p>
            <div style="display:flex;gap:.75rem;justify-content:center;flex-wrap:wrap;">
                <button type="button" onclick="closeElearningSuccessModal()" class="el-btn-outline">{{ __('Keep browsing') }}</button>
                <a href="{{ route('elearning.my-courses') }}" class="el-btn-primary">{{ __('Go to My Courses') }}</a>
            </div>
        </div>
    </div>
</div>

<style>
    .el-topic-preview {
        width:100%;display:flex;align-items:center;justify-content:space-between;gap:0.75rem;
        text-align:left;background:var(--cream-light);border:1px solid rgba(123,107,53,0.12);
        border-radius:var(--radius-md);padding:0.85rem 1rem;color:var(--dark);font-size:var(--text-base);cursor:pointer;transition:all 0.2s;
    }
    .el-topic-preview:hover { border-color:var(--primary);background:rgba(196,168,64,0.08); }
    .el-topic-preview > span { display:flex;align-items:center;gap:0.65rem; }
    .el-topic-preview > span i, .el-topic-preview > i { color:var(--primary); }
    .el-modal-overlay { position:fixed;inset:0;background:rgba(44,36,22,0.6);z-index:9999;display:flex;align-items:center;justify-content:center;opacity:0;visibility:hidden;transition:all 0.3s ease;backdrop-filter:blur(4px); }
    .el-modal-overlay.active { opacity:1;visibility:visible; }
    .el-modal-content { max-width:450px;width:90%;position:relative;transform:translateY(20px);transition:all 0.3s ease;background:#ffffff;border:1.5px solid var(--primary);box-shadow:0 20px 60px rgba(44,36,22,0.25); }
    .el-modal-overlay.active .el-modal-content { transform:translateY(0); }
    .el-modal-close { position:absolute;top:15px;right:15px;background:none;border:none;color:var(--muted);font-size:var(--text-lg);cursor:pointer; }
</style>

<script>
    function openCourseTopic(courseId) {
        let enrolled = [];
        try { enrolled = JSON.parse(localStorage.getItem('werta-elearning-enrolled-courses') || '[]'); } catch (error) { localStorage.removeItem('werta-elearning-enrolled-courses'); }
        if (Array.isArray(enrolled) && enrolled.some(function (course) { return String(course.id) === String(courseId); })) {
            window.location.href = '{{ url('/elearning/course') }}/' + encodeURIComponent(courseId) + '/content';
            return;
        }
        document.getElementById('enrollmentRequiredModal').classList.add('active');
    }
    function closeEnrollmentRequiredModal() { document.getElementById('enrollmentRequiredModal').classList.remove('active'); }
    function closeElearningSuccessModal() { document.getElementById('elearningSuccessModal').classList.remove('active'); }
</script>
@endsection
