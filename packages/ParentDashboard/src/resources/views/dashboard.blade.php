@extends('parentdashboard::layout')

@section('content')
@php
    // Safe defaults keep the dashboard's empty states working without demo data.
    $dashboard_stats = [];
    $assessment_scores = [];
    $weekly_activity = [];
    $recent_activities = [];
@endphp

{{-- ============================================================
     TEMP DUMMY DATA — FOR PREVIEW ONLY
     Delete this entire block to return the dashboard to its empty states.
     The safe defaults above ensure the view still works after removal.
     ============================================================ --}}
@php
    // TEMP DUMMY DATA START
    $dashboard_stats = [
        'assessments_completed' => 12,
        'modules_finished' => 7,
        'upcoming_appointments' => 2,
        'action_required' => 1,
    ];
    $assessment_scores = [68, 74, 79, 83, 87];
    $weekly_activity = [3, 4.5, 2, 5, 6, 2.5, 1];
    $recent_activities = [
        ['date' => '01 Sep 2026', 'child' => 'Aiman', 'type' => 'Wellness Assessment', 'status' => 'Completed'],
        ['date' => '30 Aug 2026', 'child' => 'Sofia', 'type' => 'E-Learning Module', 'status' => 'In Progress'],
        ['date' => '29 Aug 2026', 'child' => 'Aiman', 'type' => 'Counselling Appointment', 'status' => 'Upcoming'],
    ];
    // TEMP DUMMY DATA END
@endphp

<div x-data="{ 
    isModalOpen: false,
    childName: '',
    childEmail: '',
    childPassword: '',
    submitChild() {
        if (!this.childName) {
            alert('Please enter your child\'s name.');
            return;
        }
        console.log('Registering child account:', {
            name: this.childName,
            email: this.childEmail,
            password: this.childPassword
        });
        alert('Child account registered for ' + this.childName + ' (' + (this.childEmail || 'no email provided') + ').');
        this.isModalOpen = false;
        this.childName = '';
        this.childEmail = '';
        this.childPassword = '';
    }
}" @open-add-child.window="isModalOpen = true">
    
    <div class="mb-6 sm:mb-8 flex justify-between items-end">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-dark">Parent Overview</h1>
            <p class="text-sm text-gray-500 mt-1">Welcome back, {{ session('parent_profile.username', 'Parent User') }}. Monitor your children's mental wellness and platform activity.</p>
        </div>
        <button @click="isModalOpen = true" class="hidden sm:flex items-center gap-2 bg-gold hover:bg-primary text-white px-4 py-2 rounded-lg font-semibold transition-colors shadow-sm">
            <i class="bi bi-plus-lg"></i> Register Child
        </button>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
        <div class="bg-white rounded-2xl p-4 sm:p-6 border border-gold/20 shadow-sm flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4">
            <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-xl flex items-center justify-center shrink-0 bg-gold/10 text-gold">
                <i class="bi bi-clipboard-check text-xl sm:text-3xl"></i>
            </div>
            <div>
                <p class="text-[10px] sm:text-sm font-bold text-gray-500 uppercase tracking-wide">Assessments Completed</p>
                <h3 class="text-lg sm:text-2xl font-extrabold text-dark mt-1">{{ $dashboard_stats['assessments_completed'] ?? 0 }}</h3>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl p-4 sm:p-6 border border-gold/20 shadow-sm flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4">
            <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-xl flex items-center justify-center shrink-0 bg-gold/10 text-gold">
                <i class="bi bi-book text-xl sm:text-3xl"></i>
            </div>
            <div>
                <p class="text-[10px] sm:text-sm font-bold text-gray-500 uppercase tracking-wide">Modules Finished</p>
                <h3 class="text-lg sm:text-2xl font-extrabold text-dark mt-1">{{ $dashboard_stats['modules_finished'] ?? 0 }}</h3>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-4 sm:p-6 border border-gold/20 shadow-sm flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4">
            <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-xl flex items-center justify-center shrink-0 bg-primary/10 text-primary">
                <i class="bi bi-calendar-check text-xl sm:text-3xl"></i>
            </div>
            <div>
                <p class="text-[10px] sm:text-sm font-bold text-gray-500 uppercase tracking-wide">Upcoming Appointments</p>
                <h3 class="text-lg sm:text-2xl font-extrabold text-dark mt-1">{{ $dashboard_stats['upcoming_appointments'] ?? 0 }}</h3>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-4 sm:p-6 border border-gold/20 shadow-sm flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4">
            <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-xl flex items-center justify-center shrink-0 bg-red-50 text-red-500">
                <i class="bi bi-exclamation-triangle text-xl sm:text-3xl"></i>
            </div>
            <div>
                <p class="text-[10px] sm:text-sm font-bold text-gray-500 uppercase tracking-wide">Action Required</p>
                <h3 class="text-lg sm:text-2xl font-extrabold text-dark mt-1">{{ $dashboard_stats['action_required'] ?? 0 }}</h3>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <div class="bg-white rounded-2xl p-6 border border-gold/20 shadow-sm">
            <h2 class="text-lg font-bold text-dark mb-6">Wellness Assessment Score Trend</h2>
            <div class="h-72 w-full flex items-center justify-center">
                @if(!empty($assessment_scores))<div class="w-full flex items-end justify-center gap-2 h-48">@foreach($assessment_scores as $score)<div class="w-8 bg-gold rounded-t" style="height:{{ min(100, max(0, $score)) }}%;" title="{{ $score }}%"></div>@endforeach</div>@else<div class="text-center text-gray-400"><i class="bi bi-bar-chart text-5xl"></i><p class="text-sm mt-2">No assessment data available yet.</p></div>@endif
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-gold/20 shadow-sm">
            <h2 class="text-lg font-bold text-dark mb-6">Platform Activity (Hours / Week)</h2>
            <div class="h-72 w-full flex items-center justify-center">
                @if(!empty($weekly_activity))<div class="w-full flex items-end justify-center gap-2 h-48">@foreach($weekly_activity as $hours)<div class="w-8 bg-primary rounded-t" style="height:{{ min(100, max(0, $hours * 15)) }}%;" title="{{ $hours }} hours"></div>@endforeach</div>@else<div class="text-center text-gray-400"><i class="bi bi-graph-up text-5xl"></i><p class="text-sm mt-2">No activity data available yet.</p></div>@endif
            </div>
        </div>
    </div>

    <!-- Recent Activities Table -->
    <div class="bg-white rounded-2xl p-4 sm:p-6 border border-gold/20 shadow-sm">
        <h2 class="text-lg font-bold text-dark mb-4 sm:mb-6">Recent Child Activities</h2>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-cream text-primary font-bold border-b border-gold/20 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-4 py-3 rounded-tl-lg">Date</th>
                        <th class="px-4 py-3">Child Name</th>
                        <th class="px-4 py-3">Activity Type</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right rounded-tr-lg">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($recent_activities as $activity)
                        <tr><td class="px-4 py-3">{{ $activity['date'] ?? '' }}</td><td class="px-4 py-3">{{ $activity['child'] ?? '' }}</td><td class="px-4 py-3">{{ $activity['type'] ?? '' }}</td><td class="px-4 py-3">{{ $activity['status'] ?? '' }}</td><td class="px-4 py-3 text-right">View</td></tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-12 text-center text-gray-400"><i class="bi bi-inbox text-4xl block mb-2"></i>No activities recorded yet. Register a child account to get started.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal for Adding Child -->
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
                     class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-gold/30">
                    
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-cream sm:mx-0 sm:h-10 sm:w-10">
                                <i class="bi bi-person-plus text-primary text-xl"></i>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                <h3 class="text-lg font-bold leading-6 text-dark" id="modal-title">Register Child Account</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 mb-4">Create a Werta account for your underage child to access assessments and learning modules.</p>
                                    
                                    {{-- TODO: Connect to backend child registration endpoint (e.g. POST /parent/children) when API is implemented --}}
                                    <form id="registerChildForm" @submit.prevent="submitChild()" class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700">Child's Full Name</label>
                                            <input type="text" x-model="childName" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gold focus:ring focus:ring-gold/20 p-2 border" placeholder="e.g. Ahmad bin Ali">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700">Email Address (for login)</label>
                                            <input type="email" x-model="childEmail" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gold focus:ring focus:ring-gold/20 p-2 border" placeholder="ahmad@example.com">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700">Temporary Password</label>
                                            <input type="password" x-model="childPassword" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gold focus:ring focus:ring-gold/20 p-2 border" placeholder="••••••••">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t border-gray-100">
                        <button type="submit" form="registerChildForm" class="inline-flex w-full justify-center rounded-md bg-gold px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary sm:ml-3 sm:w-auto transition-colors">Register Account</button>
                        <button type="button" @click="isModalOpen = false" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition-colors">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
</div>
@endsection
