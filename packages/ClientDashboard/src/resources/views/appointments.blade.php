@extends('clientdashboard::layout')

@section('content')
<div>
    <div class="mb-6 sm:mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-dark">My Appointments</h1>
        <p class="text-sm text-gray-500 mt-1">View and manage your upcoming counseling sessions.</p>
    </div>

    <!-- Empty State -->
    <div class="bg-white rounded-2xl p-12 border border-gold/20 shadow-sm text-center">
        <div class="w-20 h-20 rounded-full bg-cream flex items-center justify-center mx-auto mb-4">
            <i class="bi bi-calendar-event text-4xl text-gold"></i>
        </div>
        <h3 class="text-lg font-bold text-dark mb-2">No Appointments Scheduled</h3>
        <p class="text-sm text-gray-500 max-w-md mx-auto">When you book a counseling session, the details will be shown here.</p>
    </div>
</div>
@endsection
