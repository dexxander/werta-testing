<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Dashboard - Werta</title>
    <!-- Alpine.js for interactions like modals and dropdowns -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Chart.js for charts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Tailwind CSS via CDN (standalone, no Vite required) -->
    <script src="https://cdn.tailwindcss.com"></script>
    @include('partials.tailwind-dashboard-config')
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
</head>
<body class="bg-cream text-dark font-[Lato,sans-serif] antialiased flex flex-col h-screen overflow-hidden" x-data="{ sidebarOpen: true }">
    {{-- DEV BYPASS: Warning banner for dev-bypassed sessions --}}
    @include('partials.dev-bypass-banner')
    
    <!-- Top Header -->
    <header class="bg-cream-light border-b border-gold/20 shadow-sm flex items-center justify-between px-6 py-3 z-20 shrink-0 relative">
        <!-- Logo Left — links back to home -->
        <div class="flex items-center gap-4">
            <button @click="sidebarOpen = !sidebarOpen" class="text-primary hover:bg-cream p-1.5 rounded-lg transition-colors focus:outline-none">
                <i class="bi bi-list text-2xl"></i>
            </button>
            <a href="/" class="flex items-center gap-2 no-underline">
                <img src="{{ asset('images/Werta_Logo.png') }}" alt="Werta Logo" class="h-8 w-auto">
                <span class="flex items-baseline gap-px">
                    <span style="font-family: 'Great Vibes', cursive; font-size: 2.2rem; color: #C4A840; line-height: 1;">W</span>
                    <span style="font-family: 'Lato', sans-serif; font-size: 1.1rem; font-weight: 700; letter-spacing: 3px; color: #7B6B35;">ERTA</span>
                </span>
            </a>
        </div>
        
        <!-- Profile Right -->
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" @click.away="open = false" class="flex items-center gap-2 focus:outline-none hover:bg-cream px-3 py-1.5 rounded-lg transition-colors">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(session('parent_profile.username', 'Parent User')) }}&background=C4A840&color=fff" alt="Profile" class="h-8 w-8 rounded-full border border-gold/30">
                <span class="font-semibold text-sm">{{ session('parent_profile.username', 'Parent User') }}</span>
                <i class="bi bi-chevron-down text-xs text-primary"></i>
            </button>
            <!-- Dropdown -->
            <div x-show="open" style="display: none;" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-100 py-1 z-50" x-transition>
                <button onclick="openParentProfileModal()" class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-cream hover:text-primary"><i class="bi bi-person mr-2"></i> Edit Profile</button>
                <div class="border-t border-gray-100 my-1"></div>
                <a href="{{ url('/parent/logout') }}" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50"><i class="bi bi-box-arrow-right mr-2"></i> Logout</a>
            </div>
        </div>
    </header>

    <!-- Main Body with Sidebar -->
    <div class="flex flex-1 overflow-hidden">
        <!-- Sidebar Navigation -->
        <aside :class="sidebarOpen ? 'ml-0' : '-ml-64'" class="w-64 bg-white border-r border-gold/20 flex-shrink-0 flex flex-col h-full z-10 shadow-sidebar transition-all duration-300 ease-in-out">
            <nav class="flex-1 overflow-y-auto py-6 px-4 space-y-2">
                <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 px-3">Main Menu</div>
                
                <a href="{{ url('/parent/dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition-colors {{ request()->routeIs('parent.dashboard') ? 'bg-gold/10 text-primary font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-primary' }}">
                    <i class="bi bi-grid-1x2-fill text-lg"></i> Overview
                </a>
                
                <button @click="$dispatch('open-add-child')" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-600 hover:bg-gray-50 hover:text-primary font-medium transition-colors">
                    <i class="bi bi-person-plus text-lg"></i> Add Child Account
                </button>
                
                <a href="{{ url('/parent/progress') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition-colors {{ request()->routeIs('parent.progress') ? 'bg-gold/10 text-primary font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-primary' }}">
                    <i class="bi bi-bar-chart-line text-lg"></i> Monitor Progress
                </a>
                
                <a href="{{ url('/parent/appointments') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition-colors {{ request()->routeIs('parent.appointments') ? 'bg-gold/10 text-primary font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-primary' }}">
                    <i class="bi bi-calendar-event text-lg"></i> Appointments
                </a>
                
                <a href="{{ url('/parent/messages') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition-colors {{ request()->routeIs('parent.messages') ? 'bg-gold/10 text-primary font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-primary' }}">
                    <i class="bi bi-chat-dots text-lg"></i> Messages
                </a>
            </nav>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-y-auto bg-cream p-6 lg:p-8 flex flex-col justify-between">
            <div>
                @yield('content')
            </div>
            
            @include('partials.dashboard-footer')
        </main>
    </div>

    <!-- Parent Edit Profile Modal -->
    <div id="parentProfileModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(44,36,22,0.6); z-index:9999; align-items:center; justify-content:center; backdrop-filter:blur(4px);">
        <div style="background:#fff; width:90%; max-width:400px; border-radius:12px; padding:2rem; position:relative; box-shadow:0 10px 30px rgba(0,0,0,0.1);">
            <button onclick="closeParentProfileModal()" style="position:absolute; top:15px; right:15px; background:none; border:none; font-size:1.2rem; cursor:pointer; color:#6c757d;"><i class="bi bi-x-lg"></i></button>
            
            <h3 style="font-size:1.25rem; font-weight:700; color:#2c2416; margin-bottom:1.5rem; font-family:'IM Fell English',serif;">Edit Profile</h3>
            
            <form action="/parent/profile" method="POST">
                @csrf
                <div style="margin-bottom:1.2rem;">
                    <label style="display:block; font-weight:600; font-size:0.9rem; margin-bottom:0.5rem; color:#2c2416;">Username</label>
                    <input type="text" name="username" value="{{ session('parent_profile.username', 'Parent User') }}" required style="width:100%; padding:0.75rem; border:1px solid #ddd; border-radius:6px;">
                </div>
                
                <div style="margin-bottom:1.5rem;">
                    <label style="display:block; font-weight:600; font-size:0.9rem; margin-bottom:0.5rem; color:#2c2416;">Profile Icon</label>
                    <select name="picture" style="width:100%; padding:0.75rem; border:1px solid #ddd; border-radius:6px; background:#fff;">
                        <option value="bi-person-heart" {{ session('parent_profile.picture') === 'bi-person-heart' ? 'selected' : '' }}>Heart Person</option>
                        <option value="bi-person-circle" {{ session('parent_profile.picture') === 'bi-person-circle' ? 'selected' : '' }}>Circle Person</option>
                        <option value="bi-emoji-smile" {{ session('parent_profile.picture') === 'bi-emoji-smile' ? 'selected' : '' }}>Smiley</option>
                        <option value="bi-emoji-sunglasses" {{ session('parent_profile.picture') === 'bi-emoji-sunglasses' ? 'selected' : '' }}>Sunglasses</option>
                    </select>
                </div>
                
                <button type="submit" style="width:100%; padding:0.75rem; background:#c4a840; color:#fff; border:none; border-radius:6px; font-weight:600; cursor:pointer;">Save Changes</button>
            </form>
        </div>
    </div>

    <script>
        function openParentProfileModal() { document.getElementById('parentProfileModal').style.display = 'flex'; }
        function closeParentProfileModal() { document.getElementById('parentProfileModal').style.display = 'none'; }
    </script>
    
    @yield('scripts')
</body>
</html>
