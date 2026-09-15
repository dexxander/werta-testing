@extends('admindashboard::layout')

@section('content')
<div>
    <div class="mb-6 sm:mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-dark">Settings</h1>
        <p class="text-sm text-gray-500 mt-1">Manage your account preferences and platform configuration.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Profile Settings -->
        <div class="bg-white rounded-2xl p-6 border border-gold/20 shadow-sm">
            <h2 class="text-lg font-bold text-dark mb-4">Profile</h2>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Display Name</label>
                    <input type="text" disabled value="{{ ucfirst($role) }} User" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-gray-50 text-sm text-gray-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                    <input type="email" disabled value="{{ $role }}@werta.com" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-gray-50 text-sm text-gray-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Role</label>
                    <input type="text" disabled value="{{ ucfirst($role) }}" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-gray-50 text-sm text-gray-500">
                </div>
            </div>
        </div>

        <!-- Platform Settings (Superadmin gets extra note) -->
        <div class="bg-white rounded-2xl p-6 border border-gold/20 shadow-sm">
            <h2 class="text-lg font-bold text-dark mb-4">Platform Configuration</h2>
            @if($role === 'superadmin')
            <p class="text-sm text-gray-500 mb-4">As Superadmin, you have access to system-wide configuration options.</p>
            @else
            <p class="text-sm text-gray-500 mb-4">Contact a Superadmin for platform-wide configuration changes.</p>
            @endif
            <div class="text-center text-gray-400 py-10">
                <i class="bi bi-gear text-4xl block mb-2"></i>
                Configuration options coming soon.
            </div>
        </div>
    </div>
</div>
@endsection