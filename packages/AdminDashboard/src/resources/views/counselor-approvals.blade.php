@extends('admindashboard::layout')

@section('content')
<div>
    <div class="mb-6 sm:mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-dark">{{ __('Counselor Approvals') }}</h1>
        <p class="text-sm text-gray-500 mt-1">{{ __('Review and approve counselor applications submitted to the platform.') }}</p>
    </div>

    <!-- Filter Tabs -->
    <div class="flex gap-2 mb-6">
        <button class="px-4 py-2 rounded-lg text-sm font-semibold bg-gold/10 text-primary">{{ __('Pending (0)') }}</button>
        <button class="px-4 py-2 rounded-lg text-sm font-semibold text-gray-500 hover:bg-gray-50">{{ __('Approved (0)') }}</button>
        <button class="px-4 py-2 rounded-lg text-sm font-semibold text-gray-500 hover:bg-gray-50">{{ __('Rejected (0)') }}</button>
    </div>

    @if(session('success'))
    <div class="mb-4 p-3 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm font-medium">
        <i class="bi bi-check-circle mr-1"></i> {{ \App\Support\SiteText::translate(session('success')) }}
    </div>
    @endif

    <!-- Applications Table -->
    <div class="bg-white rounded-2xl p-4 sm:p-6 border border-gold/20 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-cream text-primary font-bold border-b border-gold/20 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-4 py-3 rounded-tl-lg">{{ __('Name') }}</th>
                        <th class="px-4 py-3">{{ __('Qualification') }}</th>
                        <th class="px-4 py-3">{{ __('Submitted') }}</th>
                        <th class="px-4 py-3">{{ __('Status') }}</th>
                        <th class="px-4 py-3 text-right rounded-tr-lg">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
    @forelse($counselors as $counselor)
        <tr>
            <td class="px-4 py-3 font-semibold text-dark">{{ $counselor->name }}</td>
            <td class="px-4 py-3">{{ \App\Support\SiteText::translate($counselor->qualification) }}</td>
            <td class="px-4 py-3">{{ \App\Support\SiteText::translate($counselor->created_at->diffForHumans()) }}</td>
            <td class="px-4 py-3">
                @if($counselor->status === 'pending')
                    <span class="text-xs font-bold uppercase px-2 py-1 rounded-full bg-yellow-50 text-yellow-700">{{ __('Pending') }}</span>
                @elseif($counselor->status === 'approved')
                    <span class="text-xs font-bold uppercase px-2 py-1 rounded-full bg-green-50 text-green-700">{{ __('Approved') }}</span>
                @else
                    <span class="text-xs font-bold uppercase px-2 py-1 rounded-full bg-red-50 text-red-700">{{ __('Rejected') }}</span>
                @endif
            </td>
            <td class="px-4 py-3 text-right">
                @if($counselor->status === 'pending')
                    <form method="POST" action="{{ url('/' . $role . '/counselor-approvals/' . $counselor->id . '/approve') }}" class="inline">
                        @csrf
                        <button class="text-xs font-semibold text-green-700 hover:underline mr-3">{{ __('Approve') }}</button>
                    </form>
                    <form method="POST" action="{{ url('/' . $role . '/counselor-approvals/' . $counselor->id . '/reject') }}" class="inline">
                        @csrf
                        <button class="text-xs font-semibold text-red-600 hover:underline">{{ __('Reject') }}</button>
                    </form>
                @else
                    <span class="text-xs text-gray-400">—</span>
                @endif
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="5" class="px-4 py-16 text-center text-gray-400">
                <i class="bi bi-inbox text-4xl block mb-2"></i>
                {{ __('No counselor applications submitted yet.') }}
            </td>
        </tr>
    @endforelse
</tbody>
            </table>
        </div>
    </div>
</div>
@endsection