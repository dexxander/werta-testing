@extends('elearning::layout')

@section('elearning-body')
@php
    // TEMP DUMMY DATA START
    $category_name = ucwords(str_replace('-', ' ', $category_slug ?? 'Category'));
    
    $category_courses = [
        [
            'id' => 101,
            'title' => 'Mastering ' . $category_name,
            'instructor' => 'James Carter',
            'difficulty' => 'Beginner',
            'rating' => '4.8',
            'duration' => '32h',
            'students' => 4210,
            'topics' => ['Introduction to ' . $category_name, 'Core Concepts', 'Practical Techniques', 'Tools and Workflows', 'Final Project'],
        ],
        [
            'id' => 102,
            'title' => 'Advanced Concepts in ' . $category_name,
            'instructor' => 'Priya Nair',
            'difficulty' => 'Advanced',
            'rating' => '4.9',
            'duration' => '28h',
            'students' => 3185,
            'topics' => ['Advanced Foundations', 'Professional Workflows', 'Problem Solving', 'Industry Case Studies', 'Capstone Assessment'],
        ],
        [
            'id' => 103,
            'title' => $category_name . ' for Professionals',
            'instructor' => 'Sarah Chen',
            'difficulty' => 'Intermediate',
            'rating' => '4.7',
            'duration' => '18h',
            'students' => 2674,
            'topics' => ['Professional Essentials', 'Planning and Strategy', 'Collaboration', 'Quality Standards', 'Portfolio Project'],
        ]
    ];
    // TEMP DUMMY DATA END
@endphp

<section class="el-section bg-cream-light" id="category-courses">
    <div class="el-container">
        <div class="mb-5">
            <a href="{{ route('elearning.courses') }}" style="color:var(--gold);text-decoration:none;font-size:0.9rem;font-weight:700;"><i class="bi bi-arrow-left"></i> Back to Courses</a>
            <h2 class="el-heading mt-3">{{ $category_name }} Courses</h2>
            <p class="el-subtext">Explore top-rated courses specifically tailored for {{ $category_name }}.</p>
        </div>

        @if(count($category_courses) > 0)
            <div class="row g-4">
                @foreach($category_courses as $course)
                    <div class="col-md-6 col-lg-4">
                        <div class="el-card h-100 d-flex flex-column">
                            <div style="width:100%;height:180px;background:var(--cream);border-radius:8px;margin-bottom:1.2rem;display:flex;align-items:center;justify-content:center;">
                                <i class="bi bi-image" style="font-size:2rem;color:var(--gold);opacity:0.3;"></i>
                            </div>
                            <div style="display:flex;align-items:center;gap:6px;margin-bottom:0.6rem;">
                                <span style="font-size:0.75rem;font-weight:700;background:rgba(196,168,64,0.15);color:var(--primary);padding:3px 10px;border-radius:4px;">{{ $course['difficulty'] }}</span>
                            </div>
                            <h5 style="font-weight:700;font-size:1.05rem;margin-bottom:0.5rem;"><a href="{{ route('elearning.course-preview', ['id' => $course['id'] ?? 0]) }}" style="color:inherit;text-decoration:none;">{{ $course['title'] }}</a></h5>
                            <p style="font-size:0.85rem;color:var(--muted);margin-bottom:0.8rem;">{{ $course['instructor'] }}</p>
                            <div style="display:flex;align-items:center;gap:1rem;font-size:0.8rem;color:var(--muted);margin-bottom:1rem;">
                                <span><i class="bi bi-star-fill" style="color:var(--gold);"></i> {{ $course['rating'] }}</span>
                                <span><i class="bi bi-clock"></i> {{ $course['duration'] }}</span>
                                <span><i class="bi bi-people"></i> {{ $course['students'] }}</span>
                            </div>
                            <div style="margin-top:auto;display:flex;gap:0.6rem;">
                                <a href="{{ route('elearning.course-preview', ['id' => $course['id'] ?? 0]) }}" class="el-btn-outline" style="width:100%;text-align:center;padding:0.65rem 0.8rem;">Preview Course</a>
                                @if(session('client_logged_in'))
                                    <button onclick="openElearningModal()" class="el-btn-primary" style="width:100%;text-align:center;border:none;">Enroll Now</button>
                                @else
                                    <button onclick="openElearningModal()" class="el-btn-primary" style="width:100%;text-align:center;border:none;">Enroll Now</button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="el-empty-state">
                <i class="bi bi-journal-bookmark"></i>
                <h5>No Courses Available</h5>
                <p>We are currently updating our catalog for {{ $category_name }}.</p>
            </div>
        @endif
    </div>
</section>

{{-- Reuse the modal design --}}
<div id="elearningEnrollModal" class="el-modal-overlay">
    <div class="el-modal-content el-card">
        <button class="el-modal-close" onclick="closeElearningModal()"><i class="bi bi-x-lg"></i></button>
        <div class="text-center">
            @if(session('client_logged_in'))
                <div style="width:64px;height:64px;background:rgba(40,199,64,0.15);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;">
                    <i class="bi bi-check-circle-fill" style="font-size:2rem;color:#28c740;"></i>
                </div>
                <h4 style="font-family:'IM Fell English',serif;font-weight:700;color:var(--dark);margin-bottom:1rem;">Enrolled Successfully!</h4>
                <p style="color:var(--muted);font-size:0.95rem;margin-bottom:2rem;">You have been enrolled in this course. Head to your dashboard to start learning.</p>
                <div style="display:flex;gap:1rem;justify-content:center;">
                    <button onclick="closeElearningModal()" class="el-btn-outline" style="padding:0.75rem 1.5rem;">Close</button>
                    <a href="{{ route('elearning.my-courses') }}" class="el-btn-primary" style="padding:0.75rem 1.5rem;">Go to My Courses</a>
                </div>
            @else
                <div style="width:64px;height:64px;background:rgba(196,168,64,0.15);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;">
                    <i class="bi bi-person-lock" style="font-size:2rem;color:var(--gold);"></i>
                </div>
                <h4 style="font-family:'IM Fell English',serif;font-weight:700;color:var(--dark);margin-bottom:1rem;">Client Access Only</h4>
                <p style="color:var(--muted);font-size:0.95rem;margin-bottom:2rem;">Only Client accounts can enroll in courses. Please log in using a Client account to continue.</p>
                <div style="display:flex;gap:1rem;justify-content:center;">
                    <button onclick="closeElearningModal()" class="el-btn-outline" style="padding:0.75rem 1.5rem;">Cancel</button>
                    <a href="{{ url('/client/login') }}" class="el-btn-primary" style="padding:0.75rem 1.5rem;">Client Login</a>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .el-modal-overlay {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(44,36,22,0.6); z-index: 9999;
        display: flex; align-items: center; justify-content: center;
        opacity: 0; visibility: hidden; transition: all 0.3s ease;
        backdrop-filter: blur(4px);
    }
    .el-modal-overlay.active { opacity: 1; visibility: visible; }
    .el-modal-content {
        max-width: 450px; width: 90%; position: relative;
        transform: translateY(20px); transition: all 0.3s ease;
        background: var(--cream-light);
    }
    .el-modal-overlay.active .el-modal-content { transform: translateY(0); }
    .el-modal-close {
        position: absolute; top: 15px; right: 15px; background: none;
        border: none; color: var(--muted); font-size: 1.2rem; cursor: pointer;
        transition: color 0.2s;
    }
    .el-modal-close:hover { color: var(--dark); }
</style>

<script>
    function openElearningModal() { document.getElementById('elearningEnrollModal').classList.add('active'); }
    function closeElearningModal() { document.getElementById('elearningEnrollModal').classList.remove('active'); }
</script>
@endsection
