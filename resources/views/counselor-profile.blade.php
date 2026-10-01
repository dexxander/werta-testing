@extends('layouts.app')

@section('content')
    @include('partials.navbar')

    <style>
        .profile-page-sec {
            padding: 40px 0 80px;
            min-height: 75vh;
        }

        .back-nav-wrap {
            margin-bottom: 1.5rem;
        }

        .back-nav-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            font-size: var(--text-base);
            transition: color 0.2s;
        }

        .back-nav-link:hover {
            color: var(--primary-dark);
            text-decoration: underline;
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

        .profile-container-wrap {
            max-width: 840px;
            margin: 0 auto;
        }

        .profile-card {
            background: #ffffff;
            border-radius: var(--radius-xl);
            box-shadow: var(--card-ring), var(--shadow-md);
            padding: 2.5rem 2rem;
        }

        .profile-header {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            padding-bottom: 1.75rem;
            border-bottom: 1px solid rgba(123, 107, 53, 0.15);
            margin-bottom: 2rem;
        }

        .profile-avatar {
            width: 80px;
            height: 80px;
            border-radius: var(--radius-full);
            background: var(--sand);
            color: var(--primary-dark);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.85rem;
            letter-spacing: 1px;
            flex-shrink: 0;
        }

        .profile-header-info {
            flex: 1;
            min-width: 0;
        }

        .profile-top-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.5rem;
            flex-wrap: wrap;
            margin-bottom: 0.25rem;
        }

        .profile-name {
            font-family: 'IM Fell English', serif;
            font-size: var(--text-3xl);
            font-weight: 700;
            color: var(--dark);
            margin: 0;
            line-height: 1.2;
        }

        .sample-profile-badge {
            font-size: var(--text-xs);
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            background: rgba(196, 168, 64, 0.18);
            color: var(--primary-dark);
            padding: 4px 10px;
            border-radius: var(--radius-pill);
            white-space: nowrap;
        }

        .profile-title {
            font-size: var(--text-base-lg);
            color: var(--muted);
            margin: 0;
        }

        .profile-section-heading {
            font-family: 'IM Fell English', serif;
            font-size: 1.35rem;
            color: var(--dark);
            margin: 0 0 0.8rem 0;
        }

        .profile-bio {
            font-size: var(--text-md);
            color: #4a4440;
            line-height: 1.8;
            margin-bottom: 2rem;
        }

        .profile-details-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
            padding: 1.5rem;
            background: var(--cream-light);
            border: 1px solid rgba(123, 107, 53, 0.15);
            border-radius: var(--radius-lg);
            margin-bottom: 2rem;
        }

        .profile-detail-item {
            display: flex;
            flex-direction: column;
            gap: 0.3rem;
        }

        .detail-label {
            font-size: var(--text-xs);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--muted);
        }

        .detail-value {
            font-size: var(--text-base);
            color: var(--dark);
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }

        .detail-value i {
            color: var(--primary);
        }

        .specialty-chip {
            font-size: var(--text-xs);
            font-weight: 600;
            color: var(--primary-dark);
            background: #ffffff;
            border: 1px solid rgba(123, 107, 53, 0.25);
            padding: 3px 10px;
            border-radius: var(--radius-pill);
            display: inline-block;
        }

        .profile-booking-bar {
            background: var(--cream);
            border-radius: var(--radius-lg);
            border: 1px solid rgba(123, 107, 53, 0.2);
            padding: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.5rem;
            flex-wrap: wrap;
        }

        .rate-wrap {
            display: flex;
            flex-direction: column;
        }

        .rate-label {
            font-size: var(--text-xs);
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--muted);
            font-weight: 700;
        }

        .rate-amount {
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--primary-dark);
            line-height: 1.2;
        }

        .rate-unit {
            font-size: var(--text-sm);
            color: var(--muted);
            font-weight: 400;
        }

        .btn-book-disabled {
            background: var(--muted);
            color: #ffffff;
            border: none;
            padding: 0.85rem 2rem;
            border-radius: var(--radius-sm);
            font-weight: 700;
            font-size: var(--text-base-lg);
            cursor: not-allowed;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        @media (max-width: 767px) {
            .profile-card {
                padding: 1.75rem 1.25rem;
            }

            .profile-header {
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }

            .profile-top-row {
                justify-content: center;
            }

            .profile-details-grid {
                grid-template-columns: 1fr;
                gap: 1.25rem;
                padding: 1.25rem 1rem;
            }

            .profile-booking-bar {
                flex-direction: column;
                align-items: stretch;
                text-align: center;
                gap: 1rem;
            }

            .btn-book-disabled {
                width: 100%;
                justify-content: center;
            }
        }
    </style>

    @php
        $nameClean = preg_replace('/^(Dr\.|Mr\.|Ms\.|Mrs\.)\s+/i', '', $counselor->name);
        $words = array_values(array_filter(explode(' ', $nameClean)));
        $initials = count($words) >= 2
            ? strtoupper(mb_substr($words[0], 0, 1) . mb_substr(end($words), 0, 1))
            : strtoupper(mb_substr($words[0] ?? 'C', 0, 2));
    @endphp

    <section class="profile-page-sec bg-cream">
        <div class="container">
            <div class="profile-container-wrap">
                <div class="back-nav-wrap">
                    <a href="{{ route('public.counselors') }}" class="back-nav-link">
                        <i class="bi bi-arrow-left"></i> Back to counselors
                    </a>
                </div>

                <div class="sample-preview-banner">
                    <i class="bi bi-info-circle-fill"></i>
                    <span>Preview: these are sample profiles, not real counselors. Booking opens soon.</span>
                </div>

                <div class="profile-card">
                    <div class="profile-header">
                        <div class="profile-avatar">{{ $initials }}</div>
                        <div class="profile-header-info">
                            <div class="profile-top-row">
                                <h1 class="profile-name">{{ $counselor->name }}</h1>
                                <span class="sample-profile-badge">Sample profile</span>
                            </div>
                            <div class="profile-title">{{ $counselor->display_title ?? 'Counselor' }}</div>
                        </div>
                    </div>

                    @if(!empty($counselor->bio))
                        <div>
                            <h2 class="profile-section-heading">About</h2>
                            <p class="profile-bio">{{ $counselor->bio }}</p>
                        </div>
                    @endif

                    @php
                        $hasDetails = (!empty($counselor->specialties) && count($counselor->specialties) > 0)
                            || !empty($counselor->years_experience)
                            || (!empty($counselor->languages) && count($counselor->languages) > 0)
                            || (!empty($counselor->session_modes) && count($counselor->session_modes) > 0)
                            || !empty($counselor->state);
                    @endphp

                    @if($hasDetails)
                        <div>
                            <h2 class="profile-section-heading">Details &amp; Practice</h2>
                            <div class="profile-details-grid">
                                @if(!empty($counselor->specialties) && count($counselor->specialties) > 0)
                                    <div class="profile-detail-item">
                                        <span class="detail-label">Specialties</span>
                                        <div class="detail-value">
                                            @foreach($counselor->specialties as $spec)
                                                <span class="specialty-chip">{{ $spec }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                @if(!empty($counselor->years_experience))
                                    <div class="profile-detail-item">
                                        <span class="detail-label">Experience</span>
                                        <div class="detail-value">
                                            <i class="bi bi-briefcase"></i>
                                            <span>{{ $counselor->years_experience }} years in practice</span>
                                        </div>
                                    </div>
                                @endif

                                @if(!empty($counselor->languages) && count($counselor->languages) > 0)
                                    <div class="profile-detail-item">
                                        <span class="detail-label">Languages</span>
                                        <div class="detail-value">
                                            <i class="bi bi-translate"></i>
                                            <span>{{ implode(', ', $counselor->languages) }}</span>
                                        </div>
                                    </div>
                                @endif

                                @if(!empty($counselor->session_modes) && count($counselor->session_modes) > 0)
                                    <div class="profile-detail-item">
                                        <span class="detail-label">Session Modes</span>
                                        <div class="detail-value">
                                            <i class="bi bi-camera-video"></i>
                                            <span>{{ implode(', ', $counselor->session_modes) }}</span>
                                        </div>
                                    </div>
                                @endif

                                @if(!empty($counselor->state))
                                    <div class="profile-detail-item">
                                        <span class="detail-label">Location</span>
                                        <div class="detail-value">
                                            <i class="bi bi-geo-alt"></i>
                                            <span>{{ $counselor->state }}</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <div class="profile-booking-bar">
                        @if(!is_null($counselor->rate_individual))
                            <div class="rate-wrap">
                                <span class="rate-label">Individual Session Rate</span>
                                <div class="rate-amount">
                                    RM {{ number_format($counselor->rate_individual, 0) }}
                                    <span class="rate-unit">per session</span>
                                </div>
                            </div>
                        @endif

                        <button class="btn-book-disabled" disabled aria-disabled="true">
                            <i class="bi bi-calendar-x"></i> Booking opens soon
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('partials.footer')
@endsection
