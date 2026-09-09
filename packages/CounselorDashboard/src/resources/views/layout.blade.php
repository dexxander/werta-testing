<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Counselor Portal - Werta</title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    @include('partials.tailwind-dashboard-config')
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
</head>
<body class="bg-cream text-dark font-[Lato,sans-serif] antialiased flex flex-col h-screen overflow-hidden" x-data="{ isModalOpen: false }" x-data="{ sidebarOpen: true }">
    {{-- DEV BYPASS: Warning banner for dev-bypassed sessions --}}
    @include('partials.dev-bypass-banner')
    
    <header class="bg-cream-light border-b border-gold/20 shadow-sm flex items-center justify-between px-6 py-3 z-20 shrink-0 relative" @open-session-report.window="isModalOpen = true">
        <div class="flex items-center gap-4">
            <button @click="sidebarOpen = !sidebarOpen" class="text-primary hover:bg-cream p-1.5 rounded-lg transition-colors focus:outline-none">
                <i class="bi bi-list text-2xl"></i>
            </button>
            <a href="/" class="flex items-center gap-2 no-underline">
                <img src="{{ asset('images/Werta_Logo.png') }}" alt="Werta Logo" class="h-8 w-auto">
                <span class="flex items-baseline gap-px">
                    <span style="font-family: 'Great Vibes', cursive; font-size: 2.2rem; color: #C4A840; line-height: 1;">W</span>
                    <span style="font-family: 'Lato', sans-serif; font-size: 1.1rem; font-weight: 700; letter-spacing: 3px; color: #7B6B35;">ERTA</span>
                    <span class="ml-1 text-xs font-bold text-gray-400 tracking-widest uppercase">Counselor</span>
                </span>
            </a>
        </div>
        
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" @click.away="open = false" class="flex items-center gap-2 focus:outline-none hover:bg-cream px-3 py-1.5 rounded-lg transition-colors">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(session('counselor_name', 'Counselor')) }}&background=C4A840&color=fff" alt="Profile" class="h-8 w-8 rounded-full border border-gold/30">
                <span class="font-semibold text-sm">{{ session('counselor_name', 'Counselor') }}</span>
                <i class="bi bi-chevron-down text-xs text-primary"></i>
            </button>
            <div x-show="open" style="display: none;" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-100 py-1 z-50" x-transition>
                <a href="{{ url('') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-cream hover:text-primary"><i class="bi"></i> Back to main page</a>
                <div class="border-t border-gray-100 my-1"></div>
                <a href="{{ url('/counselor/logout') }}" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50"><i class="bi bi-box-arrow-right mr-2"></i> Logout</a>
            </div>
        </div>
    </header>

    <div class="flex flex-1 overflow-hidden">
        <aside :class="sidebarOpen ? 'ml-0' : '-ml-64'" class="w-64 bg-white border-r border-gold/20 flex-shrink-0 flex flex-col h-full z-10 shadow-sidebar transition-all duration-300 ease-in-out">
            <nav class="flex-1 overflow-y-auto py-6 px-4 space-y-2">
                <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 px-3">Workspace</div>
                
                <a href="{{ url('/counselor/dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition-colors {{ request()->routeIs('counselor.dashboard') ? 'bg-gold/10 text-primary font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-primary' }}">
                    <i class="bi bi-grid-1x2-fill text-lg"></i> Overview
                </a>
                
                <a href="{{ url('/counselor/schedule') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition-colors {{ request()->routeIs('counselor.schedule') ? 'bg-gold/10 text-primary font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-primary' }}">
                    <i class="bi bi-calendar-week text-lg"></i> Schedule & Slots
                </a>
                
                <a href="{{ url('/counselor/clients') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition-colors {{ request()->routeIs('counselor.clients') ? 'bg-gold/10 text-primary font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-primary' }}">
                    <i class="bi bi-people-fill text-lg"></i> Client History
                </a>

                <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mt-6 mb-4 px-3">Publishing</div>
                
                <a href="{{ url('/counselor/articles') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition-colors {{ request()->routeIs('counselor.articles.*') ? 'bg-gold/10 text-primary font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-primary' }}">
                    <i class="bi bi-journal-richtext text-lg"></i> Articles
                </a>

                <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mt-6 mb-4 px-3">Account</div>

                <a href="{{ url('/counselor/profile') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition-colors {{ request()->routeIs('counselor.profile') ? 'bg-gold/10 text-primary font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-primary' }}">
                    <i class="bi bi-gear-fill text-lg"></i> Profile & Rates
                </a>

                <button @click="$dispatch('open-session-report')" class="w-full mt-4 flex items-center justify-center gap-2 px-3 py-2.5 rounded-lg bg-gray-50 text-gray-600 border border-gray-200 hover:bg-cream hover:text-primary hover:border-gold/30 font-medium transition-all">
                    <i class="bi bi-pencil-square text-lg"></i> Quick Report
                </button>
            </nav>
        </aside>

        <div x-show="isModalOpen" style="display: none;" class="relative z-[9999]" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div x-show="isModalOpen" x-transition.opacity class="fixed inset-0 bg-black bg-opacity-40 transition-opacity"></div>

            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    
                    <div x-show="isModalOpen" 
                         x-transition:enter="ease-out duration-300" 
                         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                         x-transition:leave="ease-in duration-200" 
                         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                         @click.away="isModalOpen = false"
                         class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-xl border border-gold/30">
                        
                        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-cream sm:mx-0 sm:h-10 sm:w-10">
                                    <i class="bi bi-file-earmark-text text-primary text-xl"></i>
                                </div>
                                <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                    <h3 class="text-lg font-bold leading-6 text-dark" id="modal-title">Submit Session Report</h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500 mb-4">File your clinical notes for a completed session. These notes are private and securely encrypted.</p>
                                        
                                        <form class="space-y-4 text-left">
                                             
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                <div>
                                                    <label class="block text-sm font-semibold text-gray-700">Client / Session</label>
                                                    <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gold focus:ring focus:ring-gold/20 p-2 border bg-white text-sm">
                                                        <option>Student #84201 - Today, 10:00 AM</option>
                                                        <option>Group Therapy A - Today, 2:30 PM</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-semibold text-gray-700">Primary Focus</label>
                                                    <input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gold focus:ring focus:ring-gold/20 p-2 border text-sm" placeholder="e.g. Academic Anxiety">
                                                </div>
                                            </div>

                                            <div>
                                                <label class="block text-sm font-semibold text-gray-700">Clinical Notes & Observations</label>
                                                <textarea rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gold focus:ring focus:ring-gold/20 p-2 border text-sm" placeholder="Document client's emotional state, general topics discussed, and progress..."></textarea>
                                            </div>

                                            <div>
                                                <label class="block text-sm font-semibold text-gray-700">Interventions Used</label>
                                                <input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gold focus:ring focus:ring-gold/20 p-2 border text-sm" placeholder="e.g., Cognitive Restructuring, 4-7-8 Breathing...">
                                            </div>

                                            <div>
                                                <label class="block text-sm font-semibold text-gray-700">Next Steps / Homework</label>
                                                <textarea rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gold focus:ring focus:ring-gold/20 p-2 border text-sm" placeholder="List any actionable tasks or habits for the client to practice..."></textarea>
                                            </div>

                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t border-gray-100">
                            <button type="button" @click="isModalOpen = false" class="inline-flex w-full justify-center rounded-md bg-gold px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary sm:ml-3 sm:w-auto transition-colors">Save Report</button>
                            <button type="button" @click="isModalOpen = false" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition-colors">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <main class="flex-1 overflow-y-auto bg-cream p-6 lg:p-8 flex flex-col justify-between">
            <div>
                @yield('content')
            </div>
            
            @include('partials.dashboard-footer')
        </main>
    </div>
    
    @yield('scripts')
</body>
</html>