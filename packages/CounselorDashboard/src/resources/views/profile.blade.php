@extends('counselor-dashboard::layout')

@section('content')
<div class="mb-6 sm:mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h1 class="text-2xl sm:text-3xl font-bold text-[#2C2416]">Profile & Rates</h1>
        <p class="text-sm text-gray-500 mt-1">Manage your public profile, set your session rates, and update your specialties.</p>
    </div>
    
    <button class="flex items-center gap-2 bg-[#C4A840] hover:bg-[#7B6B35] text-white px-6 py-2.5 rounded-lg font-semibold transition-colors shadow-sm">
        <i class="bi bi-check2-circle text-lg"></i> Save Changes
    </button>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 sm:gap-8">
    
    <div class="xl:col-span-2 space-y-6">
        
        <div class="bg-white rounded-2xl p-6 md:p-8 border border-[#C4A840]/20 shadow-sm">
            <h2 class="text-lg font-bold text-[#2C2416] mb-6 pb-4 border-b border-gray-100">Public Profile Details</h2>
            
            <div class="flex flex-col sm:flex-row gap-6 mb-8">
                <div class="shrink-0 flex flex-col items-center gap-3">
                    <div class="w-24 h-24 rounded-full bg-[#F5EFE0] border-2 border-[#C4A840] flex items-center justify-center overflow-hidden">
                        <i class="bi bi-person-fill text-4xl text-[#7B6B35]"></i>
                    </div>
                    <button class="text-xs font-semibold text-[#C4A840] hover:text-[#7B6B35] transition-colors">Change Photo</button>
                </div>
                
                <div class="flex-1 space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Display Name</label>
                            <input type="text" name="display_name" value="{{ $counselor->display_name ?? '' }}" class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-[#C4A840] outline-none text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Professional Title</label>
                            <input type="text" name="title" value="{{ $counselor->title ?? '' }}" class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-[#C4A840] outline-none text-sm">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Bio / About Me</label>
                        <textarea name="bio" rows="4" class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-[#C4A840] outline-none text-sm resize-none">{{ $counselor->bio ?? '' }}</textarea>
                    </div>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Specialties (Select up to 3)</label>
                    <div class="space-y-2">
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="checkbox" name="specialties[]" value="Academic Anxiety" class="w-4 h-4 text-[#C4A840] border-gray-300 rounded focus:ring-[#C4A840]">
                            <span class="text-sm text-gray-600 group-hover:text-gray-900 transition-colors">Academic Anxiety</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="checkbox" name="specialties[]" value="Depression & Mood" class="w-4 h-4 text-[#C4A840] border-gray-300 rounded focus:ring-[#C4A840]">
                            <span class="text-sm text-gray-600 group-hover:text-gray-900 transition-colors">Depression & Mood</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="checkbox" name="specialties[]" value="Relationship Counseling" class="w-4 h-4 text-[#C4A840] border-gray-300 rounded focus:ring-[#C4A840]">
                            <span class="text-sm text-gray-600 group-hover:text-gray-900 transition-colors">Relationship Counseling</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="checkbox" name="specialties[]" value="Career Transitions" class="w-4 h-4 text-[#C4A840] border-gray-300 rounded focus:ring-[#C4A840]">
                            <span class="text-sm text-gray-600 group-hover:text-gray-900 transition-colors">Career Transitions</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Languages Spoken</label>
                    <div class="space-y-2">
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="checkbox" name="languages[]" value="English" class="w-4 h-4 text-[#C4A840] border-gray-300 rounded focus:ring-[#C4A840]">
                            <span class="text-sm text-gray-600 group-hover:text-gray-900 transition-colors">English</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="checkbox" name="languages[]" value="Bahasa Melayu" class="w-4 h-4 text-[#C4A840] border-gray-300 rounded focus:ring-[#C4A840]">
                            <span class="text-sm text-gray-600 group-hover:text-gray-900 transition-colors">Bahasa Melayu</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="checkbox" name="languages[]" value="Mandarin" class="w-4 h-4 text-[#C4A840] border-gray-300 rounded focus:ring-[#C4A840]">
                            <span class="text-sm text-gray-600 group-hover:text-gray-900 transition-colors">Mandarin</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="xl:col-span-1 space-y-6">
        
        <div class="bg-white rounded-2xl p-6 border border-[#C4A840]/20 shadow-sm">
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100">
                <div class="flex items-center gap-2">
                    <i class="bi bi-wallet2 text-[#C4A840] text-lg"></i>
                    <h2 class="text-lg font-bold text-[#2C2416]">Session Rates</h2>
                </div>
                <span class="px-2 py-1 bg-amber-50 text-amber-700 text-[10px] font-bold uppercase rounded border border-amber-200" title="Changes require admin approval">
                    Pending Approval
                </span>
            </div>
            
            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Individual Session (1 Hour)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-500 font-medium">RM</span>
                        <input type="number" name="rate_individual" value="{{ $counselor->rate_individual ?? '' }}" class="w-full pl-12 pr-4 py-2.5 rounded-lg border border-gray-200 focus:border-[#C4A840] focus:ring-1 focus:ring-[#C4A840] outline-none text-sm font-semibold text-gray-800">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Group Session (Per Person)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-500 font-medium">RM</span>
                        <input type="number" name="rate_group" value="{{ $counselor->rate_group ?? '' }}" class="w-full pl-12 pr-4 py-2.5 rounded-lg border border-gray-200 focus:border-[#C4A840] focus:ring-1 focus:ring-[#C4A840] outline-none text-sm font-semibold text-gray-800">
                    </div>
                </div>
                
                <div class="pt-2 mt-2">
                    <p class="text-xs text-gray-500 leading-relaxed bg-gray-50 p-3 rounded-lg border border-gray-100">
                        <i class="bi bi-info-circle text-[#C4A840] mr-1"></i> Standard platform fees will be deducted. Any applicable student subsidies or discounts are applied automatically at client checkout.
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-[#C4A840]/20 shadow-sm">
            <div class="flex items-center gap-2 mb-6 pb-4 border-b border-gray-100">
                <i class="bi bi-clock-history text-[#C4A840] text-lg"></i>
                <h2 class="text-lg font-bold text-[#2C2416]">Booking Settings</h2>
            </div>
            
            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Minimum Notice Period</label>
                    <p class="text-xs text-gray-500 mb-2">How far in advance must clients book?</p>
                    <select name="min_notice" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-[#C4A840] outline-none text-sm text-gray-600 bg-white">
                        <option value="24">24 Hours Before</option>
                        <option value="48">48 Hours Before</option>
                        <option value="168">1 Week Before</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Session Buffer</label>
                    <p class="text-xs text-gray-500 mb-2">Rest time automatically added between sessions.</p>
                    <select name="session_buffer" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-[#C4A840] outline-none text-sm text-gray-600 bg-white">
                        <option value="15">15 Minutes</option>
                        <option value="30">30 Minutes</option>
                        <option value="60">1 Hour</option>
                    </select>
                </div>
                
                <div class="pt-4 mt-2 border-t border-gray-100">
                    <p class="text-xs text-gray-500 mb-2">To update your specific daily working hours or block out vacation days, please use the Schedule manager.</p>
                    <a href="{{ route('counselor.schedule') }}" class="text-sm font-semibold text-[#C4A840] hover:text-[#7B6B35] flex items-center gap-1 transition-colors">
                        Go to Schedule & Slots <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection