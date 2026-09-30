@extends('submissions::layout')

@section('title', $submission->title)

@section('content')
<div class="max-w-3xl mx-auto py-10 px-4">
    <a href="{{ route('submissions.index') }}" class="inline-flex items-center gap-1 text-sm text-[#7B6B35] hover:text-[#C4A840] font-semibold mb-6 transition-colors">
        <i class="bi bi-arrow-left"></i> Back to Your Articles
    </a>

    <div class="bg-white rounded-2xl border border-[#C4A840]/20 shadow-sm p-8">
        <div class="flex justify-between items-start mb-6 pb-6 border-b border-gray-100">
            <div>
                <h1 class="text-2xl font-bold text-[#2C2416]">{{ $submission->title }}</h1>
                <p class="text-xs text-gray-400 mt-1">Submitted {{ $submission->submitted_at->format('M d, Y') }}</p>
            </div>
            <span class="px-2.5 py-1 bg-gray-100 text-gray-600 text-xs font-bold uppercase rounded-md shrink-0">
                {{ str_replace('_', ' ', $submission->status) }}
            </span>
        </div>

        <div class="grid sm:grid-cols-3 gap-4 mb-6 text-sm">
            <div class="p-3 bg-gray-50 rounded-lg">
                <div class="text-xs text-gray-400 font-semibold uppercase mb-1">Visibility</div>
                <div class="font-semibold text-gray-700 capitalize">{{ $submission->visibility }}</div>
            </div>
            <div class="p-3 bg-gray-50 rounded-lg">
                <div class="text-xs text-gray-400 font-semibold uppercase mb-1">Access</div>
                <div class="font-semibold text-gray-700 capitalize">{{ $submission->access_type }}</div>
            </div>
            @if($submission->access_type === 'paid')
                <div class="p-3 bg-gray-50 rounded-lg">
                    <div class="text-xs text-gray-400 font-semibold uppercase mb-1">Price</div>
                    <div class="font-semibold text-gray-700">RM {{ number_format($submission->price, 2) }}</div>
                </div>
            @endif
        </div>

        @if($submission->abstract)
            <div class="mb-6">
                <h2 class="text-xs font-bold uppercase tracking-wider text-[#C4A840] mb-2">Abstract</h2>
                <p class="text-sm text-gray-600 leading-relaxed">{{ $submission->abstract }}</p>
            </div>
        @endif

        <div class="mb-6">
            <h2 class="text-xs font-bold uppercase tracking-wider text-[#C4A840] mb-2">Content</h2>
            <p class="text-sm text-gray-600 leading-relaxed whitespace-pre-line">{{ $submission->content }}</p>
        </div>

        {{-- TODO: once the review phase is built, list $submission->reviews here
             (reviewer name, comments, suggestions, recommendation, score) --}}
        @if($submission->status !== 'submitted')
            <div class="border-t border-gray-100 pt-6">
                <h2 class="text-xs font-bold uppercase tracking-wider text-[#C4A840] mb-2">Editorial Feedback</h2>
                <p class="text-sm text-gray-400">Reviewer feedback will appear here once available.</p>
            </div>
        @endif
    </div>
</div>
@endsection