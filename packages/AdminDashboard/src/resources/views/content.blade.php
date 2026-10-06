@extends('admindashboard::layout')

@section('content')
<div>
    <div class="mb-6 sm:mb-8 flex justify-between items-end">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-dark">{{ __('Content Management') }}</h1>
            <p class="text-sm text-gray-500 mt-1">{{ __('Manage articles, e-learning modules, and platform resources.') }}</p>
        </div>
        <button class="hidden sm:flex items-center gap-2 bg-gold hover:bg-primary text-dark hover:text-white px-4 py-2 rounded-lg font-semibold transition-colors shadow-sm">
            <i class="bi bi-plus-lg"></i> {{ __('New Content') }}
        </button>
    </div>

    <!-- Content Type Tabs -->
    <div class="flex gap-2 mb-6">
        <button class="px-4 py-2 rounded-lg text-sm font-semibold bg-gold/10 text-primary">{{ __('Articles (0)') }}</button>
        <button class="px-4 py-2 rounded-lg text-sm font-semibold text-gray-500 hover:bg-gray-50">{{ __('E-Learning Modules (0)') }}</button>
    </div>

    <!-- Content Table -->
    <div class="bg-white rounded-2xl p-4 sm:p-6 border border-gold/20 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-cream text-primary font-bold border-b border-gold/20 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-4 py-3 rounded-tl-lg">{{ __('Title') }}</th>
                        <th class="px-4 py-3">{{ __('Type') }}</th>
                        <th class="px-4 py-3">{{ __('Published') }}</th>
                        <th class="px-4 py-3">{{ __('Status') }}</th>
                        <th class="px-4 py-3 text-right rounded-tr-lg">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr>
                        <td colspan="5" class="px-4 py-16 text-center text-gray-400">
                            <i class="bi bi-file-earmark-text text-4xl block mb-2"></i>
                            {{ __('No content published yet.') }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection