@extends('submissions::layout')

@section('title', 'Your Articles')

@section('content')
<div class="max-w-3xl mx-auto py-10 px-4">
    <a href="{{ route('public.articles') }}" class="inline-flex items-center gap-1 text-sm text-[#7B6B35] hover:text-[#C4A840] font-semibold mb-6 transition-colors">
        <i class="bi bi-arrow-left"></i> Back to Articles
    </a>

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-[#2C2416]">Your Articles</h1>
        <div class="flex gap-2">
            <a href="{{ route('public.articles') }}" class="px-4 py-2 bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 text-sm font-semibold rounded-lg transition-colors">
                <i class="bi bi-journal-text"></i> Browse Articles
            </a>
            <a href="{{ route('submissions.create') }}" class="px-4 py-2 bg-[#C4A840] hover:bg-[#7B6B35] text-white text-sm font-semibold rounded-lg transition-colors">
                <i class="bi bi-plus-lg"></i> Publish an Article
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-50 text-green-700 text-sm rounded-lg">{{ session('success') }}</div>
    @endif  

    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($submissions as $submission)
            @include('partials.article-card', [
                'article' => $submission,
                'url' => route('submissions.show', $submission),
                'showStatus' => true,
            ])
        @empty
            <div class="col-span-full p-12 text-center text-gray-400 bg-white rounded-2xl border border-gray-100">
                No submissions yet.
            </div>
        @endforelse
    </div>
</div>
@endsection