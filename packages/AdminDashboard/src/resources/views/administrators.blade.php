@extends('admindashboard::layout')

@section('content')
<div x-data="{ isModalOpen: false }">

    <div class="mb-6 sm:mb-8 flex justify-between items-end">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-dark">Administrators</h1>
            <p class="text-sm text-gray-500 mt-1">Manage admin accounts and their access to the platform.</p>
        </div>
        <button @click="isModalOpen = true" class="hidden sm:flex items-center gap-2 bg-gold hover:bg-primary text-white px-4 py-2 rounded-lg font-semibold transition-colors shadow-sm">
            <i class="bi bi-plus-lg"></i> Add Administrator
        </button>
    </div>

    <!-- Administrators Table -->
    <div class="bg-white rounded-2xl p-4 sm:p-6 border border-gold/20 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-cream text-primary font-bold border-b border-gold/20 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-4 py-3 rounded-tl-lg">Name</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Added On</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right rounded-tr-lg">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
    @forelse($administrators as $admin)
        <tr>
            <td class="px-4 py-3 font-semibold text-dark">{{ $admin->name }}</td>
            <td class="px-4 py-3">{{ $admin->email }}</td>
            <td class="px-4 py-3">{{ $admin->created_at->format('d M Y') }}</td>
            <td class="px-4 py-3">
                <span class="text-xs font-bold uppercase px-2 py-1 rounded-full bg-green-50 text-green-700">{{ ucfirst($admin->status) }}</span>
            </td>
            <td class="px-4 py-3 text-right">
                <form method="POST" action="{{ url('/superadmin/administrators/' . $admin->id) }}" class="inline" onsubmit="return confirm('Remove this administrator?');">
                    @csrf
                    @method('DELETE')
                    <button class="text-xs font-semibold text-red-600 hover:underline">Remove</button>
                </form>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="5" class="px-4 py-16 text-center text-gray-400">
                <i class="bi bi-person-badge text-4xl block mb-2"></i>
                No administrator accounts created yet.
            </td>
        </tr>
    @endforelse
</tbody>
            </table>
        </div>
    </div>

    <!-- Add Administrator Modal -->
    <div x-show="isModalOpen" style="display: none;" class="relative z-[9999]" role="dialog" aria-modal="true">
        <div x-show="isModalOpen" x-transition.opacity class="fixed inset-0 bg-black bg-opacity-40"></div>
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
                                <h3 class="text-lg font-bold leading-6 text-dark">Add New Administrator</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 mb-4">Create a new admin account with access to counselor approvals and user management.</p>
                                    <form method="POST" action="{{ url('/superadmin/administrators') }}" id="add-admin-form" class="space-y-4">
    @csrf
    <div>
        <label class="block text-sm font-semibold text-gray-700">Full Name</label>
        <input type="text" name="name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gold focus:ring focus:ring-gold/20 p-2 border" placeholder="e.g. Ahmad bin Ali">
    </div>
    <div>
        <label class="block text-sm font-semibold text-gray-700">Email Address</label>
        <input type="email" name="email" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gold focus:ring focus:ring-gold/20 p-2 border" placeholder="admin@werta.com">
    </div>
    <div>
        <label class="block text-sm font-semibold text-gray-700">Temporary Password</label>
        <input type="password" name="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gold focus:ring focus:ring-gold/20 p-2 border" placeholder="••••••••">
    </div>
    <button type="submit" id="submit-admin-form" class="hidden"></button>
</form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t border-gray-100">
                        <button type="submit" form="add-admin-form" class="inline-flex w-full justify-center rounded-md bg-gold px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary sm:ml-3 sm:w-auto transition-colors">Create Account</button>
                        <button type="button" @click="isModalOpen = false" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition-colors">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection