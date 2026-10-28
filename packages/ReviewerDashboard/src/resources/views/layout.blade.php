<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    @include('partials.localization')
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', __('Reviewer Dashboard')) — Werta</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/Werta_Logo.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/Werta_Logo.png') }}">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    @include('partials.tailwind-dashboard-config')
    <link href="https://fonts.googleapis.com/css2?family=IM+Fell+English:ital@0;1&family=Great+Vibes&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        .font-display { font-family: 'IM Fell English', serif; font-weight: 400; }
        .card-ring { box-shadow: 0 0 0 1px rgba(87, 75, 34, 0.35), 0 8px 32px rgba(44, 36, 22, 0.07); }
    </style>
</head>
<body class="bg-cream text-dark font-[Lato,sans-serif] antialiased flex flex-col h-screen overflow-hidden" x-data="{ sidebarOpen: window.innerWidth >= 1024 }">
    @include('partials.dev-bypass-banner')

    <header class="bg-cream-light border-b border-gold/20 shadow-sm flex items-center justify-between px-4 sm:px-6 py-3 z-20 shrink-0 relative">
        <div class="flex items-center gap-4">
            <button @click="sidebarOpen = !sidebarOpen" aria-label="{{ __('Toggle navigation') }}" :aria-expanded="sidebarOpen.toString()" class="text-primary hover:bg-cream p-1.5 rounded-lg transition-colors focus:outline-none">
                <i class="bi bi-list text-2xl"></i>
            </button>
            <a href="/" class="flex items-center gap-2">
                <img src="{{ asset('images/Werta_Logo.png') }}" alt="{{ __('Werta Logo') }}" class="h-8 w-auto">
                <span class="flex items-baseline gap-px">
                    <span style="font-family: 'Great Vibes', cursive; font-size: 2.2rem; color: #C4A840; line-height: 1;">W</span>
                    <span style="font-family: 'Lato', sans-serif; font-size: 1.1rem; font-weight: 700; letter-spacing: 3px; color: #7B6B35;">ERTA</span>
                </span>
                <span class="ml-2 text-xs font-bold uppercase tracking-widest text-primary bg-gold/10 px-2 py-1 rounded-full hidden sm:inline">{{ __('Reviewer Portal') }}</span>
            </a>
        </div>

        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" @click.away="open = false" class="flex items-center gap-2 focus:outline-none hover:bg-cream px-3 py-1.5 rounded-lg transition-colors">
                <img src="{{ $reviewer->avatar_url }}" alt="{{ __('Profile') }}" class="h-8 w-8 rounded-full border border-gold/30">
                <span class="font-semibold text-sm hidden sm:inline">{{ $reviewer->name }}</span>
                <i class="bi bi-chevron-down text-xs text-primary"></i>
            </button>
            <div x-show="open" style="display: none;" class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg ring-1 ring-primary-dark/35 py-1 z-50" x-transition>
                @if($reviewer->show_publicly)
                    <a href="{{ route('public.reviewers.show', $reviewer->slug) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-cream hover:text-primary"><i class="bi bi-person-vcard mr-2"></i> {{ __('My public profile') }}</a>
                @endif
                @if(\App\Support\DemoAuth::enabled())
                    <a href="{{ route('demo.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-cream hover:text-primary"><i class="bi bi-arrow-left-right mr-2"></i> {{ __('nav.demo_switch') }}</a>
                @endif
                <div class="border-t border-gray-100 my-1"></div>
                <a href="{{ route('reviewer.logout') }}" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50"><i class="bi bi-box-arrow-right mr-2"></i> {{ __('Logout') }}</a>
            </div>
        </div>
        @include('partials.language-picker')
    </header>

    <div class="relative flex flex-1 overflow-hidden">
        <aside :class="sidebarOpen ? '!translate-x-0' : 'lg:-ml-64'" class="absolute inset-y-0 left-0 z-30 -translate-x-full lg:relative lg:z-10 lg:translate-x-0 w-64 bg-white border-r border-gold/20 flex-shrink-0 flex flex-col h-full shadow-sidebar transition-all duration-300 ease-in-out">
            <nav class="flex-1 overflow-y-auto py-6 px-4 space-y-2">
                <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 px-3">{{ __('Main Menu') }}</div>
                @foreach([
                    ['reviewer.dashboard', 'bi-inbox', __('Review queue')],
                    ['reviewer.completed', 'bi-journal-check', __('Completed reviews')],
                    ['reviewer.guidelines', 'bi-clipboard-check', __('Evaluation guidelines')],
                ] as [$route, $icon, $label])
                    <a href="{{ route($route) }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition-colors {{ request()->routeIs($route) || ($route === 'reviewer.dashboard' && request()->routeIs('reviewer.reviews.*')) ? 'bg-gold/10 text-primary font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-primary' }}">
                        <i class="bi {{ $icon }} text-lg"></i> {{ $label }}
                    </a>
                @endforeach
                <a href="{{ route('public.articles') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition-colors text-gray-600 hover:bg-gray-50 hover:text-primary">
                    <i class="bi bi-journal-text text-lg"></i> {{ __('Browse articles') }}
                </a>

                <div class="mt-6 mx-1 p-4 rounded-xl bg-cream text-xs text-primary-dark leading-relaxed">
                    <div class="font-bold uppercase tracking-wider mb-1"><i class="bi bi-shield-check"></i> {{ __('Verified reviewer') }}</div>
                    {{ $reviewer->title }}@if($reviewer->field_label) · {{ __($reviewer->field_label) }}@endif
                </div>
            </nav>
        </aside>

        <div x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen = false" style="display: none;" class="absolute inset-0 z-20 bg-dark/40 lg:hidden"></div>

        <main class="flex-1 overflow-y-auto bg-cream p-6 lg:p-8 flex flex-col justify-between">
            <div>
                @if(session('mock_ok'))
                    <div class="mb-5 p-3 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm font-medium"><i class="bi bi-check-circle mr-1"></i> {{ __(session('mock_ok')) }}</div>
                @endif
                @if(session('mock_error'))
                    <div class="mb-5 p-3 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm font-medium"><i class="bi bi-exclamation-circle mr-1"></i> {{ __(session('mock_error')) }}</div>
                @endif
                @if($errors->any())
                    <div class="mb-5 p-3 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm font-medium"><i class="bi bi-exclamation-circle mr-1"></i> {{ \App\Support\SiteText::translate($errors->first()) }}</div>
                @endif
                @yield('content')
            </div>

            @include('partials.dashboard-footer')
        </main>
    </div>

    @yield('scripts')
</body>
</html>
