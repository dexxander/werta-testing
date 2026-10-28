@extends('reviewerdashboard::layout')

@section('title', $review->submission->title)

@section('content')
@php
    use Submissions\Models\SubmissionReview;
    $s = $review->submission;
    $done = $review->status !== 'assigned';
    $paid = $s->access_type === 'paid';
    $criteriaHelp = [
        'score_accuracy'    => __('Claims are correct and supported by evidence or recognised practice.'),
        'score_originality' => __('Offers something new or useful beyond common advice.'),
        'score_clarity'     => __('Well organised and easy for students and parents to follow.'),
        'score_relevance'   => __('Matters to Werta readers in Malaysia today.'),
        'score_ethics'      => __('Safe, respectful, no identifying details, and points to help where needed.'),
    ];
@endphp
<div>
    <a href="{{ route('reviewer.dashboard') }}" class="inline-flex items-center gap-1 text-sm text-primary hover:text-gold font-semibold mb-5 transition-colors"><i class="bi bi-arrow-left"></i> {{ __('Back to review queue') }}</a>

    <div class="grid grid-cols-1 xl:grid-cols-5 gap-6">
        {{-- Manuscript --}}
        <div class="xl:col-span-3 bg-white rounded-2xl p-6 sm:p-8 card-ring">
            <div class="flex flex-wrap gap-2 mb-3">
                @if($s->category_label)<span class="text-[10px] font-bold uppercase tracking-wider px-2 py-1 rounded-full bg-gold/15 text-primary-dark"><i class="bi {{ $s->category_icon }}"></i> {{ __($s->category_label) }}</span>@endif
                <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-1 rounded-full {{ $paid ? 'bg-dark text-gold' : 'bg-gray-100 text-gray-600' }}">{{ $paid ? __('Author requested: Premium') : __('Author requested: Free') }}</span>
            </div>
            <h1 class="font-display text-3xl text-dark leading-tight">{{ $s->title }}</h1>
            <p class="text-xs text-gray-400 mt-2"><i class="bi bi-incognito"></i> {{ __('Author hidden for blind review') }} · {{ __('Submitted :date', ['date' => $s->submitted_at->translatedFormat('d M Y')]) }}</p>

            @if($s->abstract)
                <div class="mt-6 p-4 rounded-xl bg-cream-light border-l-4 border-gold">
                    <div class="text-[10px] font-bold uppercase tracking-[2px] text-primary mb-1">{{ __('Abstract') }}</div>
                    <p class="text-sm text-gray-600 italic leading-relaxed">{{ $s->abstract }}</p>
                </div>
            @endif

            <div class="mt-6">
                @if($s->file_path)
                    <div class="flex justify-between items-center mb-3">
                        <div class="text-[10px] font-bold uppercase tracking-[2px] text-primary">{{ __('Manuscript') }}</div>
                        <a href="{{ asset('storage/' . $s->file_path) }}" target="_blank" class="text-sm font-semibold text-primary hover:text-primary-dark"><i class="bi bi-box-arrow-up-right"></i> {{ __('Open in new tab') }}</a>
                    </div>
                    <iframe src="{{ asset('storage/' . $s->file_path) }}" class="w-full rounded-lg ring-1 ring-primary-dark/20" style="height: 720px;" title="{{ __('Manuscript') }}"></iframe>
                @else
                    <div class="text-[10px] font-bold uppercase tracking-[2px] text-primary mb-2">{{ __('Manuscript') }}</div>
                    <div class="manuscript text-[15px] leading-8 text-dark/90">{{ \Submissions\Support\ContentRenderer::html($s->content) }}</div>
                @endif
            </div>
        </div>

        {{-- Evaluation --}}
        <div class="xl:col-span-2">
            <div class="bg-white rounded-2xl p-6 card-ring xl:sticky xl:top-0">
                <h2 class="font-display text-2xl text-dark">{{ __('Your evaluation') }}</h2>
                @if($done)
                    <p class="text-sm text-gray-500 mb-4">{{ $review->status === 'declined' ? __('You declined this review.') : __('Submitted :date. Thank you.', ['date' => optional($review->submitted_at)->translatedFormat('d M Y')]) }}</p>
                    @if($review->status === 'completed')
                        <div class="space-y-2 text-sm">
                            @foreach(SubmissionReview::CRITERIA as $col => $label)
                                <div class="flex justify-between border-b border-dashed border-gold/30 py-1"><span class="text-gray-500">{{ __($label) }}</span><strong>{{ $review->{$col} }}/5</strong></div>
                            @endforeach
                            <div class="flex justify-between py-1"><span class="text-gray-500">{{ __('Recommendation') }}</span><strong>{{ __($review->recommendationLabel()) }}</strong></div>
                            @if($review->suitable_for_sale !== null)
                                <div class="flex justify-between py-1"><span class="text-gray-500">{{ __('Sale verdict') }}</span><strong>{{ $review->suitable_for_sale ? __('Sell as premium') : __('Publish free') }}</strong></div>
                            @endif
                            <div class="pt-2"><div class="text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-1">{{ __('Comments to the author') }}</div><p>{{ $review->comments }}</p></div>
                            @if($review->suggestions)<div><div class="text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-1">{{ __('Requested changes') }}</div><p>{{ $review->suggestions }}</p></div>@endif
                        </div>
                    @endif
                @else
                    <p class="text-sm text-gray-500 mb-5">{{ __('Due :date. Scores: 1 = poor, 5 = excellent.', ['date' => optional($review->due_at)->translatedFormat('d M Y')]) }}</p>
                    <form method="POST" action="{{ route('reviewer.reviews.submit', $review) }}" class="space-y-5">
                        @csrf
                        @foreach(SubmissionReview::CRITERIA as $col => $label)
                            <fieldset>
                                <legend class="text-sm font-semibold text-dark">{{ __($label) }}</legend>
                                <p class="text-xs text-gray-400 mb-2">{{ $criteriaHelp[$col] }}</p>
                                <div class="grid grid-cols-5 gap-1.5">
                                    @for($n = 1; $n <= 5; $n++)
                                        <label class="cursor-pointer">
                                            <input type="radio" name="{{ $col }}" value="{{ $n }}" class="peer sr-only" @checked((int) old($col) === $n) required>
                                            <span class="block text-center py-2 rounded-lg ring-1 ring-primary/25 text-sm font-semibold text-gray-600 peer-checked:bg-primary-dark peer-checked:text-white peer-checked:ring-primary-dark hover:ring-primary transition">{{ $n }}</span>
                                        </label>
                                    @endfor
                                </div>
                            </fieldset>
                        @endforeach

                        <fieldset>
                            <legend class="text-sm font-semibold text-dark mb-2">{{ __('Recommendation') }}</legend>
                            <div class="grid grid-cols-2 gap-2">
                                @foreach(SubmissionReview::RECOMMENDATIONS as $value => $label)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="recommendation" value="{{ $value }}" class="peer sr-only" @checked(old('recommendation') === $value) required>
                                        <span class="block text-center py-2 px-2 rounded-lg ring-1 ring-primary/25 text-sm font-semibold text-gray-600 peer-checked:bg-gold peer-checked:text-dark peer-checked:ring-gold hover:ring-primary transition">{{ __($label) }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </fieldset>

                        @if($paid)
                            <fieldset class="p-4 rounded-xl bg-cream">
                                <legend class="text-sm font-semibold text-dark"><i class="bi bi-coin text-gold"></i> {{ __('Is it suitable to sell?') }}</legend>
                                <p class="text-xs text-gray-500 mb-2">{{ __('Premium papers sell for RM 5. Ask whether readers would pay for this, or whether it is better offered free.') }}</p>
                                <label class="flex items-center gap-2 text-sm mb-1"><input type="radio" name="suitable_for_sale" value="1" @checked(old('suitable_for_sale') === '1') class="accent-[#7B6B35]" required> {{ __('Yes, sell it as premium') }}</label>
                                <label class="flex items-center gap-2 text-sm"><input type="radio" name="suitable_for_sale" value="0" @checked(old('suitable_for_sale') === '0') class="accent-[#7B6B35]"> {{ __('No, publish it free') }}</label>
                            </fieldset>
                        @endif

                        <div>
                            <label for="comments" class="text-sm font-semibold text-dark">{{ __('Comments to the author') }} *</label>
                            <textarea id="comments" name="comments" rows="4" required class="mt-1 w-full px-3 py-2 rounded-lg border border-gray-200 focus:border-gold focus:ring-1 focus:ring-gold outline-none text-sm">{{ old('comments') }}</textarea>
                        </div>
                        <div>
                            <label for="suggestions" class="text-sm font-semibold text-dark">{{ __('Requested changes') }}</label>
                            <textarea id="suggestions" name="suggestions" rows="3" class="mt-1 w-full px-3 py-2 rounded-lg border border-gray-200 focus:border-gold focus:ring-1 focus:ring-gold outline-none text-sm">{{ old('suggestions') }}</textarea>
                        </div>
                        <div>
                            <label for="confidential_notes" class="text-sm font-semibold text-dark">{{ __('Confidential note to the editor') }}</label>
                            <textarea id="confidential_notes" name="confidential_notes" rows="2" class="mt-1 w-full px-3 py-2 rounded-lg border border-gray-200 focus:border-gold focus:ring-1 focus:ring-gold outline-none text-sm" placeholder="{{ __('Not shown to the author') }}">{{ old('confidential_notes') }}</textarea>
                        </div>

                        <div class="space-y-2 text-sm">
                            <label class="flex items-start gap-2"><input type="checkbox" name="no_conflict" value="1" class="mt-1 accent-[#7B6B35]" required @checked(old('no_conflict'))> <span>{{ __('I have no conflict of interest with this manuscript or its author.') }}</span></label>
                            <label class="flex items-start gap-2"><input type="hidden" name="show_on_article" value="0"><input type="checkbox" name="show_on_article" value="1" class="mt-1 accent-[#7B6B35]" @checked(old('show_on_article', '1') === '1')> <span>{{ __('Show my name on the article as a reviewer if it is published.') }}</span></label>
                        </div>

                        <button type="submit" class="w-full bg-gold hover:bg-primary text-dark hover:text-white font-semibold py-2.5 rounded-lg transition-colors shadow-sm">{{ __('Submit evaluation') }}</button>
                    </form>

                    <form method="POST" action="{{ route('reviewer.reviews.decline', $review) }}" class="mt-4 pt-4 border-t border-gray-100" onsubmit="return confirm(WertaI18n.t('Decline this review?'))">
                        @csrf
                        <input type="hidden" name="conflict" value="1">
                        <button class="text-xs font-semibold text-red-600 hover:underline"><i class="bi bi-x-circle"></i> {{ __('Decline (conflict of interest or outside my expertise)') }}</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
