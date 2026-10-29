{{-- Featured Courses Section --}}
<section class="el-section bg-cream-light" id="el-courses">
    <div class="el-container">
        <div class="text-center mb-5">
            <div class="el-pill">{{ __('Top Picks') }}</div>
            <h2 class="el-heading">{{ __('Featured Courses') }}</h2>
            <p class="el-subtext mx-auto">{{ __('Handpicked courses by our experts to help you get started on the right track.') }}</p>
        </div>

        @if(count($featured_courses ?? []) > 0)
            <div class="row g-4">
                @foreach($featured_courses as $course)
                    <div class="col-md-6 col-lg-4">
                        <div class="el-card h-100 d-flex flex-column">
                            @if(!empty($course['image']))
                                <img src="{{ asset($course['image']) }}" alt="{{ \App\Support\SiteText::translate($course['title']) }}" style="width:100%;height:180px;object-fit:cover;border-radius:var(--radius-md);margin-bottom:1.2rem;">
                            @else
                                <div style="width:100%;height:180px;background:var(--cream);border-radius:var(--radius-md);margin-bottom:1.2rem;display:flex;align-items:center;justify-content:center;">
                                    <i class="bi bi-image" style="font-size:2rem;color:var(--gold);opacity:0.3;"></i>
                                </div>
                            @endif
                            <div style="display:flex;align-items:center;gap:6px;margin-bottom:0.6rem;">
                                <span style="font-size:var(--text-xs);font-weight:700;background:rgba(196,168,64,0.15);color:var(--primary);padding:3px 10px;border-radius:4px;">{{ \App\Support\SiteText::translate($course['difficulty'] ?? 'Beginner') }}</span>
                            </div>
                            <h5 style="font-weight:700;font-size:var(--text-md);margin-bottom:0.5rem;"><a href="{{ route('elearning.course-preview', ['id' => $course['id'] ?? 0]) }}" style="color:inherit;text-decoration:none;">{{ \App\Support\SiteText::translate($course['title']) }}</a></h5>
                            <p style="font-size:var(--text-sm);color:var(--muted);margin-bottom:0.8rem;">{{ \App\Support\SiteText::translate($course['instructor'] ?? 'Instructor') }}</p>
                            @if(!empty($course['topics']))
                                <div class="el-course-topics">
                                    @foreach(array_slice($course['topics'], 0, 3) as $topic)
                                        <span><i class="bi bi-check2"></i>{{ \App\Support\SiteText::translate($topic) }}</span>
                                    @endforeach
                                </div>
                            @endif
                            <div style="display:flex;align-items:center;gap:1rem;font-size:var(--text-sm);color:var(--muted);margin-bottom:1rem;">
                                <span><i class="bi bi-star-fill" style="color:var(--gold);"></i> {{ \App\Support\SiteText::translate($course['rating'] ?? '0.0') }}</span>
                                <span><i class="bi bi-clock"></i> {{ \App\Support\SiteText::translate($course['duration'] ?? '0h') }}</span>
                                <span><i class="bi bi-people"></i> {{ \App\Support\SiteText::translate($course['students'] ?? 0) }}</span>
                            </div>
                            <div style="margin-top:auto;">
                                <div style="display:flex;gap:0.6rem;align-items:center;">
                                    <a href="{{ route('elearning.course-preview', ['id' => $course['id'] ?? 0]) }}" class="el-btn-outline" style="width:100%;text-align:center;padding:0.65rem 0.8rem;">{{ __('Preview Course') }}</a>
                                    @if(session('client_logged_in'))
                                        <button onclick="openElearningSuccessModal()" class="el-btn-primary" style="width:100%;text-align:center;border:none;padding:0.8rem;">{{ __('Enroll') }}</button>
                                    @else
                                        <button onclick="openElearningModal()" class="el-btn-primary" style="width:100%;text-align:center;border:none;padding:0.8rem;">{{ __('Enroll') }}</button>
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
                <h5>{{ __('No Courses Available Yet') }}</h5>
                <p>{{ __('Our team is working hard to bring you the best courses. Stay tuned for exciting learning opportunities.') }}</p>
            </div>
        @endif
    </div>
</section>

{{-- Authentication Modal (for non-clients) --}}
<div id="elearningEnrollModal" class="el-modal-overlay">
    <div class="el-modal-content el-card">
        <button class="el-modal-close" onclick="closeElearningModal()"><i class="bi bi-x-lg"></i></button>
        <div class="text-center">
            <div style="width:64px;height:64px;background:rgba(196,168,64,0.15);border-radius:var(--radius-full);display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;">
                <i class="bi bi-person-lock" style="font-size:2rem;color:var(--primary);"></i>
            </div>
            <h4 style="font-family:'IM Fell English',serif;font-weight:700;color:var(--dark);margin-bottom:1rem;">{{ __('Client Access Only') }}</h4>
            <p style="color:var(--muted);font-size:var(--text-base-lg);margin-bottom:2rem;">{{ __('Only Client accounts can enroll in courses. Please log in using a Client account to continue.') }}</p>
            <div style="display:flex;gap:1rem;justify-content:center;">
                <button onclick="closeElearningModal()" class="el-btn-outline" style="padding:0.75rem 1.5rem;">{{ __('Cancel') }}</button>
                <a href="{{ url('/client/login') }}" class="el-btn-primary" style="padding:0.75rem 1.5rem;">{{ __('Client Login') }}</a>
            </div>
        </div>
    </div>
</div>

{{-- Success Modal (for clients) --}}
<div id="elearningSuccessModal" class="el-modal-overlay">
    <div class="el-modal-content el-card">
        <button class="el-modal-close" onclick="closeElearningSuccessModal()"><i class="bi bi-x-lg"></i></button>
        <div class="text-center">
            <div style="width:64px;height:64px;background:rgba(40,199,64,0.15);border-radius:var(--radius-full);display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;">
                <i class="bi bi-check-circle-fill" style="font-size:2rem;color:#28c740;"></i>
            </div>
            <h4 style="font-family:'IM Fell English',serif;font-weight:700;color:var(--dark);margin-bottom:1rem;">{{ __('Enrolled Successfully!') }}</h4>
            <p style="color:var(--muted);font-size:var(--text-base-lg);margin-bottom:2rem;">{{ __('You have been enrolled in this course. Head to your dashboard to start learning.') }}</p>
            <div style="display:flex;gap:1rem;justify-content:center;">
                <button onclick="closeElearningSuccessModal()" class="el-btn-outline" style="padding:0.75rem 1.5rem;">{{ __('Close') }}</button>
                <a href="{{ route('elearning.my-courses') }}" class="el-btn-primary" style="padding:0.75rem 1.5rem;">{{ __('Go to My Courses') }}</a>
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
        background: #ffffff;
        border: 1.5px solid var(--primary);
        box-shadow: 0 20px 60px rgba(44, 36, 22, 0.25);
    }
    .el-modal-overlay.active .el-modal-content { transform: translateY(0); }
    .el-course-topics { display:grid; gap:0.35rem; margin:0 0 1rem; }
    .el-course-topics span { color:var(--muted); font-size:var(--text-sm); display:flex; align-items:center; gap:0.4rem; }
    .el-course-topics i { color:var(--primary); }
    .el-modal-close {
        position: absolute; top: 15px; right: 15px; background: none;
        border: none; color: var(--muted); font-size: var(--text-lg); cursor: pointer;
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
