@extends('admindashboard::layout')

@section('content')
<div>
    <div class="mb-6 sm:mb-8 flex justify-between items-end">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-dark">{{ __('User Management') }}</h1>
            <p class="text-sm text-gray-500 mt-1">{{ __('View and manage client accounts across the platform.') }}</p>
        </div>
    </div>

    <!-- Search / Filter Bar -->
    <div class="bg-white rounded-2xl p-4 border border-gold/20 shadow-sm mb-6 flex flex-col sm:flex-row gap-3">
        <div class="relative flex-1">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400"><i class="bi bi-search"></i></span>
            <input type="text" disabled placeholder="{{ __('Search clients by name or email...') }}" class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-200 bg-gray-50 text-sm text-gray-400 outline-none">
        </div>
        <select disabled class="px-4 py-2 rounded-lg border border-gray-200 bg-gray-50 text-sm text-gray-400">
            <option>{{ __('All Clients') }}</option>
        </select>
    </div>

    <!-- Clients Table -->
    <div class="bg-white rounded-2xl p-4 sm:p-6 border border-gold/20 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-cream text-primary font-bold border-b border-gold/20 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-4 py-3 rounded-tl-lg">{{ __('Name') }}</th>
                        <th class="px-4 py-3">{{ __('Email') }}</th>
                        <th class="px-4 py-3">{{ __('Path (A/B)') }}</th>
                        <th class="px-4 py-3">{{ __('Joined') }}</th>
                        <th class="px-4 py-3">{{ __('Status') }}</th>
                        <th class="px-4 py-3 text-right rounded-tr-lg">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
    @forelse($clients as $client)
        <tr>
            <td class="px-4 py-3 font-semibold text-dark">{{ $client->name }}</td>
            <td class="px-4 py-3">{{ $client->email }}</td>
            <td class="px-4 py-3">{{ __('Path') }} {{ \App\Support\SiteText::translate($client->path) }}</td>
            <td class="px-4 py-3">{{ \App\Support\SiteText::translate($client->created_at->translatedFormat('d M Y')) }}</td>
            <td class="px-4 py-3">
                <span class="text-xs font-bold uppercase px-2 py-1 rounded-full {{ $client->status === 'active' ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                    {{ \App\Support\SiteText::translate(ucfirst($client->status)) }}
                </span>
            </td>
            <td class="px-4 py-3 text-right">
                <form method="POST" action="{{ url('/' . $role . '/user-management/' . $client->id) }}" class="inline" onsubmit="return confirm({{ Illuminate\Support\Js::from(__('Remove this client account?')) }});">
                    @csrf
                    @method('DELETE')
                    <button class="text-xs font-semibold text-red-600 hover:underline">{{ __('Remove') }}</button>
                </form>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="6" class="px-4 py-16 text-center text-gray-400">
                <i class="bi bi-people text-4xl block mb-2"></i>
                {{ __('No client accounts registered yet.') }}
            </td>
        </tr>
    @endforelse
</tbody>
            </table>
        </div>
    </div>
</div>
@endsection
