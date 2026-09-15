{{-- DEV BYPASS: Development-only alert banner for bypassed sessions --}}
@if(\App\Support\DevAuth::isBypassActive() && session('dev_bypass_session'))
    <div class="bg-amber-400 text-black px-4 py-1.5 text-xs font-mono font-bold flex items-center justify-between border-b-2 border-amber-600 shadow-md shrink-0 z-[99999] select-none">
        <div class="flex items-center gap-2">
            <span class="px-1.5 py-0.5 bg-black text-amber-300 rounded text-[10px] uppercase tracking-wider font-extrabold">DEV MODE</span>
            <span>⚡ DEV AUTH BYPASS ACTIVE &mdash; Simulated Session (Not for production)</span>
        </div>
        <div class="flex items-center gap-3 text-[11px] opacity-90">
            <span>Role: {{ ucfirst(session('staff_role') ?? (session('counselor_logged_in') ? 'Counselor' : (session('parent_logged_in') ? 'Parent' : 'Client'))) }}</span>
            <span class="text-amber-800 font-semibold">[DEV_BYPASS_AUTH=true]</span>
        </div>
    </div>
@endif
