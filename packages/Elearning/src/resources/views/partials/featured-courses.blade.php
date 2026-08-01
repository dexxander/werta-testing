{{-- ============================================================
     TEMP DUMMY DATA — FOR PREVIEW ONLY
     This @php block overrides $featured_courses with fake data so
     you can see the UI. It only exists in this file — your
     controller's 'featured_courses' => [] empty state is untouched.

     TO REMOVE: delete the entire @php ... @endphp block below
     (from "TEMP DUMMY DATA START" to "TEMP DUMMY DATA END").
     Once deleted, $featured_courses goes back to whatever the
     controller actually passes in (currently an empty array).
     ============================================================ --}}
@php
    // TEMP DUMMY DATA START
    $featured_courses = [
        [
            'title' => 'Complete Web Development Bootcamp',
            'instructor' => 'James Carter',
            'difficulty' => 'Beginner',
            'rating' => '4.8',
            'duration' => '32h',
            'students' => 4210,
            'image' => null,
        ],
        [
            'title' => 'Data Science & Machine Learning A-Z',
            'instructor' => 'Priya Nair',
            'difficulty' => 'Intermediate',
            'rating' => '4.9',
            'duration' => '28h',
            'students' => 3185,
            'image' => null,
        ],
        [
            'title' => 'UI/UX Design Fundamentals',
            'instructor' => 'Sarah Chen',
            'difficulty' => 'Beginner',
            'rating' => '4.7',
            'duration' => '18h',
            'students' => 2674,
            'image' => null,
        ],
        [
            'title' => 'Digital Marketing Mastery',
            'instructor' => 'Ahmad Faiz',
            'difficulty' => 'Beginner',
            'rating' => '4.6',
            'duration' => '15h',
            'students' => 1932,
            'image' => null,
        ],
        [
            'title' => 'Advanced React & Next.js',
            'instructor' => 'Elena Ruiz',
            'difficulty' => 'Advanced',
            'rating' => '4.9',
            'duration' => '24h',
            'students' => 1547,
            'image' => null,
        ],
        [
            'title' => 'Cybersecurity Essentials',
            'instructor' => 'Marcus Lee',
            'difficulty' => 'Intermediate',
            'rating' => '4.8',
            'duration' => '20h',
            'students' => 2098,
            'image' => null,
        ],
    ];
    // TEMP DUMMY DATA END
@endphp

{{-- Featured Courses Section --}}
<section class="el-section bg-cream-light" id="el-courses">
    <div class="el-container">
        <div class="text-center mb-5">
            <div class="el-pill">Top Picks</div>
            <h2 class="el-heading">Featured Courses</h2>
            <p class="el-subtext mx-auto">Handpicked courses by our experts to help you get started on the right track.</p>
        </div>

        @if(count($featured_courses ?? []) > 0)
            <div class="row g-4">
                @foreach($featured_courses as $course)
                    <div class="col-md-6 col-lg-4">
                        <div class="el-card h-100 d-flex flex-column">
                            @if(!empty($course['image']))
                                <img src="{{ asset($course['image']) }}" alt="{{ $course['title'] }}" style="width:100%;height:180px;object-fit:cover;border-radius:8px;margin-bottom:1.2rem;">
                            @else
                                <div style="width:100%;height:180px;background:var(--cream);border-radius:8px;margin-bottom:1.2rem;display:flex;align-items:center;justify-content:center;">
                                    <i class="bi bi-image" style="font-size:2rem;color:var(--gold);opacity:0.3;"></i>
                                </div>
                            @endif
                            <div style="display:flex;align-items:center;gap:6px;margin-bottom:0.6rem;">
                                <span style="font-size:0.75rem;font-weight:700;background:rgba(196,168,64,0.15);color:var(--primary);padding:3px 10px;border-radius:4px;">{{ $course['difficulty'] ?? 'Beginner' }}</span>
                            </div>
                            <h5 style="font-weight:700;font-size:1.05rem;margin-bottom:0.5rem;">{{ $course['title'] }}</h5>
                            <p style="font-size:0.85rem;color:var(--muted);margin-bottom:0.8rem;">{{ $course['instructor'] ?? 'Instructor' }}</p>
                            <div style="display:flex;align-items:center;gap:1rem;font-size:0.8rem;color:var(--muted);margin-bottom:1rem;">
                                <span><i class="bi bi-star-fill" style="color:var(--gold);"></i> {{ $course['rating'] ?? '0.0' }}</span>
                                <span><i class="bi bi-clock"></i> {{ $course['duration'] ?? '0h' }}</span>
                                <span><i class="bi bi-people"></i> {{ $course['students'] ?? 0 }}</span>
                            </div>
                            <div style="margin-top:auto;">
                                <a href="#" class="el-btn-primary" style="width:100%;text-align:center;">Enroll Now</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            {{-- Empty State --}}
            <div class="el-empty-state">
                <i class="bi bi-journal-bookmark"></i>
                <h5>No Courses Available Yet</h5>
                <p>Our team is working hard to bring you the best courses. Stay tuned for exciting learning opportunities.</p>
            </div>
        @endif
    </div>
</section>