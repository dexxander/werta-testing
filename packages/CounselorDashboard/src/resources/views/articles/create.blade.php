@extends('counselor-dashboard::layout')

@section('content')
<div class="mb-6 sm:mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <div class="flex items-center gap-2 mb-2">
            <a href="{{ route('counselor.articles.index') }}" class="text-sm font-medium text-gray-500 hover:text-gold transition-colors">
                <i class="bi bi-arrow-left"></i> Back to Articles
            </a>
        </div>
        <h1 class="text-2xl sm:text-3xl font-bold text-dark">Write New Article</h1>
    </div>
    
    <div class="flex w-full sm:w-auto gap-3">
        <button class="flex-1 sm:flex-none px-4 py-2 bg-white border border-gray-200 text-gray-600 rounded-lg text-sm font-semibold hover:bg-gray-50 transition-colors">
            Save Draft
        </button>
        <button class="flex-1 sm:flex-none px-6 py-2 bg-gold text-white rounded-lg text-sm font-semibold hover:bg-primary shadow-sm transition-colors">
            Publish Now
        </button>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 sm:gap-8">
    
    <div class="xl:col-span-2 space-y-6">
        <div class="bg-white rounded-2xl p-6 border border-gold/20 shadow-sm space-y-5">
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Article Title</label>
                <input type="text" placeholder="Enter a catchy, helpful title..." class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-gold focus:ring-2 focus:ring-gold/20 outline-none text-lg font-bold text-dark transition-colors placeholder:font-normal">
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Article Content</label>
                
                <div class="border border-gray-200 border-b-0 rounded-t-lg bg-gray-50 px-3 py-2 flex flex-wrap gap-1 text-gray-500">
                    <button class="hover:text-gold hover:bg-gray-200 p-1.5 rounded transition-colors"><i class="bi bi-type-bold"></i></button>
                    <button class="hover:text-gold hover:bg-gray-200 p-1.5 rounded transition-colors"><i class="bi bi-type-italic"></i></button>
                    <button class="hover:text-gold hover:bg-gray-200 p-1.5 rounded transition-colors"><i class="bi bi-type-underline"></i></button>
                    <div class="w-px h-6 bg-gray-300 my-auto mx-2"></div>
                    <button class="hover:text-gold hover:bg-gray-200 p-1.5 rounded transition-colors"><i class="bi bi-list-ul"></i></button>
                    <button class="hover:text-gold hover:bg-gray-200 p-1.5 rounded transition-colors"><i class="bi bi-list-ol"></i></button>
                    <div class="w-px h-6 bg-gray-300 my-auto mx-2"></div>
                    <button class="hover:text-gold hover:bg-gray-200 p-1.5 rounded transition-colors"><i class="bi bi-link-45deg"></i></button>
                    <button class="hover:text-gold hover:bg-gray-200 p-1.5 rounded transition-colors"><i class="bi bi-image"></i></button>
                </div>
                
                <textarea rows="18" placeholder="Write your article content here..." class="w-full px-4 py-4 rounded-b-lg border border-gray-200 focus:border-gold outline-none transition-colors resize-y text-gray-700 leading-relaxed"></textarea>
            </div>
        </div>
    </div>

    <div class="xl:col-span-1 space-y-6">
        <div class="bg-white rounded-2xl p-6 border border-gold/20 shadow-sm space-y-6">
            
            <div>
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Discovery</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Category</label>
                        <select class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:border-gold outline-none bg-white text-sm text-gray-600">
                            <option>Anxiety & Stress</option>
                            <option>Academic Pressure</option>
                            <option>Depression</option>
                            <option>Sleep Health</option>
                            <option>Relationships</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Tags (Comma separated)</label>
                        <input type="text" placeholder="e.g., exams, breathing, focus" class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:border-gold outline-none text-sm">
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-100"></div>

            <div>
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Access Settings</h3>
                <label class="flex items-start justify-between cursor-pointer group">
                    <div class="pr-4">
                        <span class="block text-sm font-semibold text-gray-700">Premium Content</span>
                        <span class="block text-xs text-gray-500 mt-0.5 leading-relaxed">Require users to have an active subscription to read this article.</span>
                    </div>
                    <div class="relative shrink-0 mt-1">
                        <input type="checkbox" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-gold"></div>
                    </div>
                </label>
            </div>

            <div class="border-t border-gray-100"></div>
            
            <div>
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Cover Image</h3>
                <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-gold hover:bg-cream/50 transition-colors cursor-pointer group">
                    <i class="bi bi-cloud-arrow-up text-3xl text-gray-400 group-hover:text-gold mb-2 block transition-colors"></i>
                    <span class="text-sm font-semibold text-gray-700 group-hover:text-primary">Click to upload</span>
                    <p class="text-xs text-gray-400 mt-1">PNG, JPG up to 2MB</p>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection