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
            'id' => 1,
            'title' => 'Complete Web Development Bootcamp',
            'instructor' => 'James Carter',
            'difficulty' => 'Beginner',
            'rating' => '4.8',
            'duration' => '32h',
            'students' => 4210,
            'image' => null,
            'topics' => ['HTML5 Foundations', 'CSS Layouts and Flexbox', 'Responsive Web Design', 'JavaScript Essentials', 'Building a Final Project'],
        ],
        [
            'id' => 2,
            'title' => 'Data Science & Machine Learning A-Z',
            'instructor' => 'Priya Nair',
            'difficulty' => 'Intermediate',
            'rating' => '4.9',
            'duration' => '28h',
            'students' => 3185,
            'image' => null,
            'topics' => ['Python for Data Analysis', 'Statistics Fundamentals', 'Data Cleaning', 'Machine Learning Models', 'Model Evaluation'],
        ],
        [
            'id' => 3,
            'title' => 'UI/UX Design Fundamentals',
            'instructor' => 'Sarah Chen',
            'difficulty' => 'Beginner',
            'rating' => '4.7',
            'duration' => '18h',
            'students' => 2674,
            'image' => null,
            'topics' => ['User Research', 'Personas and User Flows', 'Wireframing', 'Figma Prototyping', 'Usability Testing'],
        ],
        [
            'id' => 4,
            'title' => 'Digital Marketing Mastery',
            'instructor' => 'Ahmad Faiz',
            'difficulty' => 'Beginner',
            'rating' => '4.6',
            'duration' => '15h',
            'students' => 1932,
            'image' => null,
            'topics' => ['Marketing Strategy', 'SEO Fundamentals', 'Content Marketing', 'Social Media Campaigns', 'Analytics and Reporting'],
        ],
        [
            'id' => 5,
            'title' => 'Advanced React & Next.js',
            'instructor' => 'Elena Ruiz',
            'difficulty' => 'Advanced',
            'rating' => '4.9',
            'duration' => '24h',
            'students' => 1547,
            'image' => null,
            'topics' => ['React Architecture', 'Next.js Routing', 'Server Components', 'Data Fetching', 'Deployment'],
        ],
        [
            'id' => 6,
            'title' => 'Cybersecurity Essentials',
            'instructor' => 'Marcus Lee',
            'difficulty' => 'Intermediate',
            'rating' => '4.8',
            'duration' => '20h',
            'students' => 2098,
            'image' => null,
            'topics' => ['Network Security', 'Threat Detection', 'Identity and Access', 'Incident Response', 'Security Best Practices'],
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
                            <h5 style="font-weight:700;font-size:1.05rem;margin-bottom:0.5rem;"><a href="{{ route('elearning.course-preview', ['id' => $course['id'] ?? 0]) }}" style="color:inherit;text-decoration:none;">{{ $course['title'] }}</a></h5>
                            <p style="font-size:0.85rem;color:var(--muted);margin-bottom:0.8rem;">{{ $course['instructor'] ?? 'Instructor' }}</p>
                            <div style="display:flex;align-items:center;gap:1rem;font-size:0.8rem;color:var(--muted);margin-bottom:1rem;">
                                <span><i class="bi bi-star-fill" style="color:var(--gold);"></i> {{ $course['rating'] ?? '0.0' }}</span>
                                <span><i class="bi bi-clock"></i> {{ $course['duration'] ?? '0h' }}</span>
                                <span><i class="bi bi-people"></i> {{ $course['students'] ?? 0 }}</span>
                            </div>
                            <div style="margin-top:auto;">
                                <div style="display:flex;gap:0.6rem;align-items:center;">
                                    <a href="{{ route('elearning.course-preview', ['id' => $course['id'] ?? 0]) }}" class="el-btn-outline" style="width:100%;text-align:center;padding:0.65rem 0.8rem;">Preview Course</a>
                                    @if(session('client_logged_in'))
                                        <button onclick="openElearningSuccessModal()" class="el-btn-primary" style="width:100%;text-align:center;border:none;padding:0.8rem;">Enroll</button>
                                    @else
                                        <button onclick="openElearningModal()" class="el-btn-primary" style="width:100%;text-align:center;border:none;padding:0.8rem;">Enroll</button>
                                    @endif
                                </div>
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

{{-- Authentication Modal (for non-clients) --}}
<div id="elearningEnrollModal" class="el-modal-overlay">
    <div class="el-modal-content el-card">
        <button class="el-modal-close" onclick="closeElearningModal()"><i class="bi bi-x-lg"></i></button>
        <div class="text-center">
            <div style="width:64px;height:64px;background:rgba(196,168,64,0.15);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;">
                <i class="bi bi-person-lock" style="font-size:2rem;color:var(--gold);"></i>
            </div>
            <h4 style="font-family:'IM Fell English',serif;font-weight:700;color:var(--dark);margin-bottom:1rem;">Client Access Only</h4>
            <p style="color:var(--muted);font-size:0.95rem;margin-bottom:2rem;">Only Client accounts can enroll in courses. Please log in using a Client account to continue.</p>
            <div style="display:flex;gap:1rem;justify-content:center;">
                <button onclick="closeElearningModal()" class="el-btn-outline" style="padding:0.75rem 1.5rem;">Cancel</button>
                <a href="{{ url('/client/login') }}" class="el-btn-primary" style="padding:0.75rem 1.5rem;">Client Login</a>
            </div>
        </div>
    </div>
</div>

{{-- Success Modal (for clients) --}}
<div id="elearningSuccessModal" class="el-modal-overlay">
    <div class="el-modal-content el-card">
        <button class="el-modal-close" onclick="closeElearningSuccessModal()"><i class="bi bi-x-lg"></i></button>
        <div class="text-center">
            <div style="width:64px;height:64px;background:rgba(40,199,64,0.15);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;">
                <i class="bi bi-check-circle-fill" style="font-size:2rem;color:#28c740;"></i>
            </div>
            <h4 style="font-family:'IM Fell English',serif;font-weight:700;color:var(--dark);margin-bottom:1rem;">Enrolled Successfully!</h4>
            <p style="color:var(--muted);font-size:0.95rem;margin-bottom:2rem;">You have been enrolled in this course. Head to your dashboard to start learning.</p>
            <div style="display:flex;gap:1rem;justify-content:center;">
                <button onclick="closeElearningSuccessModal()" class="el-btn-outline" style="padding:0.75rem 1.5rem;">Close</button>
                <a href="{{ route('elearning.my-courses') }}" class="el-btn-primary" style="padding:0.75rem 1.5rem;">Go to My Courses</a>
            </div>
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
    function openElearningSuccessModal() { document.getElementById('elearningSuccessModal').classList.add('active'); }
    function closeElearningSuccessModal() { document.getElementById('elearningSuccessModal').classList.remove('active'); }
</script>
