{{-- Certificates & Achievements Section --}}
<section class="el-section bg-cream-light" id="el-certificates">
    <div class="el-container">
        <div class="text-center mb-5">
            <div class="el-pill">{{ __('Recognition') }}</div>
            <h2 class="el-heading">{{ __('Certificates & Achievements') }}</h2>
            <p class="el-subtext mx-auto">{{ __('Earn verifiable certificates, collect achievement badges, and build your professional portfolio.') }}</p>
        </div>

        @if(!empty($certificates) && count($certificates) > 0)
            @php
                // Split into groups of 4 so each group can be laid out as a
                // deterministic big-tile + right-column block, instead of
                // relying on CSS grid-row spanning across implicit rows
                // (which sizes rows off whichever item is *shortest* in
                // them — that was the bug: the big tile's content was
                // overflowing a row height meant for the small tiles).
                $certGroups = array_chunk($certificates, 4);
            @endphp

            <div class="el-bento-wrap">
                @foreach($certGroups as $group)
                    <div class="el-bento-block">

                        @if(isset($group[0]))
                            <div class="el-bento-tile el-bento-big">
                                <div class="el-feature-icon el-bento-icon">
                                    <i class="bi bi-{{ $group[0]['icon'] ?? 'patch-check' }}"></i>
                                </div>
                                <h5 class="fw-bold el-bento-title">{{ \App\Support\SiteText::translate($group[0]['title'] ?? '') }}</h5>
                                <p class="mb-0 el-bento-desc">{{ \App\Support\SiteText::translate($group[0]['description'] ?? '') }}</p>
                            </div>
                        @endif

                        <div class="el-bento-right">
                            @if(isset($group[1]))
                                <div class="el-bento-tile el-bento-wide">
                                    <div class="el-feature-icon el-bento-icon">
                                        <i class="bi bi-{{ $group[1]['icon'] ?? 'patch-check' }}"></i>
                                    </div>
                                    <div>
                                        <h5 class="fw-bold el-bento-title">{{ \App\Support\SiteText::translate($group[1]['title'] ?? '') }}</h5>
                                        <p class="mb-0 el-bento-desc">{{ \App\Support\SiteText::translate($group[1]['description'] ?? '') }}</p>
                                    </div>
                                </div>
                            @endif

                            @if(isset($group[2]) || isset($group[3]))
                                <div class="el-bento-small-row">
                                    @foreach([$group[2] ?? null, $group[3] ?? null] as $cert)
                                        @if($cert)
                                            <div class="el-bento-tile el-bento-small">
                                                <div class="el-feature-icon el-bento-icon">
                                                    <i class="bi bi-{{ $cert['icon'] ?? 'patch-check' }}"></i>
                                                </div>
                                                <h5 class="fw-bold el-bento-title">{{ \App\Support\SiteText::translate($cert['title'] ?? '') }}</h5>
                                                <p class="mb-0 el-bento-desc">{{ \App\Support\SiteText::translate($cert['description'] ?? '') }}</p>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </div>

                    </div>
                @endforeach
            </div>
        @else
            {{-- Empty State: kept as the standard empty state instead of fabricated dummy content --}}
            <div class="el-empty-state">
                <i class="bi bi-award"></i>
                <h5>{{ __('No Certificates Yet') }}</h5>
                <p>{{ __('Start your learning journey today. Your earned certificates and achievements will appear here.') }}</p>
            </div>
        @endif
    </div>
</section>

<style>
    /* ─── CERTIFICATES: BENTO GRID ─────────────────────────────
       ┌─────────────┬──────────────┐
       │             │   (wide)     │
       │   (big)     ├──────┬───────┤
       │             │(sml) │ (sml) │
       └─────────────┴──────┴───────┘
       Each block is its own 2-column grid: [big tile] | [right column].
       The right column stacks the wide tile over the small-tile row using
       flexbox, so its total height is whatever its own content needs —
       and the big tile (in the other grid column) stretches to match
       that height automatically via the default grid `align-items: stretch`.
       No fixed/implicit row-height guessing, so nothing can overflow. */

    .el-bento-wrap {
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }

    .el-bento-block {
        display: grid;
        grid-template-columns: 1.1fr 1fr;
        gap: 1.25rem;
        align-items: stretch;
    }

    .el-bento-right {
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }

    .el-bento-small-row {
        display: flex;
        gap: 1.25rem;
        flex: 1;
    }

    .el-bento-tile {
        background: var(--cream);
        border: 1px solid rgba(123,107,53,0.12);
        border-radius: 16px;
        padding: 1.75rem;
        transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
    }

    .el-bento-tile:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
        border-color: var(--primary);
    }

    .el-bento-icon {
        margin-bottom: 1.1rem;
    }

    .el-bento-title {
        margin-bottom: 0.5rem;
        color: var(--dark);
    }

    .el-bento-desc {
        font-size: var(--text-sm);
        line-height: 1.6;
        color: var(--muted);
    }

    /* Big tile: fills its grid column, height matches the right column */
    .el-bento-big {
        display: flex;
        flex-direction: column;
        justify-content: center;
        background: var(--dark);
        border-color: rgba(255,255,255,0.1);
        height: 100%;
    }
    .el-bento-big .el-bento-icon {
        width: 64px;
        height: 64px;
        background: rgba(196,168,64,0.18);
    }
    .el-bento-big .el-bento-icon i { font-size: 1.8rem; }
    .el-bento-big .el-bento-title { font-size: var(--text-xl); color: #fff; }
    .el-bento-big .el-bento-desc { font-size: var(--text-base-lg); color: rgba(255,255,255,0.6); }
    .el-bento-big:hover { border-color: var(--gold); }

    /* Wide tile: icon + text side by side */
    .el-bento-wide {
        display: flex;
        flex-direction: row;
        align-items: center;
        gap: 1.25rem;
    }
    .el-bento-wide .el-bento-icon { margin-bottom: 0; flex-shrink: 0; }

    /* Small tiles: equal-width pair in the row */
    .el-bento-small { flex: 1; }

    @media (max-width: 991px) {
        .el-bento-block {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 575px) {
        .el-bento-wide {
            flex-direction: column;
            align-items: flex-start;
        }
        .el-bento-small-row {
            flex-direction: column;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .el-bento-tile {
            transition: none;
        }
    }
</style>
