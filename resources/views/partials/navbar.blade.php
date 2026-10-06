@php
    $isLoggedIn = session('staff_role') || session('counselor_logged_in') || session('client_logged_in') || session('parent_logged_in');
    $canPublish = session('counselor_logged_in') || session('client_logged_in') || session('parent_logged_in');
    if ($isLoggedIn) {
        $staffRole = session('staff_role');
        $roleKey = $staffRole
            ?: (session('counselor_logged_in') ? 'counselor' : (session('client_logged_in') ? 'client' : 'parent'));
        $role = ucfirst($roleKey);
        $roleLabel = __('nav.roles.' . $roleKey);
        $dashboardUrl = $staffRole
            ? ($staffRole === 'superadmin' ? '/superadmin/dashboard' : '/admin/dashboard')
            : (session('counselor_logged_in') ? route('counselor.dashboard') : (session('client_logged_in') ? '/client/dashboard' : '/parent/dashboard'));
        $logoutUrl = $staffRole
            ? ($staffRole === 'superadmin' ? '/superadmin/logout' : '/admin/logout')
            : (session('counselor_logged_in') ? route('counselor.logout') : (session('client_logged_in') ? '/client/logout' : '/parent/logout'));
        $profile = session($staffRole ? 'staff_profile' : strtolower($role) . '_profile', [
            'username' => session('counselor_logged_in') ? session('counselor_name', 'Counselor') : $role . ' User',
            'picture' => $staffRole === 'superadmin' ? 'bi-shield-check' : ($staffRole ? 'bi-shield-lock' : (session('counselor_logged_in') ? 'bi-person-badge' : 'bi-person-circle'))
        ]);
    }
    $currentLocale = app()->getLocale();
@endphp

<style>
    /* Shared hover-dropdown styling for nav links with submenus */
    .nav-dropdown {
        position: relative;
    }
    .nav-dropdown-menu {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        background-color: #ffffff;
        box-shadow: var(--card-ring), var(--shadow-lg);
        border: none;
        border-radius: var(--radius-md);
        min-width: 220px;
        padding: 0.5rem 0;
        z-index: 1000;
    }
    .nav-dropdown:hover .nav-dropdown-menu {
        display: block;
    }
    .nav-dropdown-menu a {
        display: block !important;
        padding: 0.6rem 1.5rem !important;
        color: var(--dark, #333) !important;
        font-size: var(--text-base) !important;
        text-decoration: none !important;
        text-transform: none !important;
        transition: background 0.2s, color 0.2s !important;
    }
    .nav-dropdown-menu a:hover {
        background-color: rgba(196,168,64,0.1) !important;
        color: var(--primary) !important;
    }
    .language-switcher {
        display: flex;
        align-items: center;
        gap: 2px;
        padding: 3px;
        border: 1px solid rgba(123, 107, 53, 0.22);
        border-radius: var(--radius-sm);
        background: rgba(255, 255, 255, 0.55);
    }
    .language-switcher a {
        padding: 0.3rem 0.45rem;
        border-radius: 4px;
        color: var(--muted);
        font-size: var(--text-sm);
        font-weight: 700;
        line-height: 1;
        text-decoration: none;
    }
    .language-switcher a:hover,
    .language-switcher a[aria-current="true"] {
        background: var(--primary);
        color: #fff;
    }
    .mobile-language-switcher {
        width: max-content;
        margin: 0.85rem 0.8rem 0.25rem;
    }
</style>

<nav class="site-navbar">
    <div class="navbar-inner">
        <a href="/" class="navbar-brand-wrap">
            <img src="{{ asset('images/Werta_Logo.png') }}" alt="{{ __('Werta Logo') }}" style="height: 46px; width: auto;">
            <span class="brand-title">
                <span class="brand-w">W</span><span class="brand-rest">ERTA</span>
            </span>
        </a>

        <ul class="nav-links">
            <li><a href="/">{{ __('nav.home') }}</a></li>
            <li><a href="{{ url('/counselors') }}">{{ __('nav.counselors') }}</a></li>
            <li><a href="{{ url('/assessment') }}">{{ __('nav.assessments') }}</a></li>
            <li class="nav-dropdown">
                <a href="{{ route('public.articles') }}">
                    {{ __('nav.articles') }} <i class="bi bi-chevron-down" style="font-size: var(--text-xs); margin-left:4px;"></i>
                </a>
                <div class="nav-dropdown-menu">
                    <a href="{{ route('public.articles') }}"><i class="bi bi-journal-text"></i> {{ __('nav.browse_articles') }}</a>
                    @if($canPublish)
                        <a href="{{ route('submissions.create') }}"><i class="bi bi-pencil-square"></i> {{ __('nav.publish_article') }}</a>
                        <a href="{{ route('submissions.index') }}"><i class="bi bi-folder2-open"></i> {{ __('nav.your_articles') }}</a>
                    @endif
                </div>
            </li>

            {{-- Replaced E-Learning Link with Hover Dropdown --}}
            <li class="nav-dropdown">
                <a href="{{ route('elearning.index') }}">
                    {{ __('nav.elearning_modules') }} <i class="bi bi-chevron-down" style="font-size: var(--text-xs); margin-left:4px;"></i>
                </a>
                <div class="nav-dropdown-menu">
                    <a href="{{ route('elearning.overview') }}">{{ __('nav.overview') }}</a>
                    <a href="{{ route('elearning.courses') }}">{{ __('nav.courses') }}</a>
                    <a href="{{ route('elearning.paths') }}">{{ __('nav.learning_paths') }}</a>
                    <a href="{{ route('elearning.dashboard') }}">{{ __('nav.dashboard_preview') }}</a>
                    <a href="{{ route('elearning.pricing') }}">{{ __('nav.pricing') }}</a>
                    <a href="{{ route('elearning.faq') }}">{{ __('nav.faq') }}</a>
                </div>
            </li>

            <li><a href="{{ url('/about') }}">{{ __('nav.about') }}</a></li>
        </ul>

        <div class="nav-actions">
            <div class="language-switcher" role="group" aria-label="{{ __('nav.language') }}">
                @foreach(['ms', 'en', 'zh'] as $locale)
                    <a href="{{ route('language.switch', $locale) }}"
                       lang="{{ $locale }}"
                       hreflang="{{ $locale }}"
                       title="{{ __('nav.language_names.' . $locale) }}"
                       @if($currentLocale === $locale) aria-current="true" @endif>{{ __('nav.languages.' . $locale) }}</a>
                @endforeach
            </div>
            <div class="account-dropdown">
                @if($isLoggedIn)
                    <button class="btn-account">
                        <i class="bi {{ $profile['picture'] }}"></i> <span class="btn-account-label">{{ $profile['username'] }} ({{ $roleLabel }})</span> <i class="bi bi-chevron-down" style="font-size: var(--text-xs);"></i>
                    </button>
                    <div class="account-dropdown-menu">
                        <div class="account-dropdown-menu-inner">
                            <a href="{{ $dashboardUrl }}"><i class="bi bi-speedometer2"></i> {{ __('nav.dashboard') }}</a>
                            <hr class="divider">
                            <a href="{{ $logoutUrl }}"><i class="bi bi-box-arrow-right"></i> {{ __('nav.logout') }}</a>
                        </div>
                    </div>
                @else
                    <button class="btn-account">
                        <i class="bi bi-person-circle"></i> <span class="btn-account-label">{{ __('nav.account') }}</span> <i class="bi bi-chevron-down" style="font-size: var(--text-xs);"></i>
                    </button>
                    <div class="account-dropdown-menu">
                        <div class="account-dropdown-menu-inner">
                            <a href="{{ url('/parent/login') }}"><i class="bi bi-person-heart"></i> {{ __('nav.parent_sign_in') }}</a>
                            <a href="{{ url('/client/login') }}"><i class="bi bi-person"></i> {{ __('nav.client_sign_in') }}</a>
                            <a href="{{ route('counselor.login') }}"><i class="bi bi-person-badge"></i> {{ __('nav.counselor_sign_in') }}</a>
                            <a href="{{ url('/admin/login') }}"><i class="bi bi-shield-lock"></i> {{ __('nav.admin_sign_in') }}</a>
                            <a href="{{ url('/superadmin/login') }}"><i class="bi bi-shield-check"></i> {{ __('nav.superadmin_sign_in') }}</a>
                            <hr class="divider">
                            <a href="{{ url('/auth/register') }}"><i class="bi bi-person-plus"></i> {{ __('nav.register') }}</a>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <button class="mobile-nav-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#mobileNav" aria-controls="mobileNav" aria-expanded="false" aria-label="{{ __('nav.toggle_navigation') }}">
            <i class="bi bi-list"></i>
        </button>
    </div>

    <div class="collapse mobile-nav-collapse" id="mobileNav">
        <div class="mobile-nav-body">
            <ul class="mobile-nav-links">
                <li><a href="/" class="mobile-nav-link">{{ __('nav.home') }}</a></li>
                <li><a href="{{ url('/counselors') }}" class="mobile-nav-link">{{ __('nav.counselors') }}</a></li>
                <li><a href="{{ url('/assessment') }}" class="mobile-nav-link">{{ __('nav.assessments') }}</a></li>
                <li>
                    <button class="mobile-nav-sub-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#mobileArticlesMenu" aria-expanded="false" aria-controls="mobileArticlesMenu">
                        <span>{{ __('nav.articles') }}</span>
                        <i class="bi bi-chevron-down sub-chevron"></i>
                    </button>
                    <div class="collapse mobile-sub-links" id="mobileArticlesMenu">
                        <a href="{{ route('public.articles') }}" class="mobile-sub-link">{{ __('nav.browse_articles') }}</a>
                        @if($canPublish)
                            <a href="{{ route('submissions.create') }}" class="mobile-sub-link">{{ __('nav.publish_article') }}</a>
                            <a href="{{ route('submissions.index') }}" class="mobile-sub-link">{{ __('nav.your_articles') }}</a>
                        @endif
                    </div>
                </li>

                <li>
                    <button class="mobile-nav-sub-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#mobileElearningMenu" aria-expanded="false" aria-controls="mobileElearningMenu">
                        <span>{{ __('nav.elearning_modules') }}</span>
                        <i class="bi bi-chevron-down sub-chevron"></i>
                    </button>
                    <div class="collapse mobile-sub-links" id="mobileElearningMenu">
                        <a href="{{ route('elearning.index') }}" class="mobile-sub-link">{{ __('nav.all_modules') }}</a>
                        <a href="{{ route('elearning.overview') }}" class="mobile-sub-link">{{ __('nav.overview') }}</a>
                        <a href="{{ route('elearning.courses') }}" class="mobile-sub-link">{{ __('nav.courses') }}</a>
                        <a href="{{ route('elearning.paths') }}" class="mobile-sub-link">{{ __('nav.learning_paths') }}</a>
                        <a href="{{ route('elearning.dashboard') }}" class="mobile-sub-link">{{ __('nav.dashboard_preview') }}</a>
                        <a href="{{ route('elearning.pricing') }}" class="mobile-sub-link">{{ __('nav.pricing') }}</a>
                        <a href="{{ route('elearning.faq') }}" class="mobile-sub-link">{{ __('nav.faq') }}</a>
                    </div>
                </li>

                <li><a href="{{ url('/about') }}" class="mobile-nav-link">{{ __('nav.about') }}</a></li>
            </ul>

            <div class="language-switcher mobile-language-switcher" role="group" aria-label="{{ __('nav.language') }}">
                @foreach(['ms', 'en', 'zh'] as $locale)
                    <a href="{{ route('language.switch', $locale) }}"
                       lang="{{ $locale }}"
                       hreflang="{{ $locale }}"
                       title="{{ __('nav.language_names.' . $locale) }}"
                       @if($currentLocale === $locale) aria-current="true" @endif>{{ __('nav.languages.' . $locale) }}</a>
                @endforeach
            </div>

            <div class="mobile-nav-account">
                @if($isLoggedIn)
                    <button class="mobile-nav-sub-toggle mobile-account-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#mobileAccountMenu" aria-expanded="false" aria-controls="mobileAccountMenu">
                        <span class="mobile-account-label">
                            <i class="bi {{ $profile['picture'] }}"></i> {{ $profile['username'] }} ({{ $roleLabel }})
                        </span>
                        <i class="bi bi-chevron-down sub-chevron"></i>
                    </button>
                    <div class="collapse mobile-sub-links" id="mobileAccountMenu">
                        <a href="{{ $dashboardUrl }}" class="mobile-sub-link"><i class="bi bi-speedometer2"></i> {{ __('nav.dashboard') }}</a>
                        <hr class="mobile-divider">
                        <a href="{{ $logoutUrl }}" class="mobile-sub-link"><i class="bi bi-box-arrow-right"></i> {{ __('nav.logout') }}</a>
                    </div>
                @else
                    <button class="mobile-nav-sub-toggle mobile-account-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#mobileAccountMenu" aria-expanded="false" aria-controls="mobileAccountMenu">
                        <span class="mobile-account-label">
                            <i class="bi bi-person-circle"></i> {{ __('nav.account') }}
                        </span>
                        <i class="bi bi-chevron-down sub-chevron"></i>
                    </button>
                    <div class="collapse mobile-sub-links" id="mobileAccountMenu">
                        <a href="{{ url('/parent/login') }}" class="mobile-sub-link"><i class="bi bi-person-heart"></i> {{ __('nav.parent_sign_in') }}</a>
                        <a href="{{ url('/client/login') }}" class="mobile-sub-link"><i class="bi bi-person"></i> {{ __('nav.client_sign_in') }}</a>
                        <a href="{{ route('counselor.login') }}" class="mobile-sub-link"><i class="bi bi-person-badge"></i> {{ __('nav.counselor_sign_in') }}</a>
                        <a href="{{ url('/admin/login') }}" class="mobile-sub-link"><i class="bi bi-shield-lock"></i> {{ __('nav.admin_sign_in') }}</a>
                        <a href="{{ url('/superadmin/login') }}" class="mobile-sub-link"><i class="bi bi-shield-check"></i> {{ __('nav.superadmin_sign_in') }}</a>
                        <hr class="mobile-divider">
                        <a href="{{ url('/auth/register') }}" class="mobile-sub-link"><i class="bi bi-person-plus"></i> {{ __('nav.register') }}</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</nav>
