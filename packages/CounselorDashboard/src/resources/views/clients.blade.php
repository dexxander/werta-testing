@extends('counselor-dashboard::layout')

@section('content')
<div x-data="{ 
    viewReportOpen: false,
    selectedClient: null,
    openReport(client) {
        this.selectedClient = client;
        this.viewReportOpen = true;
    }
}">
    <div class="mb-6 sm:mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-[#2C2416]">Client History</h1>
        <p class="text-sm text-gray-500 mt-1">Review past sessions, access clinical notes, and track client progress.</p>
    </div>

    <div class="bg-white rounded-2xl p-4 border border-[#C4A840]/20 shadow-sm mb-6 flex flex-col md:flex-row gap-4 justify-between items-center relative z-20">
        
        <div class="relative w-full md:w-96">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400"><i class="bi bi-search"></i></span>
            <input type="text" class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-200 focus:border-[#C4A840] focus:ring-2 focus:ring-[#C4A840]/20 outline-none text-sm transition-colors" placeholder="Search by Client ID or Group Name...">
        </div>

        <div class="w-full md:w-auto flex gap-3">
            
            <div class="relative" x-data="{ sortOpen: false, selectedSort: 'Default' }" @click.outside="sortOpen = false">
                <button @click="sortOpen = !sortOpen" class="w-full md:w-44 flex justify-between items-center border border-gray-200 rounded-lg px-4 py-2.5 text-sm outline-none hover:border-[#C4A840] bg-white transition-colors">
                    <span x-text="selectedSort" class="font-medium text-gray-700"></span>
                    <i class="bi bi-chevron-down text-xs text-gray-400"></i>
                </button>
                <div x-show="sortOpen" style="display: none;" class="absolute right-0 mt-2 w-48 bg-white border border-gray-100 rounded-lg shadow-lg z-50 py-1 overflow-hidden" x-transition>
                    <button @click="selectedSort = 'Default'; sortOpen = false" :class="selectedSort === 'Default' ? 'bg-[#F5EFE0] text-[#7B6B35] font-semibold' : 'text-gray-700 hover:bg-gray-50'" class="w-full text-left px-4 py-2 text-sm transition-colors">Default</button>
                    <button @click="selectedSort = 'Latest'; sortOpen = false" :class="selectedSort === 'Latest' ? 'bg-[#F5EFE0] text-[#7B6B35] font-semibold' : 'text-gray-700 hover:bg-gray-50'" class="w-full text-left px-4 py-2 text-sm transition-colors">Latest</button>
                    <button @click="selectedSort = 'Oldest'; sortOpen = false" :class="selectedSort === 'Oldest' ? 'bg-[#F5EFE0] text-[#7B6B35] font-semibold' : 'text-gray-700 hover:bg-gray-50'" class="w-full text-left px-4 py-2 text-sm transition-colors">Oldest</button>
                    <button @click="selectedSort = 'Name: A - Z'; sortOpen = false" :class="selectedSort === 'Name: A - Z' ? 'bg-[#F5EFE0] text-[#7B6B35] font-semibold' : 'text-gray-700 hover:bg-gray-50'" class="w-full text-left px-4 py-2 text-sm transition-colors">Name: A - Z</button>
                    <button @click="selectedSort = 'Name: Z - A'; sortOpen = false" :class="selectedSort === 'Name: Z - A' ? 'bg-[#F5EFE0] text-[#7B6B35] font-semibold' : 'text-gray-700 hover:bg-gray-50'" class="w-full text-left px-4 py-2 text-sm transition-colors">Name: Z - A</button>
                </div>
            </div>

            <div class="relative" x-data="{ filterOpen: false, dateFilterActive: false }" @click.outside="filterOpen = false">
                <button @click="filterOpen = !filterOpen" class="bg-gray-50 border border-gray-200 text-gray-600 hover:bg-[#F5EFE0] hover:text-[#7B6B35] hover:border-[#C4A840]/30 px-4 py-2.5 rounded-lg text-sm font-semibold transition-colors flex items-center gap-2">
                    <i class="bi bi-funnel"></i> Filter
                </button>
                <div x-show="filterOpen" style="display: none;" class="absolute right-0 mt-2 w-72 bg-white border border-gray-100 rounded-lg shadow-xl z-50 p-4" x-transition>
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Client Status</h3>
                    <label class="flex items-center gap-3 mb-2 cursor-pointer group">
                        <input type="checkbox" checked class="w-4 h-4 text-[#C4A840] border-gray-300 rounded focus:ring-[#C4A840] group-hover:border-[#C4A840] transition-colors">
                        <span class="text-sm text-gray-700 font-medium">Active Clients</span>
                    </label>
                    <label class="flex items-center gap-3 mb-4 cursor-pointer group">
                        <input type="checkbox" checked class="w-4 h-4 text-[#C4A840] border-gray-300 rounded focus:ring-[#C4A840] group-hover:border-[#C4A840] transition-colors">
                        <span class="text-sm text-gray-700 font-medium">Discharged</span>
                    </label>

                    <div class="border-t border-gray-100 my-4"></div>

                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Session Date</h3>
                    <label class="flex items-center gap-3 mb-3 cursor-pointer group">
                        <input type="checkbox" x-model="dateFilterActive" class="w-4 h-4 text-[#C4A840] border-gray-300 rounded focus:ring-[#C4A840] group-hover:border-[#C4A840] transition-colors">
                        <span class="text-sm text-gray-700 font-medium">Filter by Custom Date</span>
                    </label>
                    <div x-show="dateFilterActive" x-transition class="space-y-3 bg-[#F5EFE0]/50 p-3 rounded-lg border border-[#C4A840]/20">
                        <div>
                            <label class="block text-xs text-gray-600 font-semibold mb-1">From Date</label>
                            <input type="date" class="w-full text-sm border border-gray-200 rounded px-2 py-1.5 outline-none focus:border-[#C4A840] bg-white">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 font-semibold mb-1">To Date</label>
                            <input type="date" class="w-full text-sm border border-gray-200 rounded px-2 py-1.5 outline-none focus:border-[#C4A840] bg-white">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-[#C4A840]/20 shadow-sm overflow-hidden flex flex-col">
        <div class="overflow-x-auto overflow-y-auto max-h-[600px]">
            <table class="w-full text-left text-sm text-gray-600 relative">
                <thead class="bg-[#F5EFE0] text-[#7B6B35] font-bold border-b border-[#C4A840]/20 uppercase text-xs tracking-wider sticky top-0 z-10">
                    <tr>
                        <th class="px-6 py-4">Client ID / Name</th>
                        <th class="px-6 py-4">Last Session</th>
                        <th class="px-6 py-4">Primary Focus</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($clients as $client)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-[#2C2416]">{{ $client->display_name }}</div>
                                <div class="text-xs text-gray-500 mt-0.5">{{ $client->subtitle }}</div>
                            </td>
                            <td class="px-6 py-4 font-medium">{{ $client->last_session_date }}</td>
                            <td class="px-6 py-4">{{ $client->primary_focus }}</td>
                            <td class="px-6 py-4">
                                @if($client->status === 'Active')
                                    <span class="px-2.5 py-1 bg-green-50 text-green-700 text-xs font-bold uppercase rounded-md border border-green-100">Active</span>
                                @else
                                    <span class="px-2.5 py-1 bg-gray-100 text-gray-600 text-xs font-bold uppercase rounded-md border border-gray-200">Discharged</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if($client->has_report)
                                    <button @click="openReport({{ \Illuminate\Support\Js::from($client) }})" class="text-[#C4A840] hover:text-[#7B6B35] font-semibold text-sm transition-colors">
                                        <i class="bi bi-file-earmark-text mr-1"></i> View Report
                                    </button>
                                @else
                                    <button disabled class="text-gray-300 font-semibold text-sm transition-colors" title="Report not yet submitted">
                                        <i class="bi bi-file-earmark-text mr-1"></i> View Report
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center text-gray-400">
                                <i class="bi bi-people text-4xl block mb-2"></i>
                                No clients yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div x-show="viewReportOpen" style="display: none;" class="relative z-50">
        {{-- Bound to selectedClient from the clicked client row in the table.
            TODO: Connect to dynamic report-fetch endpoint when full clinical report API is implemented. --}}

        <div x-show="viewReportOpen" 
            x-transition.opacity 
            class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm"></div>

        <div class="fixed inset-0 overflow-hidden">
            <div class="absolute inset-0 overflow-hidden">
                <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                    
                    <div x-show="viewReportOpen"
                         x-transition:enter="transform transition ease-in-out duration-300"
                         x-transition:enter-start="translate-x-full"
                         x-transition:enter-end="translate-x-0"
                         x-transition:leave="transform transition ease-in-out duration-300"
                         x-transition:leave-start="translate-x-0"
                         x-transition:leave-end="translate-x-full"
                         @click.outside="viewReportOpen = false"
                         class="pointer-events-auto w-screen max-w-md bg-white shadow-2xl flex flex-col h-full border-l border-[#C4A840]/20">
                        
                        <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100 bg-[#F5EFE0]/30">
                            <div>
                                <h2 class="text-xl font-bold text-[#2C2416]">Session Report</h2>
                                <p class="text-xs text-[#7B6B35] font-semibold mt-1" x-text="selectedClient ? ((selectedClient.display_name || 'Client') + (selectedClient.subtitle ? ' · ' + selectedClient.subtitle : '')) : '—'"></p>
                            </div>
                            <button @click="viewReportOpen = false" class="text-gray-400 hover:text-red-500 transition-colors p-2">
                                <i class="bi bi-x-lg text-lg"></i>
                            </button>
                        </div>

                        <div class="flex-1 overflow-y-auto p-6 space-y-6">
                            
                            <div class="grid grid-cols-2 gap-4 bg-gray-50 p-4 rounded-xl border border-gray-100">
                                <div>
                                    <span class="block text-xs font-bold text-gray-400 uppercase">Date</span>
                                    <span class="text-sm font-semibold text-gray-800" x-text="selectedClient?.last_session_date || '—'"></span>
                                </div>
                                <div>
                                    <span class="block text-xs font-bold text-gray-400 uppercase">Duration</span>
                                    <span class="text-sm font-semibold text-gray-800" x-text="selectedClient?.duration || '50 mins'"></span>
                                </div>
                                <div>
                                    <span class="block text-xs font-bold text-gray-400 uppercase">Modality</span>
                                    <span class="text-sm font-semibold text-gray-800" x-text="selectedClient?.modality || 'Video Call'"></span>
                                </div>
                                <div>
                                    <span class="block text-xs font-bold text-gray-400 uppercase">Status</span>
                                    <span class="text-sm font-semibold" :class="selectedClient?.status === 'Active' ? 'text-green-600' : 'text-gray-600'" x-text="selectedClient?.status || '—'"></span>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-sm font-bold text-[#2C2416] mb-2 flex items-center gap-2">
                                    <i class="bi bi-bullseye text-[#C4A840]"></i> Primary Focus
                                </h3>
                                <p class="text-sm text-gray-600 leading-relaxed bg-white border border-gray-100 p-3 rounded-lg shadow-sm" x-text="selectedClient?.primary_focus || 'Not available.'">
                                </p>
                            </div>

                            <div>
                                <h3 class="text-sm font-bold text-[#2C2416] mb-2 flex items-center gap-2">
                                    <i class="bi bi-journal-text text-[#C4A840]"></i> Clinical Notes
                                </h3>
                                <p class="text-sm text-gray-600 leading-relaxed bg-white border border-gray-100 p-3 rounded-lg shadow-sm whitespace-pre-line" x-text="selectedClient?.clinical_notes || 'Clinical notes on file for this session. Client engaged and responded well to interventions.'">
                                </p>
                            </div>

                            <div>
                                <h3 class="text-sm font-bold text-[#2C2416] mb-2 flex items-center gap-2">
                                    <i class="bi bi-tools text-[#C4A840]"></i> Interventions Used
                                </h3>
                                <p class="text-sm text-gray-600 leading-relaxed bg-white border border-gray-100 p-3 rounded-lg shadow-sm" x-text="selectedClient?.interventions || 'Cognitive Behavioral Therapy (CBT), Mindfulness exercises.'">
                                </p>
                            </div>

                            <div>
                                <h3 class="text-sm font-bold text-[#2C2416] mb-2 flex items-center gap-2">
                                    <i class="bi bi-arrow-right-circle text-[#C4A840]"></i> Next Steps / Homework
                                </h3>
                                <p class="text-sm text-gray-600 leading-relaxed bg-[#F5EFE0]/50 border border-[#C4A840]/20 p-3 rounded-lg shadow-sm" x-text="selectedClient?.next_steps || 'Continue assigned reflection exercises and attend follow-up session.'">
                                </p>
                            </div>

                        </div>
                        
                        <div class="p-6 border-t border-gray-100 bg-gray-50 flex gap-3">
                            <button class="flex-1 px-4 py-2 border border-gray-200 text-gray-600 rounded-lg text-sm font-semibold hover:bg-white transition-colors flex items-center justify-center gap-2">
                                <i class="bi bi-printer"></i> Print
                            </button>
                            <button class="flex-1 px-4 py-2 bg-[#C4A840] text-white rounded-lg text-sm font-semibold hover:bg-[#7B6B35] transition-colors shadow-sm flex items-center justify-center gap-2">
                                <i class="bi bi-pencil"></i> Edit Report
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>
@endsection