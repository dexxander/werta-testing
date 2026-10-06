{{-- eLearning Tab Navigation --}}
<div class="el-tab-bar-wrap">
    <ul class="nav el-tab-bar" id="elearningTab">
        <li class="nav-item">
            <a href="{{ route('elearning.overview') }}"
               class="nav-link {{ request()->routeIs('elearning.overview') ? 'active' : '' }}">
                {{ __('Overview') }}
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('elearning.courses') }}"
               class="nav-link {{ request()->routeIs('elearning.courses') ? 'active' : '' }}">
                {{ __('Courses') }}
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('elearning.paths') }}"
               class="nav-link {{ request()->routeIs('elearning.paths') ? 'active' : '' }}">
                {{ __('Learning Paths') }}
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('elearning.dashboard') }}"
               class="nav-link {{ request()->routeIs('elearning.dashboard') ? 'active' : '' }}">
                {{ __('Dashboard Preview') }}
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('elearning.pricing') }}"
               class="nav-link {{ request()->routeIs('elearning.pricing') ? 'active' : '' }}">
                {{ __('Pricing') }}
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('elearning.faq') }}"
               class="nav-link {{ request()->routeIs('elearning.faq') ? 'active' : '' }}">
                {{ __('FAQ') }}
            </a>
        </li>
    </ul>
</div>