@extends('layouts.app')

@section('content')
    @include('partials.navbar')
    @include('partials.werta-ui')
    @php
        use Articles\Support\MockBilling as MB;
        use Submissions\Models\SubmissionReview;
        $paid = $submission->access_type === 'paid';
    @endphp

    <div class="wt-page wt-page--narrow">
        <a href="{{ route('submissions.index') }}" class="wt-back"><i class="bi bi-arrow-left"></i> {{ __('Back to Your Articles') }}</a>

        @include('partials.flash')

        <div class="wt-card wt-mb" style="padding: 2rem;">
            <div class="d-flex flex-wrap justify-content-between gap-3 align-items-start">
                <div class="min-w-0">
                    <p class="wt-eyebrow mb-1">{{ $submission->category_label ? __($submission->category_label) : __('Manuscript') }}</p>
                    <h1 class="wt-title" style="font-size: var(--text-3xl);">{{ $submission->title }}</h1>
                    <p class="wt-small wt-muted mb-0">{{ __('Submitted') }} {{ $submission->submitted_at->translatedFormat('d M Y') }}</p>
                </div>
                @include('submissions::partials.status', ['submission' => $submission, 'showSteps' => false])
            </div>
            <div class="mt-3">
                @include('submissions::partials.status', ['submission' => $submission, 'showBadge' => false])
            </div>

            <div class="wt-grid wt-grid-4 mt-4">
                <div class="detail-tile"><div class="wt-card-label">{{ __('Visibility') }}</div><strong>{{ __(ucfirst($submission->visibility)) }}</strong></div>
                <div class="detail-tile"><div class="wt-card-label">{{ __('Access') }}</div><strong>{{ $paid ? __('Premium') : __('Free') }}</strong></div>
                @if($paid)
                    <div class="detail-tile"><div class="wt-card-label">{{ __('Price') }}</div><strong>{{ MB::rm(MB::articlePrice()) }}</strong></div>
                    <div class="detail-tile"><div class="wt-card-label">{{ __('Copies sold') }}</div><strong>{{ $copies }} · {{ MB::rm($copies * MB::AUTHOR_PER_COPY) }}</strong></div>
                @endif
            </div>
        </div>

        {{-- Next step --}}
        @if($submission->status === 'accepted' && $paid)
            <div class="wt-card wt-card--feature wt-mb d-flex flex-wrap gap-3 align-items-center justify-content-between">
                <div>
                    <div class="wt-card-label">{{ __('Next step') }}</div>
                    <div class="fw-bold">{{ __('Your paper was accepted. Pay the :fee publishing fee to put it on sale.', ['fee' => MB::rm(MB::PUBLISH_FEE)]) }}</div>
                    <div class="wt-small wt-muted">{{ __('You earn :rm per copy, so the fee is recovered after :n copies.', ['rm' => MB::rm(MB::AUTHOR_PER_COPY), 'n' => MB::breakEvenCopies()]) }}</div>
                </div>
                <form method="POST" action="{{ route('submissions.pay-fee', $submission) }}">@csrf
                    <button class="wt-btn wt-btn--gold"><i class="bi bi-credit-card"></i> {{ __('Pay :fee (demo)', ['fee' => MB::rm(MB::PUBLISH_FEE)]) }}</button>
                </form>
            </div>
        @elseif($submission->status === 'accepted')
            <div class="wt-alert wt-alert--info"><i class="bi bi-hourglass-split"></i> {{ __('Accepted as a free article. An editor will publish it shortly.') }}</div>
        @elseif($submission->status === 'published' && $submission->visibility === 'public')
            <div class="wt-alert wt-alert--ok"><i class="bi bi-check-circle-fill"></i> <span>{{ __('This paper is live.') }} <a href="{{ route('public.articles.show', $submission->slug) }}">{{ __('View the public page') }}</a></span></div>
        @endif

        @if($submission->abstract)
            <div class="wt-card wt-card--flat wt-mb">
                <div class="wt-card-label">{{ __('Abstract') }}</div>
                <p class="mb-0">{{ $submission->abstract }}</p>
            </div>
        @endif

        <div class="wt-card wt-card--flat wt-mb">
            <div class="wt-card-label">{{ __('Content') }}</div>
            @if($submission->file_path)
                <a href="{{ asset('storage/' . $submission->file_path) }}" target="_blank" class="wt-btn wt-btn--outline wt-btn--sm"><i class="bi bi-file-earmark-pdf"></i> {{ __('Open uploaded paper') }}</a>
            @else
                <p class="mb-0" style="white-space: pre-line;">{{ $submission->content }}</p>
            @endif
        </div>

        {{-- Editorial feedback --}}
        <h2 class="wt-section-title">{{ __('Editorial Feedback') }}</h2>
        @if($submission->decision_notes)
            <div class="wt-card wt-card--cream wt-mb">
                <div class="wt-card-label">{{ __('Editor\'s decision note') }}</div>
                <p class="mb-0">{{ $submission->decision_notes }}</p>
            </div>
        @endif

        @forelse($reviews as $i => $review)
            <div class="wt-card wt-card--flat mb-3">
                <div class="d-flex flex-wrap justify-content-between gap-2 align-items-center mb-2">
                    <div class="fw-bold">{{ __('Reviewer :n', ['n' => $i + 1]) }} <span class="wt-muted wt-small">· {{ $review->reviewer->title }}</span></div>
                    @if($review->recommendation)
                        <span class="wt-badge {{ in_array($review->recommendation, ['accept', 'minor_revision']) ? 'wt-badge--green' : ($review->recommendation === 'reject' ? 'wt-badge--red' : 'wt-badge--yellow') }}">{{ __($review->recommendationLabel()) }}</span>
                    @endif
                </div>
                <div class="score-grid mb-2">
                    @foreach(SubmissionReview::CRITERIA as $col => $label)
                        @if($review->{$col})
                            <div class="score-item"><span>{{ __($label) }}</span><strong>{{ $review->{$col} }}/5</strong></div>
                        @endif
                    @endforeach
                </div>
                @if($review->comments)<p class="mb-2">{{ $review->comments }}</p>@endif
                @if($review->suggestions)
                    <div class="wt-small"><strong>{{ __('Requested changes') }}:</strong> {{ $review->suggestions }}</div>
                @endif
                @if($paid && $review->suitable_for_sale !== null)
                    <div class="wt-small wt-muted mt-2"><i class="bi bi-coin"></i> {{ $review->suitable_for_sale ? __('Reviewer view: suitable to sell as premium.') : __('Reviewer view: better published free.') }}</div>
                @endif
            </div>
        @empty
            <div class="wt-card wt-card--flat wt-empty">
                <i class="bi bi-clipboard-check"></i>
                @if($pendingReviews > 0)
                    {{ trans_choice(':count reviewer is evaluating your paper.|:count reviewers are evaluating your paper.', $pendingReviews, ['count' => $pendingReviews]) }}
                @else
                    {{ __('Reviewer feedback will appear here once available.') }}
                @endif
            </div>
        @endforelse
    </div>

    @include('partials.footer')

    <style>
        .detail-tile { background: var(--cream-light); border-radius: var(--radius-md); padding: .8rem 1rem; }
        .score-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: .4rem 1rem; }
        .score-item { display: flex; justify-content: space-between; font-size: var(--text-sm); color: var(--muted); border-bottom: 1px dashed rgba(123,107,53,.2); padding: .2rem 0; }
        .score-item strong { color: var(--dark); }
    </style>
@endsection
