@extends('admindashboard::layout')

@section('content')
<div>
    <div class="mb-6 sm:mb-8 flex justify-between items-end">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-[#2C2416]">Content Management</h1>
            <p class="text-sm text-gray-500 mt-1">Manage articles, e-learning modules, and platform resources.</p>
        </div>
        <button class="hidden sm:flex items-center gap-2 bg-[#C4A840] hover:bg-[#7B6B35] text-white px-4 py-2 rounded-lg font-semibold transition-colors shadow-sm">
            <i class="bi bi-plus-lg"></i> New Content
        </button>
    </div>

    <!-- Content Type Tabs -->
    <div class="flex gap-2 mb-6">
        <button class="px-4 py-2 rounded-lg text-sm font-semibold bg-[#C4A840]/10 text-[#7B6B35]">Articles (0)</button>
        <button class="px-4 py-2 rounded-lg text-sm font-semibold text-gray-500 hover:bg-gray-50">E-Learning Modules (0)</button>
    </div>

    <!-- Content Table -->
    <div class="bg-white rounded-2xl p-4 sm:p-6 border border-[#C4A840]/20 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-[#F5EFE0] text-[#7B6B35] font-bold border-b border-[#C4A840]/20 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-4 py-3 rounded-tl-lg">Title</th>
                        <th class="px-4 py-3">Type</th>
                        <th class="px-4 py-3">Published</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right rounded-tr-lg">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr>
                        <td colspan="5" class="px-4 py-16 text-center text-gray-400">
                            <i class="bi bi-file-earmark-text text-4xl block mb-2"></i>
                            No content published yet.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection