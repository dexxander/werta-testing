{{-- Instructor Showcase Section --}}
<section class="el-section bg-cream-light">
    <div class="el-container">
        <div class="text-center mb-5">
            <div class="el-pill">Meet The Experts</div>
            <h2 class="el-heading">Instructor Showcase</h2>
            <p class="el-subtext mx-auto">Learn from experienced professionals who are passionate about teaching and sharing their expertise.</p>
        </div>

        @if(count($instructors ?? []) > 0)
            <div class="row g-4">
                @foreach($instructors as $instructor)
                    <div class="col-md-6 col-lg-3">
                        <div class="el-card text-center h-100">
                            @if(!empty($instructor['avatar']))
                                <img src="{{ asset($instructor['avatar']) }}" alt="{{ $instructor['name'] }}" style="width:80px;height:80px;object-fit:cover;border-radius:50%;margin:0 auto 1rem;display:block;border:3px solid var(--gold);">
                            @else
                                <div style="width:80px;height:80px;border-radius:50%;margin:0 auto 1rem;display:flex;align-items:center;justify-content:center;background:var(--cream);border:3px solid rgba(196,168,64,0.3);">
                                    <i class="bi bi-person" style="font-size:2rem;color:var(--gold);opacity:0.5;"></i>
                                </div>
                            @endif
                            <h5 style="font-weight:700;font-size:1rem;margin-bottom:0.3rem;">{{ $instructor['name'] }}</h5>
                            <p style="font-size:0.8rem;color:var(--gold);font-weight:600;margin-bottom:0.6rem;">{{ $instructor['expertise'] ?? '' }}</p>
                            <div style="display:flex;justify-content:center;gap:1rem;font-size:0.8rem;color:var(--muted);margin-bottom:0.8rem;">
                                <span><i class="bi bi-people-fill"></i> {{ $instructor['students'] ?? 0 }}</span>
                                <span><i class="bi bi-star-fill" style="color:var(--gold);"></i> {{ $instructor['rating'] ?? '0.0' }}</span>
                            </div>
                            <p style="font-size:0.82rem;color:var(--muted);line-height:1.6;margin:0;">{{ $instructor['bio'] ?? '' }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            {{-- Empty State --}}
            <div class="el-empty-state">
                <i class="bi bi-person-workspace"></i>
                <h5>Instructors Coming Soon</h5>
                <p>We are onboarding expert instructors to deliver world-class learning experiences.</p>
            </div>
        @endif
    </div>
</section>
