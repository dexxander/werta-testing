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

    @php
        use Submissions\Models\Submission;
        $statusLabels = Submission::STATUS_LABELS;
    @endphp

    <p class="text-xs text-gray-400 mb-3">{{ $submissions->count() }} {{ Str::plural('article', $submissions->count()) }}</p>

    <div class="bg-white rounded-2xl border border-[#C4A840]/20 shadow-sm divide-y divide-gray-100 overflow-hidden">
        @forelse($submissions as $submission)
            @php $s = $statusLabels[$submission->status] ?? ['label' => $submission->status, 'class' => 'bg-gray-100 text-gray-600']; @endphp

            <a href="{{ route('submissions.show', $submission) }}"
            class="flex items-center gap-4 px-5 py-4 hover:bg-[#F5EFE0]/60 transition-colors group">

                {{-- Initials tile --}}
                <div class="hidden sm:flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#C4A840]/15 text-[#7B6B35] font-bold text-sm">
                    {{ strtoupper(mb_substr($submission->title, 0, 2)) }}
                </div>

                {{-- Title + abstract + meta --}}
                <div class="min-w-0 flex-1">
                    <h3 class="font-bold text-[#2C2416] truncate group-hover:text-[#7B6B35] transition-colors">{{ $submission->title }}</h3>
                    @if($submission->abstract)
                        <p class="text-xs text-gray-500 truncate mt-0.5">{{ $submission->abstract }}</p>
                    @endif
                    <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-2 text-[11px] text-gray-400">
                        <span><i class="bi bi-calendar3"></i> {{ $submission->submitted_at->format('M d, Y') }}</span>
                        <span class="capitalize">
                            <i class="bi {{ $submission->visibility === 'private' ? 'bi-lock' : 'bi-globe2' }}"></i>
                            {{ $submission->visibility }}
                        </span>
                        <span class="capitalize">
                            <i class="bi bi-tag"></i>
                            {{ $submission->access_type }}@if($submission->access_type === 'paid' && $submission->price) · RM {{ number_format($submission->price, 2) }}@endif
                        </span>
                    </div>
                </div>

                {{-- Status + arrow --}}
                <div class="flex items-center gap-3 shrink-0">
                    <span class="px-2.5 py-1 text-[10px] font-bold uppercase rounded-md {{ $s['class'] }}">{{ $s['label'] }}</span>
                    <i class="bi bi-chevron-right text-gray-300 group-hover:text-[#C4A840] transition-colors"></i>
                </div>
            </a>
        @empty
            <div class="p-12 text-center text-gray-400">
                <i class="bi bi-journal-plus text-3xl block mb-2 text-[#C4A840]/60"></i>
                <p class="text-sm mb-3">You haven't submitted anything yet.</p>
                <a href="{{ route('submissions.create') }}" class="inline-block px-4 py-2 bg-[#C4A840] hover:bg-[#7B6B35] text-white text-sm font-semibold rounded-lg transition-colors">
                    Publish your first article
                </a>
            </div>
        @endforelse
    </div>
</div>
@endsection