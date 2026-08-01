{{-- ============================================================
     TEMP DUMMY DATA — FOR PREVIEW ONLY
     This @php block overrides $certificates with fake data so you
     can see the UI. It only exists in this file — your controller's
     'certificates' => [] empty state is untouched.

     TO REMOVE: delete the entire @php ... @endphp block below
     (from "TEMP DUMMY DATA START" to "TEMP DUMMY DATA END").
     Once deleted, $certificates goes back to whatever the
     controller actually passes in (currently an empty array).
     ============================================================ --}}
@php
    // TEMP DUMMY DATA START
    $certificates = [
        [
            'title' => 'Verified Course Certificate',
            'icon' => 'patch-check',
            'description' => 'Awarded on completion of any paid course, verifiable via a unique certificate ID.',
        ],
        [
            'title' => 'Career Track Badge',
            'icon' => 'award',
            'description' => 'Earned after finishing every course in a Learning Path, signalling job-ready skills.',
        ],
        [
            'title' => 'Skill Mastery Badge',
            'icon' => 'stars',
            'description' => 'Unlocked by scoring 90% or higher on a course\'s final assessment.',
        ],
        [
            'title' => 'Shareable Portfolio',
            'icon' => 'link-45deg',
            'description' => 'A public profile link showcasing all your certificates and badges to employers.',
        ],
    ];
    // TEMP DUMMY DATA END
@endphp

{{-- Certificates & Achievements Section --}}
<section class="el-section bg-cream-light" id="el-certificates">
    <div class="el-container">
        <div class="text-center mb-5">
            <div class="el-pill">Recognition</div>
            <h2 class="el-heading">Certificates & Achievements</h2>
            <p class="el-subtext mx-auto">Earn verifiable certificates, collect achievement badges, and build your professional portfolio.</p>
        </div>

        @if(!empty($certificates) && count($certificates) > 0)
            <div class="row g-4">
                @foreach($certificates as $cert)
                    <div class="col-md-6 col-lg-3">
                        <div class="el-card text-center h-100 p-4">
                            {{-- Applied the established icon-in-circle motif --}}
                            <div class="el-feature-icon mx-auto mb-4">
                                <i class="bi bi-{{ $cert['icon'] ?? 'patch-check' }}"></i>
                            </div>
                            
                            <h5 class="fw-bold mb-2 el-cert-title">{{ $cert['title'] ?? '' }}</h5>
                            <p class="text-muted mb-0 el-cert-desc">
                                {{ $cert['description'] ?? '' }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            {{-- Empty State: Corrected to use the standard empty state instead of fabricated dummy content --}}
            <div class="el-empty-state">
                <i class="bi bi-award"></i>
                <h5>No Certificates Yet</h5>
                <p>Start your learning journey today. Your earned certificates and achievements will appear here.</p>
            </div>
        @endif
    </div>
</section>

<style>
    /* Scoped typography adjustments to replace the previous inline styles */
    .el-cert-title {
        font-size: 1rem;
    }
    
    .el-cert-desc {
        font-size: 0.875rem;
        line-height: 1.6;
    }
</style>