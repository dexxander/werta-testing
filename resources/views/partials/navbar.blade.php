<style>
    /* Dropdown styling for E-Learning Nav Link */
    .elearning-dropdown {
        position: relative;
    }
    .elearning-dropdown-menu {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        background-color: var(--cream-light, #ffffff);
        border: 1px solid rgba(123,107,53,0.15);
        box-shadow: 0 12px 32px rgba(44,36,22,0.1);
        border-radius: 8px;
        min-width: 220px;
        padding: 0.5rem 0;
        z-index: 1000;
    }
    .elearning-dropdown:hover .elearning-dropdown-menu {
        display: block;
    }
    .elearning-dropdown-menu a {
        display: block !important;
        padding: 0.6rem 1.5rem !important;
        color: var(--dark, #333) !important;
        font-size: 0.9rem !important;
        text-decoration: none !important;
        text-transform: none !important;
        transition: background 0.2s, color 0.2s !important;
    }
    .elearning-dropdown-menu a:hover {
        background-color: rgba(196,168,64,0.1) !important;
        color: var(--gold, #c4a840) !important;
    }
</style>

<nav class="site-navbar">
    <div class="navbar-inner">
        <a href="/" class="navbar-brand-wrap">
            <img src="{{ asset('images/Werta_Logo.png') }}" alt="Werta Logo" style="height: 90px; width: auto;">
            <span class="brand-title">
                <span class="brand-w">W</span><span class="brand-rest">ERTA</span>
            </span>
        </a>

        <ul class="nav-links">
            <li><a href="#">Home</a></li>
            <li><a href="{{ url('/counselors') }}">Counselors</a></li>
            <li><a href="{{ url('/assessment') }}">Assessments</a></li>
            <li><a href="{{ route('public.articles') }}">Articles</a></li>

            {{-- Replaced E-Learning Link with Hover Dropdown --}}
            <li class="elearning-dropdown">
                <a href="{{ route('elearning.index') }}">
                    E-Learning Modules <i class="bi bi-chevron-down" style="font-size:0.7rem; margin-left:4px;"></i>
                </a>
                <div class="elearning-dropdown-menu">
                    <a href="{{ route('elearning.overview') }}">Overview</a>
                    <a href="{{ route('elearning.courses') }}">Courses</a>
                    <a href="{{ route('elearning.paths') }}">Learning Paths</a>
                    <a href="{{ route('elearning.dashboard') }}">Dashboard Preview</a>
                    <a href="{{ route('elearning.pricing') }}">Pricing</a>
                    <a href="{{ route('elearning.faq') }}">FAQ</a>
                </div>
            </li>

            <li><a href="{{ url('/about') }}">About</a></li>
        </ul>

        <div class="nav-actions">
            <div class="account-dropdown">
                @if(session('staff_role') || session('counselor_logged_in') || session('client_logged_in') || session('parent_logged_in'))
                    @php
                        $staffRole = session('staff_role');
                        $role = $staffRole
                            ? ucfirst($staffRole)
                            : (session('counselor_logged_in') ? 'Counselor' : (session('client_logged_in') ? 'Client' : 'Parent'));
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
                    @endphp
                    <button class="btn-account">
                        <i class="bi {{ $profile['picture'] }}"></i> {{ $profile['username'] }} ({{ $role }}) <i class="bi bi-chevron-down" style="font-size:0.7rem;"></i>
                    </button>
                    <div class="account-dropdown-menu">
                        <div class="account-dropdown-menu-inner">
                            <a href="{{ $dashboardUrl }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
                            <hr class="divider">
                            <a href="{{ $logoutUrl }}"><i class="bi bi-box-arrow-right"></i> Logout</a>
                        </div>
                    </div>
                @else
                    <button class="btn-account">
                        <i class="bi bi-person-circle"></i> Account <i class="bi bi-chevron-down" style="font-size:0.7rem;"></i>
                    </button>
                    <div class="account-dropdown-menu">
                        <div class="account-dropdown-menu-inner">
                            <a href="{{ url('/parent/login') }}"><i class="bi bi-person-heart"></i> Parent Sign In</a>
                            <a href="{{ url('/client/login') }}"><i class="bi bi-person"></i> Client Sign In</a>
                            <a href="{{ route('counselor.login') }}"><i class="bi bi-person-badge"></i> Counselor Sign In</a>
                            <a href="{{ url('/admin/login') }}"><i class="bi bi-shield-lock"></i> Admin Sign In</a>
                            <a href="{{ url('/superadmin/login') }}"><i class="bi bi-shield-check"></i> Superadmin Sign In</a>
                            <hr class="divider">
                            <a href="{{ url('/auth/register') }}"><i class="bi bi-person-plus"></i> Register</a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</nav>
