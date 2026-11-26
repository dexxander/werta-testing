{{-- eLearning Tab Navigation --}}
<div class="el-tab-bar-wrap">
    <ul class="nav el-tab-bar" id="elearningTab">
        <li class="nav-item">
            <a href="{{ route('elearning.overview') }}"
               class="nav-link {{ request()->routeIs('elearning.overview') ? 'active' : '' }}" @if(request()->routeIs('elearning.overview')) aria-current="page" @endif>
                {{ __('Overview') }}
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('elearning.courses') }}"
               class="nav-link {{ request()->routeIs('elearning.courses') ? 'active' : '' }}" @if(request()->routeIs('elearning.courses')) aria-current="page" @endif>
                {{ __('Courses') }}
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('elearning.paths') }}"
               class="nav-link {{ request()->routeIs('elearning.paths') ? 'active' : '' }}" @if(request()->routeIs('elearning.paths')) aria-current="page" @endif>
                {{ __('Learning Paths') }}
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('elearning.dashboard') }}"
               class="nav-link {{ request()->routeIs('elearning.dashboard') ? 'active' : '' }}" @if(request()->routeIs('elearning.dashboard')) aria-current="page" @endif>
                {{ __('Dashboard') }}
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('elearning.pricing') }}"
               class="nav-link {{ request()->routeIs('elearning.pricing') ? 'active' : '' }}" @if(request()->routeIs('elearning.pricing')) aria-current="page" @endif>
                {{ __('Pricing') }}
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('elearning.faq') }}"
               class="nav-link {{ request()->routeIs('elearning.faq') ? 'active' : '' }}" @if(request()->routeIs('elearning.faq')) aria-current="page" @endif>
                {{ __('FAQ') }}
            </a>
        </li>
    </ul>
</div>
