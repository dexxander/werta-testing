@extends('submissions::layout')

@section('title', 'Submit an Article')

@section('content')
<div class="max-w-3xl mx-auto py-10 px-4">
    <a href="{{ route('submissions.index') }}" class="inline-flex items-center gap-1 text-sm text-[#7B6B35] hover:text-[#C4A840] font-semibold mb-6 transition-colors">
        <i class="bi bi-arrow-left"></i> Back to Your Articles
    </a>

    <div class="bg-white rounded-2xl border border-[#C4A840]/20 shadow-sm p-8">
        <div class="mb-8 border-b border-gray-100 pb-6">
            <h1 class="text-2xl font-bold text-[#2C2416]">Manuscript Submission</h1>
            <p class="text-sm text-gray-500 mt-1">Submit your work for editorial review. All submissions are reviewed before publication.</p>
        </div>

        @if($errors->any())
            <div class="mb-6 p-3 bg-red-50 text-red-700 text-sm rounded-lg">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('submissions.store') }}" class="space-y-8" enctype="multipart/form-data">
            @csrf

            {{-- Section: Manuscript Details --}}
            <div>
                <h2 class="text-xs font-bold uppercase tracking-wider text-[#C4A840] mb-4">Manuscript Details</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Title <span class="text-red-500">*</span></label>
                        <input type="text" name="title" value="{{ old('title') }}" required
                               class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-[#C4A840] focus:ring-1 focus:ring-[#C4A840] outline-none text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Abstract</label>
                        <textarea name="abstract" rows="3" placeholder="A summary readers will see before opening the full paper. Strongly recommended for uploaded papers."
                                  class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-[#C4A840] focus:ring-1 focus:ring-[#C4A840] outline-none text-sm">{{ old('abstract') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">How would you like to submit?</label>
                        <div class="flex gap-4 mb-3 text-sm">
                            <label class="flex items-center gap-1.5 cursor-pointer">
                                <input type="radio" name="submit_mode" value="write" checked onclick="document.getElementById('writeField').classList.remove('hidden'); document.getElementById('uploadField').classList.add('hidden');">
                                Write directly
                            </label>
                            <label class="flex items-center gap-1.5 cursor-pointer">
                                <input type="radio" name="submit_mode" value="upload" onclick="document.getElementById('writeField').classList.add('hidden'); document.getElementById('uploadField').classList.remove('hidden');">
                                Upload a paper (PDF/DOCX)
                            </label>
                        </div>

                        <div id="writeField">
                            <textarea name="content" rows="12" placeholder="Write your article or manuscript here..."
                                    class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-[#C4A840] focus:ring-1 focus:ring-[#C4A840] outline-none text-sm font-mono">{{ old('content') }}</textarea>
                        </div>
                        <div id="uploadField" class="hidden">
                            <input type="file" name="paper_file" accept=".pdf,.doc,.docx"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-[#C4A840] outline-none text-sm">
                            <p class="text-xs text-gray-400 mt-1">PDF or Word document, max 10MB.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section: Publication Options --}}
            <div class="border-t border-gray-100 pt-6">
                <h2 class="text-xs font-bold uppercase tracking-wider text-[#C4A840] mb-4">Publication Options</h2>
                <p class="text-xs text-gray-400 mb-4">These can be changed later from your article's settings, even after publication.</p>

                <div class="grid sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Visibility</label>
                        <div class="space-y-2">
                            <label class="flex items-center gap-2 p-3 rounded-lg border border-gray-200 cursor-pointer hover:border-[#C4A840]/50 has-[:checked]:border-[#C4A840] has-[:checked]:bg-[#F5EFE0]/50">
                                <input type="radio" name="visibility" value="public" checked class="text-[#C4A840] focus:ring-[#C4A840]">
                                <span class="text-sm"><span class="font-semibold text-gray-800">Public</span> <span class="text-gray-400 block text-xs">Visible to all readers once approved</span></span>
                            </label>
                            <label class="flex items-center gap-2 p-3 rounded-lg border border-gray-200 cursor-pointer hover:border-[#C4A840]/50 has-[:checked]:border-[#C4A840] has-[:checked]:bg-[#F5EFE0]/50">
                                <input type="radio" name="visibility" value="private" class="text-[#C4A840] focus:ring-[#C4A840]">
                                <span class="text-sm"><span class="font-semibold text-gray-800">Private</span> <span class="text-gray-400 block text-xs">Only accessible via direct link</span></span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Access</label>
                        <div class="space-y-2">
                            <label class="flex items-center gap-2 p-3 rounded-lg border border-gray-200 cursor-pointer hover:border-[#C4A840]/50 has-[:checked]:border-[#C4A840] has-[:checked]:bg-[#F5EFE0]/50">
                                <input type="radio" name="access_type" value="free" checked onclick="document.getElementById('priceField').classList.add('hidden')" class="text-[#C4A840] focus:ring-[#C4A840]">
                                <span class="text-sm font-semibold text-gray-800">Free</span>
                            </label>
                            <label class="flex items-center gap-2 p-3 rounded-lg border border-gray-200 cursor-pointer hover:border-[#C4A840]/50 has-[:checked]:border-[#C4A840] has-[:checked]:bg-[#F5EFE0]/50">
                                <input type="radio" name="access_type" value="paid" onclick="document.getElementById('priceField').classList.remove('hidden')" class="text-[#C4A840] focus:ring-[#C4A840]">
                                <span class="text-sm font-semibold text-gray-800">Paid</span>
                            </label>
                        </div>
                        <div id="priceField" class="hidden mt-2">
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Price (RM)</label>
                            <input type="number" name="price" step="0.01" min="0" value="{{ old('price') }}"
                                   class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-[#C4A840] outline-none text-sm">
                        </div>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-100 pt-6 flex justify-end gap-3">
                <a href="{{ route('submissions.index') }}" class="px-5 py-2.5 text-sm font-semibold text-gray-500 hover:text-gray-700 transition-colors">Cancel</a>
                <button type="submit" class="px-6 py-2.5 bg-[#C4A840] hover:bg-[#7B6B35] text-white text-sm font-semibold rounded-lg transition-colors">
                    Submit for Review
                </button>
            </div>
        </form>
    </div>
</div>
@endsection