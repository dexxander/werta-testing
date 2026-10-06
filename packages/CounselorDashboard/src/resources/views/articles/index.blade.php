@extends('counselor-dashboard::layout')

@section('content')
<div class="mb-6 sm:mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4">
    <div>
        <h1 class="text-2xl sm:text-3xl font-bold text-dark">{{ __('My Published Articles') }}</h1>
        <p class="text-sm text-gray-500 mt-1">{{ __('Manage your wellness articles, edit content, and publish new resources.') }}</p>
    </div>
    
    <a href="{{ route('counselor.articles.create') }}" class="flex items-center gap-2 bg-gold hover:bg-primary text-dark hover:text-white px-5 py-2.5 rounded-lg font-semibold transition-colors shadow-sm">
        <i class="bi bi-pencil-square"></i> {{ __('Write New Article') }}
    </a>
</div>

<div class="bg-white rounded-2xl p-4 border border-gold/20 shadow-sm mb-6 flex flex-col lg:flex-row gap-4 justify-between items-center relative z-20">
    
    <div class="relative w-full lg:w-96 shrink-0">
        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400"><i class="bi bi-search"></i></span>
        <input type="text" class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-200 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none text-sm transition-colors" placeholder="{{ __('Search articles by title or keywords...') }}">
    </div>

    <div class="w-full lg:w-auto flex flex-col sm:flex-row gap-3">
        
        <div class="relative w-full sm:w-auto" x-data="{ sortOpen: false, selectedSort: 'Latest' }" @click.outside="sortOpen = false">
            <button @click="sortOpen = !sortOpen" class="w-full sm:w-44 flex justify-between items-center border border-gray-200 rounded-lg px-4 py-2.5 text-sm outline-none hover:border-primary bg-white transition-colors">
                <span x-text="WertaI18n.t(selectedSort)" class="font-medium text-gray-700"></span>
                <i class="bi bi-chevron-down text-xs text-gray-400"></i>
            </button>
            
            <div x-show="sortOpen" style="display: none;" class="absolute right-0 mt-2 w-full sm:w-48 bg-white border border-gray-100 rounded-lg shadow-lg z-50 py-1 overflow-hidden" x-transition>
                <button @click="selectedSort = 'Latest'; sortOpen = false" :class="selectedSort === 'Latest' ? 'bg-cream text-primary font-semibold' : 'text-gray-700 hover:bg-gray-50'" class="w-full text-left px-4 py-2 text-sm transition-colors">{{ __('Latest') }}</button>
                <button @click="selectedSort = 'Oldest'; sortOpen = false" :class="selectedSort === 'Oldest' ? 'bg-cream text-primary font-semibold' : 'text-gray-700 hover:bg-gray-50'" class="w-full text-left px-4 py-2 text-sm transition-colors">{{ __('Oldest') }}</button>
                <button @click="selectedSort = 'Most Viewed'; sortOpen = false" :class="selectedSort === 'Most Viewed' ? 'bg-cream text-primary font-semibold' : 'text-gray-700 hover:bg-gray-50'" class="w-full text-left px-4 py-2 text-sm transition-colors">{{ __('Most Viewed') }}</button>
                <button @click="selectedSort = 'Name: A - Z'; sortOpen = false" :class="selectedSort === 'Name: A - Z' ? 'bg-cream text-primary font-semibold' : 'text-gray-700 hover:bg-gray-50'" class="w-full text-left px-4 py-2 text-sm transition-colors">{{ __('Name: A - Z') }}</button>
                <button @click="selectedSort = 'Name: Z - A'; sortOpen = false" :class="selectedSort === 'Name: Z - A' ? 'bg-cream text-primary font-semibold' : 'text-gray-700 hover:bg-gray-50'" class="w-full text-left px-4 py-2 text-sm transition-colors">{{ __('Name: Z - A') }}</button>
            </div>
        </div>

        <div class="relative w-full sm:w-auto" x-data="{ filterOpen: false }" @click.outside="filterOpen = false">
            <button @click="filterOpen = !filterOpen" class="bg-gray-50 border border-gray-200 text-gray-600 hover:bg-cream hover:text-primary hover:border-gold/30 w-full sm:w-auto px-4 py-2.5 rounded-lg text-sm font-semibold transition-colors flex items-center justify-center gap-2">
                <i class="bi bi-funnel"></i> {{ __('Filter') }}
            </button>
            
            <div x-show="filterOpen" style="display: none;" class="absolute right-0 mt-2 w-full sm:w-64 bg-white ring-1 ring-primary-dark/35 rounded-lg shadow-lg z-50 p-4" x-transition>
                
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">{{ __('Publish Status') }}</h3>
                <label class="flex items-center gap-3 mb-2 cursor-pointer group">
                    <input type="checkbox" checked class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary group-hover:border-primary transition-colors">
                    <span class="text-sm text-gray-700 font-medium">{{ __('Published') }}</span>
                </label>
                <label class="flex items-center gap-3 mb-4 cursor-pointer group">
                    <input type="checkbox" checked class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary group-hover:border-primary transition-colors">
                    <span class="text-sm text-gray-700 font-medium">{{ __('Drafts') }}</span>
                </label>

                <div class="border-t border-gray-100 my-4"></div>

                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">{{ __('Category') }}</h3>
                <select class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:border-primary outline-none bg-white text-sm text-gray-600 transition-colors">
                    <option>{{ __('All Categories') }}</option>
                    <option>{{ __('Anxiety & Stress') }}</option>
                    <option>{{ __('Academic Pressure') }}</option>
                    <option>{{ __('Sleep Health') }}</option>
                    <option>{{ __('Relationships') }}</option>
                </select>
            </div>
        </div>
    </div>
</div>


<div class="bg-white rounded-2xl border border-gold/20 shadow-sm overflow-hidden flex flex-col">
    
    <div class="bg-cream px-6 py-4 border-b border-gold/20 hidden md:grid grid-cols-12 gap-4 text-primary font-bold uppercase text-xs tracking-wider shrink-0 z-10">
        <div class="col-span-6">{{ __('Article Details') }}</div>
        <div class="col-span-2 text-center">{{ __('Status') }}</div>
        <div class="col-span-2 text-center">{{ __('Visibility') }}</div>
        <div class="col-span-2 text-right">{{ __('Actions') }}</div>
    </div>

    <div class="divide-y divide-gray-100 overflow-y-auto max-h-[500px]">
        @forelse($articles as $article)
            <div class="p-4 sm:px-6 sm:py-5 hover:bg-gray-50 transition-colors flex flex-col md:grid md:grid-cols-12 md:items-center gap-4">
                <div class="col-span-6">
                    <h3 class="text-lg font-bold text-dark mb-1">{{ $article->title }}</h3>
                    <p class="text-sm text-gray-500 line-clamp-2">{{ \App\Support\SiteText::translate($article->excerpt) }}</p>
                    <div class="flex items-center gap-3 mt-2 text-xs text-gray-400 font-medium">
                        <span><i class="bi bi-calendar3 mr-1"></i> {{ \App\Support\SiteText::translate($article->date_label) }}</span>
                        <span>•</span>
                        <span class="text-primary bg-gold/10 px-2 py-0.5 rounded">{{ \App\Support\SiteText::translate($article->visibility) }}</span>
                    </div>
                </div>

                <div class="col-span-2 md:text-center">
                    <span class="inline-flex items-center px-2.5 py-1 {{ $article->status_class }} text-xs font-bold uppercase rounded-md">
                        {{ \App\Support\SiteText::translate($article->status_label) }}
                    </span>
                </div>

                <div class="col-span-2 md:text-center font-semibold text-gray-600">
                    {{ \App\Support\SiteText::translate($article->visibility) }}
                </div>

                <div class="col-span-2 flex justify-start md:justify-end">
                    <a href="{{ route('submissions.show', $article->id) }}" class="text-sm font-semibold text-primary hover:text-primary-dark transition-colors">
                        {{ __('View') }}
                    </a>
                </div>
            </div>
        @empty
            <div class="p-12 text-center text-gray-400">
                <i class="bi bi-journal-x text-4xl block mb-2"></i>
                {{ __('No articles yet — write your first one.') }}
            </div>
        @endforelse
    </div>
</div>
@endsection
