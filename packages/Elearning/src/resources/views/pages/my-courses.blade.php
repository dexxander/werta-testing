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
                <a href="{{ route('elearning.dashboard') }}" style="color:var(--gold);text-decoration:none;font-size:var(--text-base);font-weight:700;"><i class="bi bi-arrow-left"></i> Back to Dashboard</a>
                <h2 class="el-heading mt-3">My Enrolled Courses</h2>
                <p class="el-subtext">Pick up right where you left off.</p>
            </div>
            <div>
                <a href="{{ route('elearning.courses') }}" class="el-btn-outline">Browse More</a>
            </div>
        </div>

        <div class="row g-4">
            @foreach($my_courses as $course)
                <div class="col-md-6 col-lg-4">
                    <div class="el-card h-100 d-flex flex-column">
                        <div style="width:100%;height:160px;background:var(--cream);border-radius:var(--radius-md);margin-bottom:1.2rem;display:flex;align-items:center;justify-content:center;">
                            <i class="bi bi-{{ $course['thumbnail_icon'] }}" style="font-size:3rem;color:var(--gold);opacity:0.3;"></i>
                        </div>
                        <h5 style="font-weight:700;font-size:1.1rem;margin-bottom:0.3rem;">{{ $course['title'] }}</h5>
                        <p style="font-size:var(--text-sm);color:var(--muted);margin-bottom:1rem;">Instructor: {{ $course['instructor'] }}</p>
                        
                        <div style="margin-bottom:1rem;">
                            <div style="display:flex;justify-content:space-between;font-size:var(--text-sm);margin-bottom:0.4rem;font-weight:600;">
                                <span>Progress</span>
                                <span>{{ $course['progress'] }}%</span>
                            </div>
                            <div style="width:100%;height:6px;background:var(--cream);border-radius:var(--radius-pill);overflow:hidden;">
                                <div style="width:{{ $course['progress'] }}%;height:100%;background:var(--gold);border-radius:var(--radius-pill);"></div>
                            </div>
                        </div>
                        
                        <div style="margin-top:auto;display:flex;align-items:center;justify-content:space-between;">
                            <span style="font-size:var(--text-sm);color:var(--muted);"><i class="bi bi-clock-history"></i> {{ $course['last_accessed'] }}</span>
                            @if($course['progress'] == 100)
                                <a href="{{ route('elearning.course-content', ['id' => $course['id']]) }}" class="el-btn-outline" style="padding:0.4rem 1rem;font-size:0.85rem;">Review</a>
                            @else
                                <a href="{{ route('elearning.course-content', ['id' => $course['id']]) }}" class="el-btn-primary" style="padding:0.4rem 1rem;font-size:0.85rem;">Resume</a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
