@extends('layouts.app')

@section('content')
    @include('partials.navbar')
    @include('partials.werta-ui')
    @php
        use Articles\Support\MockBilling as MB;
        use Submissions\Models\Submission;
        $mode = old('submit_mode', 'write');
        $access = old('access_type', 'free');
    @endphp

    <div class="wt-page wt-page--narrow">
        <a href="{{ route('submissions.index') }}" class="wt-back"><i class="bi bi-arrow-left"></i> {{ __('Back to Your Articles') }}</a>

        <div class="wt-header">
            <div>
                <p class="wt-eyebrow">{{ __('Author studio') }}</p>
                <h1 class="wt-title">{{ __('Manuscript Submission') }}</h1>
                <p class="wt-subtitle">{{ __('Submit your work for editorial review. All submissions are reviewed before publication.') }}</p>
            </div>
        </div>

        @include('partials.flash')

        <form method="POST" action="{{ route('submissions.store') }}" enctype="multipart/form-data" class="wt-card" style="padding: 2rem;">
            @csrf

            {{-- Section: Manuscript Details --}}
            <h2 class="form-section-title">{{ __('Manuscript Details') }}</h2>
            <div class="mb-3">
                <label class="wt-label" for="title">{{ __('Title') }} <span style="color:#a33;">*</span></label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" required class="wt-input">
            </div>
            <div class="mb-3">
                <label class="wt-label" for="category">{{ __('Category') }} <span style="color:#a33;">*</span></label>
                <select id="category" name="category" required class="wt-select">
                    <option value="" disabled @selected(!old('category'))>{{ __('Choose a category') }}</option>
                    @foreach(Submission::CATEGORIES as $key => $cat)
                        <option value="{{ $key }}" @selected(old('category') === $key)>{{ __($cat['label']) }}</option>
                    @endforeach
                </select>
                <div class="wt-help">{{ __('Readers filter articles by category. The price is the same for every category.') }}</div>
            </div>
            <div class="mb-3">
                <label class="wt-label" for="abstract">{{ __('Abstract') }}</label>
                <textarea id="abstract" name="abstract" rows="3" class="wt-textarea" placeholder="{{ __('A summary readers will see before opening the full paper. Strongly recommended for uploaded papers.') }}">{{ old('abstract') }}</textarea>
                <div class="wt-help">{{ __('The abstract and the first part of your paper are shown free as a preview.') }}</div>
            </div>

            <label class="wt-label">{{ __('How would you like to submit?') }}</label>
            <div class="choice-row mb-2">
                <label class="choice">
                    <input type="radio" name="submit_mode" value="write" @checked($mode === 'write') onclick="document.getElementById('writeField').hidden = false; document.getElementById('uploadField').hidden = true;">
                    <span><i class="bi bi-pencil-square"></i> {{ __('Write directly') }}</span>
                </label>
                <label class="choice">
                    <input type="radio" name="submit_mode" value="upload" @checked($mode === 'upload') onclick="document.getElementById('writeField').hidden = true; document.getElementById('uploadField').hidden = false;">
                    <span><i class="bi bi-file-earmark-pdf"></i> {{ __('Upload a paper (PDF/DOCX)') }}</span>
                </label>
            </div>
            <div id="writeField" @if($mode !== 'write') hidden @endif>
                <textarea name="content" rows="12" class="wt-textarea" style="font-family: ui-monospace, Menlo, Consolas, monospace;" placeholder="{{ __('Write your article or manuscript here...') }}" aria-label="{{ __('Content') }}">{{ old('content') }}</textarea>
            </div>
            <div id="uploadField" @if($mode !== 'upload') hidden @endif>
                <input type="file" name="paper_file" accept=".pdf,.doc,.docx" class="wt-input" aria-label="{{ __('Paper file') }}">
                <div class="wt-help">{{ __('PDF or Word document, max 10MB.') }}</div>
            </div>

            {{-- Section: Publication Options --}}
            <h2 class="form-section-title mt-4 pt-3" style="border-top: 1px solid rgba(123,107,53,.12);">{{ __('Publication Options') }}</h2>
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="wt-label">{{ __('Visibility') }}</label>
                    <label class="option-card">
                        <input type="radio" name="visibility" value="public" @checked(old('visibility', 'public') === 'public')>
                        <span><strong>{{ __('Public') }}</strong><small>{{ __('Visible to all readers once approved') }}</small></span>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="visibility" value="private" @checked(old('visibility') === 'private')>
                        <span><strong>{{ __('Private') }}</strong><small>{{ __('Only accessible via direct link') }}</small></span>
                    </label>
                </div>
                <div class="col-md-6">
                    <label class="wt-label">{{ __('Access') }}</label>
                    <label class="option-card">
                        <input type="radio" name="access_type" value="free" @checked($access === 'free') onclick="document.getElementById('premiumNote').hidden = true">
                        <span><strong>{{ __('Free') }}</strong><small>{{ __('Anyone can read it') }}</small></span>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="access_type" value="paid" @checked($access === 'paid') onclick="document.getElementById('premiumNote').hidden = false">
                        <span><strong>{{ __('Premium') }} · {{ MB::rm(MB::articlePrice()) }}</strong><small>{{ __('Fixed price for every premium paper') }}</small></span>
                    </label>
                </div>
            </div>

            <div id="premiumNote" class="wt-alert wt-alert--info mt-3 mb-0" @if($access !== 'paid') hidden @endif>
                <i class="bi bi-star-fill" style="color: var(--gold);"></i>
                <div>
                    <strong>{{ __('Premium paper') }}</strong>
                    <ul class="mb-0 ps-3 mt-1 wt-small">
                        <li>{{ __('Every premium paper sells for :price (:n tokens), in any category.', ['price' => MB::rm(MB::articlePrice()), 'n' => MB::ARTICLE_TOKENS]) }}</li>
                        <li>{{ __('You earn :rm for every copy bought or gifted.', ['rm' => MB::rm(MB::AUTHOR_PER_COPY)]) }}</li>
                        <li>{{ __('A :fee publishing fee is charged only after your paper is accepted.', ['fee' => MB::rm(MB::PUBLISH_FEE)]) }}</li>
                        <li>{{ __('Reads by subscribers earn you nothing.') }}</li>
                        <li>{{ __('Reviewers may recommend publishing it free instead.') }}</li>
                    </ul>
                </div>
            </div>

            <p class="wt-help mt-3">{{ __('These can be changed later from your article\'s settings, even after publication.') }}</p>

            <div class="d-flex justify-content-end gap-2 mt-4 pt-3" style="border-top: 1px solid rgba(123,107,53,.12);">
                <a href="{{ route('submissions.index') }}" class="wt-btn wt-btn--ghost">{{ __('Cancel') }}</a>
                <button type="submit" class="wt-btn wt-btn--gold">{{ __('Submit for Review') }}</button>
            </div>
        </form>
    </div>

    @include('partials.footer')

    <style>
        .form-section-title { font-size: var(--text-xs); font-weight: 700; letter-spacing: 2px; text-transform: uppercase; color: var(--gold); margin-bottom: 1rem; }
        .choice-row { display: flex; flex-wrap: wrap; gap: 1.25rem; font-size: var(--text-base); }
        .choice { display: inline-flex; align-items: center; gap: .45rem; cursor: pointer; }
        .choice input { accent-color: var(--primary); }
        .option-card { display: flex; gap: .7rem; align-items: flex-start; padding: .8rem 1rem; border-radius: var(--radius-md); box-shadow: inset 0 0 0 1px rgba(123,107,53,.25); cursor: pointer; margin-bottom: .6rem; transition: box-shadow .2s, background .2s; }
        .option-card:hover { box-shadow: inset 0 0 0 1px var(--primary); }
        .option-card:has(input:checked) { box-shadow: inset 0 0 0 2px var(--gold); background: var(--cream-light); }
        .option-card input { margin-top: .25rem; accent-color: var(--primary); }
        .option-card strong { display: block; font-size: var(--text-base); color: var(--dark); }
        .option-card small { display: block; font-size: var(--text-sm); color: var(--muted); }
    </style>
@endsection
