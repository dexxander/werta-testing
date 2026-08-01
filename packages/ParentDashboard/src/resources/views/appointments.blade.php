@extends('parentdashboard::layout')

@section('content')
<div>
    <div class="mb-6 sm:mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-[#2C2416]">Appointments</h1>
        <p class="text-sm text-gray-500 mt-1">View and manage upcoming counseling sessions for your children.</p>
    </div>

    <!-- Empty State -->
    <div class="bg-white rounded-2xl p-12 border border-[#C4A840]/20 shadow-sm text-center">
        <div class="w-20 h-20 rounded-full bg-[#F5EFE0] flex items-center justify-center mx-auto mb-4">
            <i class="bi bi-calendar-event text-4xl text-[#C4A840]"></i>
        </div>
        <h3 class="text-lg font-bold text-[#2C2416] mb-2">No Appointments Scheduled</h3>
        <p class="text-sm text-gray-500 max-w-md mx-auto">When you book a counseling session for your child, the appointment details will be shown here.</p>
    </div>
</div>
@endsection
