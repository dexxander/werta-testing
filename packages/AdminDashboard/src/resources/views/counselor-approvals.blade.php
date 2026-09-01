@extends('admindashboard::layout')

@section('content')
<div>
    <div class="mb-6 sm:mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-[#2C2416]">Counselor Approvals</h1>
        <p class="text-sm text-gray-500 mt-1">Review and approve counselor applications submitted to the platform.</p>
    </div>

    <!-- Filter Tabs -->
    <div class="flex gap-2 mb-6">
        <button class="px-4 py-2 rounded-lg text-sm font-semibold bg-[#C4A840]/10 text-[#7B6B35]">Pending (0)</button>
        <button class="px-4 py-2 rounded-lg text-sm font-semibold text-gray-500 hover:bg-gray-50">Approved (0)</button>
        <button class="px-4 py-2 rounded-lg text-sm font-semibold text-gray-500 hover:bg-gray-50">Rejected (0)</button>
    </div>

    @if(session('success'))
    <div class="mb-4 p-3 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm font-medium">
        <i class="bi bi-check-circle mr-1"></i> {{ session('success') }}
    </div>
    @endif

    <!-- Applications Table -->
    <div class="bg-white rounded-2xl p-4 sm:p-6 border border-[#C4A840]/20 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-[#F5EFE0] text-[#7B6B35] font-bold border-b border-[#C4A840]/20 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-4 py-3 rounded-tl-lg">Name</th>
                        <th class="px-4 py-3">Qualification</th>
                        <th class="px-4 py-3">Submitted</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right rounded-tr-lg">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
    @forelse($counselors as $counselor)
        <tr>
            <td class="px-4 py-3 font-semibold text-[#2C2416]">{{ $counselor->name }}</td>
            <td class="px-4 py-3">{{ $counselor->qualification }}</td>
            <td class="px-4 py-3">{{ $counselor->created_at->diffForHumans() }}</td>
            <td class="px-4 py-3">
                @if($counselor->status === 'pending')
                    <span class="text-xs font-bold uppercase px-2 py-1 rounded-full bg-yellow-50 text-yellow-700">Pending</span>
                @elseif($counselor->status === 'approved')
                    <span class="text-xs font-bold uppercase px-2 py-1 rounded-full bg-green-50 text-green-700">Approved</span>
                @else
                    <span class="text-xs font-bold uppercase px-2 py-1 rounded-full bg-red-50 text-red-700">Rejected</span>
                @endif
            </td>
            <td class="px-4 py-3 text-right">
                @if($counselor->status === 'pending')
                    <form method="POST" action="{{ url('/' . $role . '/counselor-approvals/' . $counselor->id . '/approve') }}" class="inline">
                        @csrf
                        <button class="text-xs font-semibold text-green-700 hover:underline mr-3">Approve</button>
                    </form>
                    <form method="POST" action="{{ url('/' . $role . '/counselor-approvals/' . $counselor->id . '/reject') }}" class="inline">
                        @csrf
                        <button class="text-xs font-semibold text-red-600 hover:underline">Reject</button>
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
                No counselor applications submitted yet.
            </td>
        </tr>
    @endforelse
</tbody>
            </table>
        </div>
    </div>
</div>
@endsection