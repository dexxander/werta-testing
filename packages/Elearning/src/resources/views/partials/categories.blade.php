{{-- ============================================================
     TEMP DUMMY DATA — FOR PREVIEW ONLY
     This @php block overrides $categories with fake data so you can
     see the UI. It only exists in this file — your controller's
     'categories' => [] empty state is untouched.

     TO REMOVE: delete the entire @php ... @endphp block below
     (from "TEMP DUMMY DATA START" to "TEMP DUMMY DATA END").
     Once deleted, $categories goes back to whatever the controller
     actually passes in (currently an empty array).
     ============================================================ --}}
@php
    // TEMP DUMMY DATA START
    $categories = [
        ['name' => 'Web Development', 'icon' => 'code-slash', 'course_count' => 128, 'url' => '#'],
        ['name' => 'Data Science', 'icon' => 'bar-chart-line', 'course_count' => 94, 'url' => '#'],
        ['name' => 'UI/UX Design', 'icon' => 'palette2', 'course_count' => 76, 'url' => '#'],
        ['name' => 'Digital Marketing', 'icon' => 'megaphone', 'course_count' => 61, 'url' => '#'],
        ['name' => 'Business', 'icon' => 'briefcase', 'course_count' => 88, 'url' => '#'],
        ['name' => 'Photography', 'icon' => 'camera', 'course_count' => 42, 'url' => '#'],
        ['name' => 'Mobile Development', 'icon' => 'phone', 'course_count' => 53, 'url' => '#'],
        ['name' => 'Cybersecurity', 'icon' => 'shield-lock', 'course_count' => 37, 'url' => '#'],
    ];
    // TEMP DUMMY DATA END
@endphp

{{-- Course Categories Section --}}
<section class="el-section bg-cream">
    <div class="el-container">
        <div class="text-center mb-5">
            <div class="el-pill">Browse Topics</div>
            <h2 class="el-heading">Course Categories</h2>
            <p class="el-subtext mx-auto">Explore our diverse range of course categories curated to match industry demands and your learning goals.</p>
        </div>

        @if(count($categories ?? []) > 0)
            <div class="row g-4">
                @foreach($categories as $category)
                    <div class="col-6 col-md-4 col-lg-3">
                        <a href="{{ $category['url'] ?? '#' }}" class="el-cat-card">
                            <div class="el-cat-card-icon">
                                <i class="bi bi-{{ $category['icon'] ?? 'book' }}"></i>
                            </div>
                            <h5>{{ $category['name'] ?? 'Category' }}</h5>
                            <span class="el-cat-card-count">{{ $category['course_count'] ?? 0 }} courses</span>
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            {{-- Empty State --}}
            <div class="el-empty-state">
                <i class="bi bi-grid-3x3-gap"></i>
                <h5>Categories Coming Soon</h5>
                <p>We are curating our course library. Check back soon for a wide range of topics to explore.</p>
            </div>
        @endif
    </div>
</section>

<style>
    /* ─── CATEGORIES: ICON-IN-CIRCLE (matches hero badges / stat icons) ── */
    .el-cat-card-icon {
        width: 56px;
        height: 56px;
        margin: 0 auto 1rem;
        border-radius: 50%;
        background: rgba(196,168,64,0.12);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.25s ease;
    }

    .el-cat-card-icon i {
        font-size: 1.5rem;
        color: var(--gold);
        margin: 0;
    }

    .el-cat-card:hover .el-cat-card-icon {
        background: var(--gold);
    }

    .el-cat-card:hover .el-cat-card-icon i {
        color: #fff;
    }

    /* ─── CATEGORIES: COURSE COUNT PILL ───────────────────── */
    .el-cat-card-count {
        display: inline-block;
        margin-top: 0.4rem;
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--muted);
        background: rgba(123,107,53,0.08);
        border-radius: 20px;
        padding: 0.25rem 0.75rem;
    }
</style>