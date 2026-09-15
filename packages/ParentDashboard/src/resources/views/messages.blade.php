@extends('parentdashboard::layout')

@section('content')
<div>
    <div class="mb-6 sm:mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-dark">Messages</h1>
        <p class="text-sm text-gray-500 mt-1">Communicate with counselors and receive updates about your child's care.</p>
    </div>

    <!-- Empty State -->
    <div class="bg-white rounded-2xl p-12 border border-gold/20 shadow-sm text-center">
        <div class="w-20 h-20 rounded-full bg-cream flex items-center justify-center mx-auto mb-4">
            <i class="bi bi-chat-dots text-4xl text-gold"></i>
        </div>
        <h3 class="text-lg font-bold text-dark mb-2">No Messages Yet</h3>
        <p class="text-sm text-gray-500 max-w-md mx-auto">Messages from counselors and platform notifications will appear here.</p>
    </div>
</div>
@endsection
