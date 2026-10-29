{{-- Course Categories Section --}}
<section class="el-section bg-cream">
    <div class="el-container">
        <div class="text-center mb-5">
            <div class="el-pill">{{ __('Browse Topics') }}</div>
            <h2 class="el-heading">{{ __('Course Categories') }}</h2>
            <p class="el-subtext mx-auto">{{ __('Explore our diverse range of course categories curated to match industry demands and your learning goals.') }}</p>
        </div>

        @if(count($categories ?? []) > 0)
            <div class="row g-4">
                @foreach($categories as $category)
                    <div class="col-6 col-md-4 col-lg-3">
                        <a href="{{ $category['url'] ?? '#' }}" class="el-cat-card">
                            <div class="el-cat-card-icon">
                                <i class="bi bi-{{ $category['icon'] ?? 'book' }}"></i>
                            </div>
                            <h5>{{ \App\Support\SiteText::translate($category['name'] ?? 'Category') }}</h5>
                            <span class="el-cat-card-count">{{ \App\Support\SiteText::translate($category['course_count'] ?? 0) }} {{ __('courses') }}</span>
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            {{-- Empty State --}}
            <div class="el-empty-state">
                <i class="bi bi-grid-3x3-gap"></i>
                <h5>{{ __('Categories Coming Soon') }}</h5>
                <p>{{ __('We are curating our course library. Check back soon for a wide range of topics to explore.') }}</p>
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
        border-radius: var(--radius-full);
        background: rgba(196,168,64,0.12);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.25s ease;
    }

    .el-cat-card-icon i {
        font-size: 1.5rem;
        color: var(--primary);
        margin: 0;
    }

    .el-cat-card:hover .el-cat-card-icon {
        background: var(--gold);
    }

    .el-cat-card:hover .el-cat-card-icon i {
        color: var(--dark);
    }

    /* ─── CATEGORIES: COURSE COUNT PILL ───────────────────── */
    .el-cat-card-count {
        display: inline-block;
        margin-top: 0.4rem;
        font-size: var(--text-sm);
        font-weight: 600;
        color: var(--muted);
        background: rgba(123,107,53,0.08);
        border-radius: var(--radius-pill);
        padding: 0.25rem 0.75rem;
    }
</style>
