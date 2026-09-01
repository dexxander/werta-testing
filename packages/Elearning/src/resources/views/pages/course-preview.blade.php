@extends('elearning::layout')

@section('elearning-body')
{{--
    TEMP DUMMY DATA — FOR PREVIEW ONLY
    This catalog supplements the temporary course cards with preview topics.
    If the block is removed, the page still renders a safe empty-state preview.
--}}
@php
    // Safe default when the temporary catalog below is removed.
    $preview_courses = [];
@endphp

@php
    // TEMP DUMMY DATA START
    $preview_courses = [
        1 => ['title' => 'Complete Web Development Bootcamp', 'instructor' => 'James Carter', 'difficulty' => 'Beginner', 'duration' => '32h', 'topics' => ['HTML5 Foundations', 'CSS Layouts and Flexbox', 'Responsive Web Design', 'JavaScript Essentials', 'Building a Final Project']],
        2 => ['title' => 'Data Science & Machine Learning A-Z', 'instructor' => 'Priya Nair', 'difficulty' => 'Intermediate', 'duration' => '28h', 'topics' => ['Python for Data Analysis', 'Statistics Fundamentals', 'Data Cleaning', 'Machine Learning Models', 'Model Evaluation']],
        3 => ['title' => 'UI/UX Design Fundamentals', 'instructor' => 'Sarah Chen', 'difficulty' => 'Beginner', 'duration' => '18h', 'topics' => ['User Research', 'Personas and User Flows', 'Wireframing', 'Figma Prototyping', 'Usability Testing']],
        4 => ['title' => 'Digital Marketing Mastery', 'instructor' => 'Ahmad Faiz', 'difficulty' => 'Beginner', 'duration' => '15h', 'topics' => ['Marketing Strategy', 'SEO Fundamentals', 'Content Marketing', 'Social Media Campaigns', 'Analytics and Reporting']],
        5 => ['title' => 'Advanced React & Next.js', 'instructor' => 'Elena Ruiz', 'difficulty' => 'Advanced', 'duration' => '24h', 'topics' => ['React Architecture', 'Next.js Routing', 'Server Components', 'Data Fetching', 'Deployment']],
        6 => ['title' => 'Cybersecurity Essentials', 'instructor' => 'Marcus Lee', 'difficulty' => 'Intermediate', 'duration' => '20h', 'topics' => ['Network Security', 'Threat Detection', 'Identity and Access', 'Incident Response', 'Security Best Practices']],
        101 => ['title' => 'Mastering Category Fundamentals', 'instructor' => 'James Carter', 'difficulty' => 'Beginner', 'duration' => '32h', 'topics' => ['Introduction and Core Concepts', 'Practical Techniques', 'Tools and Workflows', 'Common Mistakes', 'Final Project']],
        102 => ['title' => 'Advanced Category Concepts', 'instructor' => 'Priya Nair', 'difficulty' => 'Advanced', 'duration' => '28h', 'topics' => ['Advanced Foundations', 'Professional Workflows', 'Problem Solving', 'Industry Case Studies', 'Capstone Assessment']],
        103 => ['title' => 'Category for Professionals', 'instructor' => 'Sarah Chen', 'difficulty' => 'Intermediate', 'duration' => '18h', 'topics' => ['Professional Essentials', 'Planning and Strategy', 'Collaboration', 'Quality Standards', 'Portfolio Project']],
    ];
    // TEMP DUMMY DATA END

@endphp

@php
    $selected_course = $preview_courses[(int) ($course_id ?? 0)] ?? null;
    $course_title = $selected_course['title'] ?? 'Course Preview';
    $course_topics = $selected_course['topics'] ?? [];
@endphp

<section class="el-section bg-cream-light" id="course-preview">
    <div class="el-container">
        <div class="mb-4">
            <a href="{{ route('elearning.courses') }}" style="color:var(--gold);text-decoration:none;font-size:0.9rem;font-weight:700;"><i class="bi bi-arrow-left"></i> Back to Courses</a>
        </div>

        <div class="row g-4 align-items-start">
            <div class="col-lg-7">
                <div class="el-card">
                    <div class="el-pill">Course Preview</div>
                    <h2 class="el-heading">{{ $course_title }}</h2>
                    @if($selected_course)
                        <p class="el-subtext">Learn what this course covers before enrolling. The lessons are locked until enrollment is complete.</p>
                        <div style="display:flex;gap:1rem;flex-wrap:wrap;color:var(--muted);font-size:0.9rem;margin-top:1.5rem;">
                            <span><i class="bi bi-person"></i> {{ $selected_course['instructor'] }}</span>
                            <span><i class="bi bi-bar-chart"></i> {{ $selected_course['difficulty'] }}</span>
                            <span><i class="bi bi-clock"></i> {{ $selected_course['duration'] }}</span>
                        </div>
                    @else
                        <div class="el-empty-state">
                            <i class="bi bi-journal-bookmark"></i>
                            <h5>Course Preview Coming Soon</h5>
                            <p>This course is not available in the current catalog yet.</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-lg-5">
                <div class="el-card">
                    <h5 style="font-weight:700;margin-bottom:1.2rem;">Topics Inside This Course</h5>
                    @if(count($course_topics) > 0)
                        <div style="display:flex;flex-direction:column;gap:0.65rem;">
                            @foreach($course_topics as $index => $topic)
                                <button type="button" class="el-topic-preview" onclick="openEnrollmentRequiredModal()">
                                    <span><i class="bi bi-lock-fill"></i> {{ $index + 1 }}. {{ $topic }}</span>
                                    <i class="bi bi-chevron-right"></i>
                                </button>
                            @endforeach
                        </div>
                        <p style="font-size:0.8rem;color:var(--muted);margin:1.2rem 0 0;"><i class="bi bi-info-circle"></i> Preview only. Enroll to open lessons and start learning.</p>
                    @else
                        <p style="font-size:0.9rem;color:var(--muted);margin:0;">Topics will appear here when this course is added to the catalog.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<div id="enrollmentRequiredModal" class="el-modal-overlay">
    <div class="el-modal-content el-card">
        <button class="el-modal-close" onclick="closeEnrollmentRequiredModal()" aria-label="Close"><i class="bi bi-x-lg"></i></button>
        <div class="text-center">
            <div style="width:64px;height:64px;background:rgba(196,168,64,0.15);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;">
                <i class="bi bi-lock-fill" style="font-size:2rem;color:var(--gold);"></i>
            </div>
            <h4 style="font-family:'IM Fell English',serif;font-weight:700;color:var(--dark);margin-bottom:1rem;">Enrollment Required</h4>
            <p style="color:var(--muted);font-size:0.95rem;margin-bottom:2rem;">Please enroll in this course first to open this topic and start learning.</p>
            <button type="button" onclick="closeEnrollmentRequiredModal()" class="el-btn-primary" style="padding:0.75rem 1.5rem;">Got It</button>
        </div>
    </div>
</div>

<style>
    .el-topic-preview {
        width:100%;display:flex;align-items:center;justify-content:space-between;gap:0.75rem;
        text-align:left;background:var(--cream-light);border:1px solid rgba(123,107,53,0.12);
        border-radius:8px;padding:0.85rem 1rem;color:var(--dark);font-size:0.9rem;cursor:pointer;transition:all 0.2s;
    }
    .el-topic-preview:hover { border-color:var(--gold);background:rgba(196,168,64,0.08); }
    .el-topic-preview > span { display:flex;align-items:center;gap:0.65rem; }
    .el-topic-preview > span i, .el-topic-preview > i { color:var(--gold); }
    .el-modal-overlay { position:fixed;inset:0;background:rgba(44,36,22,0.6);z-index:9999;display:flex;align-items:center;justify-content:center;opacity:0;visibility:hidden;transition:all 0.3s ease;backdrop-filter:blur(4px); }
    .el-modal-overlay.active { opacity:1;visibility:visible; }
    .el-modal-content { max-width:450px;width:90%;position:relative;transform:translateY(20px);transition:all 0.3s ease;background:var(--cream-light); }
    .el-modal-overlay.active .el-modal-content { transform:translateY(0); }
    .el-modal-close { position:absolute;top:15px;right:15px;background:none;border:none;color:var(--muted);font-size:1.2rem;cursor:pointer; }
</style>

<script>
    function openEnrollmentRequiredModal() { document.getElementById('enrollmentRequiredModal').classList.add('active'); }
    function closeEnrollmentRequiredModal() { document.getElementById('enrollmentRequiredModal').classList.remove('active'); }
</script>
@endsection
