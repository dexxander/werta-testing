{{-- Testimonials Section --}}
<section class="el-section bg-cream">
    <div class="el-container">
        <div class="text-center mb-5">
            <div class="el-pill">Success Stories</div>
            <h2 class="el-heading">What Our Students Say</h2>
            <p class="el-subtext mx-auto">Hear from real students who have transformed their careers through our platform.</p>
        </div>

        @if(count($testimonials ?? []) > 0)
            <div class="row g-4">
                @foreach($testimonials as $testimonial)
                    <div class="col-md-6 col-lg-4">
                        <div class="el-card h-100">
                            <div style="display:flex;gap:4px;margin-bottom:1rem;">
                                @for($i = 0; $i < ($testimonial['rating'] ?? 5); $i++)
                                    <i class="bi bi-star-fill" style="color:var(--gold);font-size:0.85rem;"></i>
                                @endfor
                            </div>
                            <p style="font-size:var(--text-base);color:var(--dark);line-height:1.7;margin-bottom:1.2rem;font-style:italic;">"{{ $testimonial['content'] }}"</p>
                            <div style="display:flex;align-items:center;gap:0.8rem;margin-top:auto;">
                                @if(!empty($testimonial['avatar']))
                                    <img src="{{ asset($testimonial['avatar']) }}" alt="{{ $testimonial['name'] }}" style="width:40px;height:40px;border-radius:var(--radius-full);object-fit:cover;">
                                @else
                                    <div style="width:40px;height:40px;border-radius:var(--radius-full);background:var(--cream);display:flex;align-items:center;justify-content:center;">
                                        <i class="bi bi-person" style="color:var(--gold);"></i>
                                    </div>
                                @endif
                                <div>
                                    <strong style="font-size:var(--text-sm);">{{ $testimonial['name'] }}</strong>
                                    <p style="font-size:var(--text-xs);color:var(--muted);margin:0;">{{ $testimonial['role'] ?? 'Student' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            {{-- Empty State --}}
            <div class="el-empty-state">
                <i class="bi bi-chat-square-quote"></i>
                <h5>No Testimonials Yet</h5>
                <p>Be the first to share your learning experience with the Werta community.</p>
            </div>
        @endif
    </div>
</section>
