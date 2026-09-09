<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ ucfirst($role) }} Dashboard - Werta</title>
    <!-- Alpine.js for interactions like modals and dropdowns -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Chart.js for charts (available if needed later) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
</head>
<body class="bg-[#F5EFE0] text-[#2C2416] font-[Lato,sans-serif] antialiased flex flex-col h-screen overflow-hidden" x-data="{ sidebarOpen: true }">

    <!-- Top Header -->
    <header class="bg-[#FDFAF4] border-b border-[#C4A840]/20 shadow-sm flex items-center justify-between px-6 py-3 z-20 shrink-0 relative">
        <div class="flex items-center gap-4">
            <button @click="sidebarOpen = !sidebarOpen" class="text-[#7B6B35] hover:bg-[#F5EFE0] p-1.5 rounded-lg transition-colors focus:outline-none">
                <i class="bi bi-list text-2xl"></i>
            </button>
            <a href="/" class="flex items-center gap-2">
                <img src="{{ asset('images/Werta_Logo.png') }}" alt="Werta Logo" class="h-8 w-auto">
                <span class="flex items-baseline gap-px">
                    <span style="font-family: 'Great Vibes', cursive; font-size: 2.2rem; color: #C4A840; line-height: 1;">W</span>
                    <span style="font-family: 'Lato', sans-serif; font-size: 1.1rem; font-weight: 700; letter-spacing: 3px; color: #7B6B35;">ERTA</span>
                </span>
                <span class="ml-2 text-xs font-bold uppercase tracking-widest text-[#7B6B35] bg-[#C4A840]/10 px-2 py-1 rounded-full">
                    {{ ucfirst($role) }} Portal
                </span>
            </a>
        </div>

        <!-- Profile Right -->
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" @click.away="open = false" class="flex items-center gap-2 focus:outline-none hover:bg-[#F5EFE0] px-3 py-1.5 rounded-lg transition-colors">
                <img src="https://ui-avatars.com/api/?name={{ ucfirst($role) }}&background=7B6B35&color=fff" alt="Profile" class="h-8 w-8 rounded-full border border-[#C4A840]/30">
                <span class="font-semibold text-sm">{{ ucfirst($role) }} User</span>
                <i class="bi bi-chevron-down text-xs text-[#7B6B35]"></i>
            </button>
            <div x-show="open" style="display: none;" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-100 py-1 z-50" x-transition>
                <a href="{{ url('/' . $role . '/settings') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-[#F5EFE0] hover:text-[#7B6B35]"><i class="bi bi-person mr-2"></i> My Profile</a>
                <div class="border-t border-gray-100 my-1"></div>
                <a href="{{ url('/' . $role . '/logout') }}" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50"><i class="bi bi-box-arrow-right mr-2"></i> Logout</a>
            </div>
        </div>
    </header>

    <!-- Main Body with Sidebar -->
    <div class="flex flex-1 overflow-hidden">
        <aside :class="sidebarOpen ? 'ml-0' : '-ml-64'" class="w-64 bg-white border-r border-[#C4A840]/20 flex-shrink-0 flex flex-col h-full z-10 shadow-[2px_0_10px_rgba(0,0,0,0.02)] transition-all duration-300 ease-in-out">
            <nav class="flex-1 overflow-y-auto py-6 px-4 space-y-2">
                <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 px-3">Main Menu</div>

                <a href="{{ url('/' . $role . '/dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition-colors {{ request()->routeIs($role . '.dashboard') ? 'bg-[#C4A840]/10 text-[#7B6B35] font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-[#7B6B35]' }}">
                    <i class="bi bi-grid-1x2-fill text-lg"></i> Overview
                </a>

                @if($role === 'superadmin')
                <a href="{{ url('/superadmin/administrators') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition-colors {{ request()->routeIs('superadmin.administrators') ? 'bg-[#C4A840]/10 text-[#7B6B35] font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-[#7B6B35]' }}">
                    <i class="bi bi-person-badge text-lg"></i> Administrators
                </a>
                @endif

                <a href="{{ url('/' . $role . '/counselor-approvals') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition-colors {{ request()->routeIs($role . '.counselor-approvals') ? 'bg-[#C4A840]/10 text-[#7B6B35] font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-[#7B6B35]' }}">
                    <i class="bi bi-patch-check text-lg"></i> Counselor Approvals
                </a>

                <a href="{{ url('/' . $role . '/user-management') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition-colors {{ request()->routeIs($role . '.user-management') ? 'bg-[#C4A840]/10 text-[#7B6B35] font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-[#7B6B35]' }}">
                    <i class="bi bi-people text-lg"></i> User Management
                </a>

                <a href="{{ url('/' . $role . '/analytics') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition-colors {{ request()->routeIs($role . '.analytics') ? 'bg-[#C4A840]/10 text-[#7B6B35] font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-[#7B6B35]' }}">
                    <i class="bi bi-bar-chart-line text-lg"></i> Analytics
                </a>

                <a href="{{ url('/' . $role . '/content') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition-colors {{ request()->routeIs($role . '.content') ? 'bg-[#C4A840]/10 text-[#7B6B35] font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-[#7B6B35]' }}">
                    <i class="bi bi-file-earmark-text text-lg"></i> Content
                </a>

                <a href="{{ url('/' . $role . '/settings') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition-colors {{ request()->routeIs($role . '.settings') ? 'bg-[#C4A840]/10 text-[#7B6B35] font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-[#7B6B35]' }}">
                    <i class="bi bi-gear text-lg"></i> Settings
                </a>
            </nav>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-y-auto bg-[#F5EFE0] p-6 lg:p-8 flex flex-col justify-between">
            <div>
                @yield('content')
            </div>

            @include('partials.dashboard-footer')
        </main>
    </div>

    @yield('scripts')
</body>
</html>