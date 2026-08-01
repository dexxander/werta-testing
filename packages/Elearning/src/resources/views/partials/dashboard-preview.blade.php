{{-- Student Dashboard Preview Section --}}
<section class="el-section bg-cream">
    <div class="el-container">
        <div class="text-center mb-5">
            <div class="el-pill">Your Learning Hub</div>
            <h2 class="el-heading">Student Dashboard Preview</h2>
            <p class="el-subtext mx-auto">A premium dashboard experience to track your learning journey, goals, and achievements.</p>
        </div>

        <div class="el-dashboard-mock">
            <div class="el-dashboard-topbar">
                <span class="el-dashboard-dot red"></span>
                <span class="el-dashboard-dot yellow"></span>
                <span class="el-dashboard-dot green"></span>
                <span style="color:rgba(255,255,255,0.5);font-size:0.75rem;margin-left:12px;">Werta — Student Dashboard</span>
            </div>
            <div style="padding:2rem;background:var(--cream-light);">
                <div class="row g-4">
                    {{-- Active Courses --}}
                    <div class="col-md-8">
                        <div style="background:var(--cream);border-radius:10px;padding:1.5rem;height:100%;">
                            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
                                <h6 style="font-weight:700;margin:0;">Active Courses</h6>
                                <span style="font-size:0.75rem;color:var(--gold);font-weight:700;">View All</span>
                            </div>
                            <div style="text-align:center;padding:2.5rem 1rem;">
                                <i class="bi bi-journal-text" style="font-size:2.5rem;color:var(--gold);opacity:0.3;display:block;margin-bottom:0.8rem;"></i>
                                <p style="font-size:0.85rem;color:var(--muted);margin:0;">No active courses yet. Start your learning journey today.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Right Sidebar --}}
                    <div class="col-md-4">
                        {{-- Progress --}}
                        <div style="background:var(--cream);border-radius:10px;padding:1.2rem;margin-bottom:1rem;">
                            <h6 style="font-weight:700;margin-bottom:0.8rem;font-size:0.85rem;">Progress Tracker</h6>
                            <div style="display:flex;align-items:center;gap:0.8rem;">
                                <div style="width:50px;height:50px;border-radius:50%;border:3px solid var(--gold);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.8rem;color:var(--primary);">0%</div>
                                <div>
                                    <p style="font-size:0.8rem;color:var(--muted);margin:0;">Overall completion</p>
                                </div>
                            </div>
                        </div>

                        {{-- Goals --}}
                        <div style="background:var(--cream);border-radius:10px;padding:1.2rem;margin-bottom:1rem;">
                            <h6 style="font-weight:700;margin-bottom:0.5rem;font-size:0.85rem;">Weekly Goals</h6>
                            <p style="font-size:0.8rem;color:var(--muted);margin:0;">No goals set yet.</p>
                        </div>

                        {{-- Streak --}}
                        <div style="background:var(--cream);border-radius:10px;padding:1.2rem;">
                            <h6 style="font-weight:700;margin-bottom:0.5rem;font-size:0.85rem;"><i class="bi bi-fire" style="color:var(--gold);"></i> Learning Streak</h6>
                            <p style="font-size:1.6rem;font-weight:700;color:var(--primary-dark);margin:0;">0 <span style="font-size:0.8rem;color:var(--muted);font-weight:400;">days</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
