<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Dashboard - Werta</title>
    <!-- Alpine.js for interactions like modals and dropdowns -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Chart.js for charts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
</head>
<body class="bg-[#F5EFE0] text-[#2C2416] font-[Lato,sans-serif] antialiased flex flex-col h-screen overflow-hidden" x-data="{ sidebarOpen: true }">
    
    <!-- Top Header -->
    <header class="bg-[#FDFAF4] border-b border-[#C4A840]/20 shadow-sm flex items-center justify-between px-6 py-3 z-20 shrink-0 relative">
        <!-- Logo Left -->
        <div class="flex items-center gap-4">
            <button @click="sidebarOpen = !sidebarOpen" class="text-[#7B6B35] hover:bg-[#F5EFE0] p-1.5 rounded-lg transition-colors focus:outline-none">
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
            <button @click="open = !open" @click.away="open = false" class="flex items-center gap-2 focus:outline-none hover:bg-[#F5EFE0] px-3 py-1.5 rounded-lg transition-colors">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(session('client_profile.username', 'Client User')) }}&background=C4A840&color=fff" alt="Profile" class="h-8 w-8 rounded-full border border-[#C4A840]/30">
                <span class="font-semibold text-sm">{{ session('client_profile.username', 'Client User') }}</span>
                <i class="bi bi-chevron-down text-xs text-[#7B6B35]"></i>
            </button>
            <!-- Dropdown -->
            <div x-show="open" style="display: none;" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-100 py-1 z-50" x-transition>
                <button onclick="openProfileModal()" class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-[#F5EFE0] hover:text-[#7B6B35]"><i class="bi bi-person mr-2"></i> Edit Profile</button>
                <div class="border-t border-gray-100 my-1"></div>
                <a href="{{ url('/client/logout') }}" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50"><i class="bi bi-box-arrow-right mr-2"></i> Logout</a>
            </div>
        </div>
    </header>

    <!-- Main Body with Sidebar -->
    <div class="flex flex-1 overflow-hidden">
        <!-- Sidebar Navigation -->
        <aside :class="sidebarOpen ? 'ml-0' : '-ml-64'" class="w-64 bg-white border-r border-[#C4A840]/20 flex-shrink-0 flex flex-col h-full z-10 shadow-[2px_0_10px_rgba(0,0,0,0.02)] transition-all duration-300 ease-in-out">
            <nav class="flex-1 overflow-y-auto py-6 px-4 space-y-2">
                <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 px-3">Main Menu</div>
                
                <a href="{{ url('/client/dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition-colors {{ request()->routeIs('client.dashboard') ? 'bg-[#C4A840]/10 text-[#7B6B35] font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-[#7B6B35]' }}">
                    <i class="bi bi-grid-1x2-fill text-lg"></i> Overview
                </a>
                
                <a href="{{ url('/client/assessments') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition-colors {{ request()->routeIs('client.assessments') ? 'bg-[#C4A840]/10 text-[#7B6B35] font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-[#7B6B35]' }}">
                    <i class="bi bi-clipboard-check text-lg"></i> My Assessments
                </a>
                
                <a href="{{ url('/client/learning') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition-colors {{ request()->routeIs('client.learning') ? 'bg-[#C4A840]/10 text-[#7B6B35] font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-[#7B6B35]' }}">
                    <i class="bi bi-book text-lg"></i> E-Learning Modules
                </a>
                
                <a href="{{ url('/client/appointments') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition-colors {{ request()->routeIs('client.appointments') ? 'bg-[#C4A840]/10 text-[#7B6B35] font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-[#7B6B35]' }}">
                    <i class="bi bi-calendar-event text-lg"></i> Appointments
                </a>
                
                <a href="{{ url('/client/messages') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition-colors {{ request()->routeIs('client.messages') ? 'bg-[#C4A840]/10 text-[#7B6B35] font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-[#7B6B35]' }}">
                    <i class="bi bi-chat-dots text-lg"></i> Messages
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

    <!-- Edit Profile Modal -->
    <div id="profileModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(44,36,22,0.6); z-index:9999; align-items:center; justify-content:center; backdrop-filter:blur(4px);">
        <div style="background:#fff; width:90%; max-width:400px; border-radius:12px; padding:2rem; position:relative; box-shadow:0 10px 30px rgba(0,0,0,0.1);">
            <button onclick="closeProfileModal()" style="position:absolute; top:15px; right:15px; background:none; border:none; font-size:1.2rem; cursor:pointer; color:#6c757d;"><i class="bi bi-x-lg"></i></button>
            
            <h3 style="font-size:1.25rem; font-weight:700; color:#2c2416; margin-bottom:1.5rem; font-family:'IM Fell English',serif;">Edit Profile</h3>
            
            <form action="/client/profile" method="POST">
                @csrf
                <div style="margin-bottom:1.2rem;">
                    <label style="display:block; font-weight:600; font-size:0.9rem; margin-bottom:0.5rem; color:#2c2416;">Username</label>
                    <input type="text" name="username" value="{{ session('client_profile.username', 'Client User') }}" required style="width:100%; padding:0.75rem; border:1px solid #ddd; border-radius:6px;">
                </div>
                
                <div style="margin-bottom:1.5rem;">
                    <label style="display:block; font-weight:600; font-size:0.9rem; margin-bottom:0.5rem; color:#2c2416;">Profile Icon</label>
                    <select name="picture" style="width:100%; padding:0.75rem; border:1px solid #ddd; border-radius:6px; background:#fff;">
                        <option value="bi-person-circle" {{ session('client_profile.picture') === 'bi-person-circle' ? 'selected' : '' }}>Circle Person</option>
                        <option value="bi-person-square" {{ session('client_profile.picture') === 'bi-person-square' ? 'selected' : '' }}>Square Person</option>
                        <option value="bi-emoji-smile" {{ session('client_profile.picture') === 'bi-emoji-smile' ? 'selected' : '' }}>Smiley</option>
                        <option value="bi-emoji-sunglasses" {{ session('client_profile.picture') === 'bi-emoji-sunglasses' ? 'selected' : '' }}>Sunglasses</option>
                    </select>
                </div>
                
                <button type="submit" style="width:100%; padding:0.75rem; background:#c4a840; color:#fff; border:none; border-radius:6px; font-weight:600; cursor:pointer;">Save Changes</button>
            </form>
        </div>
    </div>

    <script>
        function openProfileModal() { document.getElementById('profileModal').style.display = 'flex'; }
        function closeProfileModal() { document.getElementById('profileModal').style.display = 'none'; }
    </script>

    @yield('scripts')
</body>
</html>
