@extends('layouts.app')

@section('content')
    @include('partials.navbar')

    <style>
        .counselors-page-sec {
            padding: 50px 0 80px;
            min-height: 70vh;
        }

        .counselors-header {
            margin-bottom: 1.75rem;
        }

        .counselors-title {
            font-family: 'IM Fell English', serif;
            font-size: var(--text-4xl);
            color: var(--dark);
            margin-bottom: 0.5rem;
        }

        .counselors-subtitle {
            font-size: var(--text-base-lg);
            color: var(--muted);
            margin: 0;
        }

        .sample-preview-banner {
            background: rgba(196, 168, 64, 0.12);
            border: 1px solid rgba(196, 168, 64, 0.35);
            border-radius: var(--radius-md);
            padding: 0.85rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 2rem;
            color: var(--primary-dark);
            font-size: var(--text-base);
            font-weight: 500;
        }

        .sample-preview-banner i {
            font-size: 1.2rem;
            color: var(--primary);
            flex-shrink: 0;
        }

        .filter-bar-card {
            background: #ffffff;
            border-radius: var(--radius-lg);
            box-shadow: var(--card-ring), var(--shadow-sm);
            padding: 1.5rem;
            margin-bottom: 2.5rem;
        }

        .filter-label {
            font-size: var(--text-xs);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--muted);
            margin-bottom: 0.4rem;
            display: block;
        }

        .filter-select {
            width: 100%;
            padding: 0.6rem 0.9rem;
            border-radius: var(--radius-md);
            border: 1px solid rgba(123, 107, 53, 0.25);
            background-color: var(--cream-light);
            color: var(--dark);
            font-size: var(--text-base);
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .filter-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(123, 107, 53, 0.15);
        }

        .btn-filter-submit {
            background: var(--primary);
            color: #ffffff;
            border: none;
            padding: 0.65rem 1.4rem;
            border-radius: var(--radius-md);
            font-weight: 700;
            font-size: var(--text-base);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: background 0.2s;
            width: 100%;
        }

        .btn-filter-submit:hover {
            background: var(--primary-dark);
            color: #ffffff;
        }

        .filter-clear-wrap {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 1rem;
            padding-top: 0.75rem;
            border-top: 1px solid rgba(123, 107, 53, 0.1);
            font-size: var(--text-sm);
            color: var(--muted);
        }

        .btn-filter-clear {
            color: var(--primary);
            text-decoration: underline;
            font-weight: 600;
        }

        .btn-filter-clear:hover {
            color: var(--primary-dark);
        }

        .counselor-card {
            background: #ffffff;
            border-radius: var(--radius-lg);
            box-shadow: var(--card-ring), var(--shadow-md);
            padding: 1.5rem;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .counselor-card-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .counselor-avatar {
            width: 52px;
            height: 52px;
            border-radius: var(--radius-full);
            background: var(--sand);
            color: var(--primary-dark);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.15rem;
            letter-spacing: 1px;
            flex-shrink: 0;
        }

        .sample-profile-badge {
            font-size: var(--text-xs);
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            background: rgba(196, 168, 64, 0.18);
            color: var(--primary-dark);
            padding: 3px 8px;
            border-radius: var(--radius-pill);
            white-space: nowrap;
        }

        .counselor-name {
            font-family: 'IM Fell English', serif;
            font-size: 1.35rem;
            font-weight: 700;
            color: var(--dark);
            margin: 0 0 0.2rem 0;
            line-height: 1.25;
        }

        .counselor-title {
            font-size: var(--text-sm);
            color: var(--muted);
            margin-bottom: 0.9rem;
        }

        .counselor-specialties {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-bottom: 1.1rem;
        }

        .specialty-chip {
            font-size: var(--text-xs);
            font-weight: 600;
            color: var(--primary-dark);
            background: var(--cream-light);
            border: 1px solid rgba(123, 107, 53, 0.25);
            padding: 3px 9px;
            border-radius: var(--radius-pill);
        }

        .counselor-meta-list {
            display: flex;
            flex-direction: column;
            gap: 0.45rem;
            font-size: var(--text-sm);
            color: var(--muted);
            margin-bottom: 1.4rem;
        }

        .counselor-meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .counselor-meta-item i {
            color: var(--primary);
            font-size: 0.95rem;
            width: 16px;
            text-align: center;
            flex-shrink: 0;
        }

        .counselor-card-footer {
            border-top: 1px solid rgba(123, 107, 53, 0.12);
            padding-top: 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
        }

        .counselor-rate {
            font-size: var(--text-base);
            font-weight: 700;
            color: var(--primary-dark);
            line-height: 1.2;
        }

        .counselor-rate-unit {
            font-size: var(--text-xs);
            font-weight: 400;
            color: var(--muted);
            display: block;
        }

        .btn-view-profile {
            background: var(--cream-light);
            color: var(--primary-dark);
            border: 1.5px solid var(--primary);
            padding: 0.45rem 1rem;
            border-radius: var(--radius-sm);
            font-size: var(--text-base);
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s;
            white-space: nowrap;
        }

        .btn-view-profile:hover {
            background: var(--primary);
            color: #ffffff;
        }

        .no-results-card {
            background: #ffffff;
            border-radius: var(--radius-lg);
            box-shadow: var(--card-ring), var(--shadow-sm);
            padding: 3rem 2rem;
            text-align: center;
        }

        .no-results-card p {
            font-size: var(--text-base-lg);
            color: var(--muted);
            margin-bottom: 1rem;
        }

        /* Coming soon empty state */
        .el-empty-state {
            text-align: center;
            padding: 4rem 2rem;
            border: 1.5px dashed rgba(196, 168, 64, 0.35);
            border-radius: var(--radius-xl);
            background: rgba(196, 168, 64, 0.04);
            max-width: 680px;
            margin: 0 auto;
        }

        .el-empty-state-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.5rem;
            border-radius: var(--radius-full);
            background: rgba(196, 168, 64, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 2.25rem;
        }

        .el-empty-state h2 {
            font-family: 'IM Fell English', serif;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.75rem;
            font-size: var(--text-3xl);
        }

        .el-empty-state p {
            font-size: var(--text-md);
            color: var(--muted);
            max-width: 500px;
            margin: 0 auto 2rem;
            line-height: 1.6;
        }

        .el-empty-state-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }
    </style>

    <section class="counselors-page-sec bg-cream">
        <div class="container">
            @if(($totalApprovedCount ?? 0) === 0)
                {{-- Coming soon fallback when no approved counselors exist in database --}}
                <div class="el-empty-state">
                    <div class="el-empty-state-icon">
                        <i class="bi bi-person-badge"></i>
                    </div>
                    <p class="pill-label mb-2">Find a Counselor</p>
                    <h2>Directory Coming Soon</h2>
                    <p>We are currently onboarding and verifying licensed mental health practitioners across Malaysia. Our certified counselor matching directory will be available soon.</p>
                    <div class="el-empty-state-actions">
                        <a href="{{ url('/assessment') }}" class="btn-cta-filled">Take the Assessment</a>
                        <a href="{{ url('/') }}" class="btn-cta-outline-on-light">Return to Home</a>
                    </div>
                </div>
            @else
                {{-- Header & Title --}}
                <div class="counselors-header">
                    <h1 class="counselors-title">Find a Counselor</h1>
                    <p class="counselors-subtitle">Connect with supportive mental health guidance tailored to your journey.</p>
                </div>

                {{-- Sample Banner --}}
                <div class="sample-preview-banner">
                    <i class="bi bi-info-circle-fill"></i>
                    <span>Preview: these are sample profiles, not real counselors. Booking opens soon.</span>
                </div>

                {{-- Filter Bar --}}
                <div class="filter-bar-card">
                    <form method="GET" action="{{ route('public.counselors') }}">
                        <div class="row g-3 align-items-end">
                            <div class="col-12 col-sm-6 col-lg-3">
                                <label for="specialtyFilter" class="filter-label">Specialty</label>
                                <select id="specialtyFilter" name="specialty" class="filter-select">
                                    <option value="">All Specialties</option>
                                    @foreach($allSpecialties as $s)
                                        <option value="{{ $s }}" {{ ($specialty ?? '') === $s ? 'selected' : '' }}>{{ $s }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-sm-6 col-lg-3">
                                <label for="languageFilter" class="filter-label">Language</label>
                                <select id="languageFilter" name="language" class="filter-select">
                                    <option value="">All Languages</option>
                                    @foreach($allLanguages as $l)
                                        <option value="{{ $l }}" {{ ($language ?? '') === $l ? 'selected' : '' }}>{{ $l }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-sm-6 col-lg-2">
                                <label for="modeFilter" class="filter-label">Mode</label>
                                <select id="modeFilter" name="mode" class="filter-select">
                                    <option value="">All Modes</option>
                                    @foreach($allModes as $m)
                                        <option value="{{ $m }}" {{ ($mode ?? '') === $m ? 'selected' : '' }}>{{ $m }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-sm-6 col-lg-2">
                                <label for="stateFilter" class="filter-label">State</label>
                                <select id="stateFilter" name="state" class="filter-select">
                                    <option value="">All States</option>
                                    @foreach($allStates as $st)
                                        <option value="{{ $st }}" {{ ($state ?? '') === $st ? 'selected' : '' }}>{{ $st }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-lg-2">
                                <button type="submit" class="btn-filter-submit">
                                    <i class="bi bi-funnel-fill"></i> Filter
                                </button>
                            </div>
                        </div>

                        @if(!empty($specialty) || !empty($language) || !empty($mode) || !empty($state))
                            <div class="filter-clear-wrap">
                                <span>Showing filtered results ({{ $counselors->count() }})</span>
                                <a href="{{ route('public.counselors') }}" class="btn-filter-clear">Clear filters</a>
                            </div>
                        @endif
                    </form>
                </div>

                {{-- Counselor Cards Grid --}}
                @if($counselors->isEmpty())
                    <div class="no-results-card">
                        <p>No counselors match your selected filters.</p>
                        <a href="{{ route('public.counselors') }}" class="btn-filter-clear">Clear filters</a>
                    </div>
                @else
                    <div class="row g-4 row-cols-1 row-cols-md-2 row-cols-lg-3">
                        @foreach($counselors as $counselor)
                            @php
                                $nameClean = preg_replace('/^(Dr\.|Mr\.|Ms\.|Mrs\.)\s+/i', '', $counselor->name);
                                $words = array_values(array_filter(explode(' ', $nameClean)));
                                $initials = count($words) >= 2
                                    ? strtoupper(mb_substr($words[0], 0, 1) . mb_substr(end($words), 0, 1))
                                    : strtoupper(mb_substr($words[0] ?? 'C', 0, 2));
                            @endphp
                            <div class="col">
                                <div class="counselor-card">
                                    <div>
                                        <div class="counselor-card-header">
                                            <div class="counselor-avatar">{{ $initials }}</div>
                                            <span class="sample-profile-badge">Sample profile</span>
                                        </div>

                                        <h2 class="counselor-name">{{ $counselor->name }}</h2>
                                        <div class="counselor-title">{{ $counselor->display_title ?? 'Counselor' }}</div>

                                        @if(!empty($counselor->specialties))
                                            <div class="counselor-specialties">
                                                @foreach($counselor->specialties as $spec)
                                                    <span class="specialty-chip">{{ $spec }}</span>
                                                @endforeach
                                            </div>
                                        @endif

                                        <div class="counselor-meta-list">
                                            @if(!empty($counselor->languages))
                                                <div class="counselor-meta-item">
                                                    <i class="bi bi-translate"></i>
                                                    <span>{{ implode(', ', $counselor->languages) }}</span>
                                                </div>
                                            @endif
                                            @if(!empty($counselor->session_modes))
                                                <div class="counselor-meta-item">
                                                    <i class="bi bi-camera-video"></i>
                                                    <span>{{ implode(', ', $counselor->session_modes) }}</span>
                                                </div>
                                            @endif
                                            @if(!empty($counselor->state))
                                                <div class="counselor-meta-item">
                                                    <i class="bi bi-geo-alt"></i>
                                                    <span>{{ $counselor->state }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="counselor-card-footer">
                                        <div class="counselor-rate">
                                            @if(!is_null($counselor->rate_individual))
                                                RM {{ number_format($counselor->rate_individual, 0) }}
                                                <span class="counselor-rate-unit">per session</span>
                                            @endif
                                        </div>
                                        <a href="{{ url('/counselors/' . $counselor->id) }}" class="btn-view-profile">
                                            View profile
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            @endif
        </div>
    </section>

    @include('partials.footer')
@endsection
