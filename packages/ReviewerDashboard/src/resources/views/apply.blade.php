@extends('layouts.app')

@section('content')
    @include('partials.navbar')
    @include('partials.werta-ui')
    @php use Submissions\Models\Submission; @endphp

    <div class="wt-page">
        <div class="wt-header">
            <div>
                <p class="wt-eyebrow">{{ __('Reviewer programme') }}</p>
                <h1 class="wt-title">{{ __('Review for Werta') }}</h1>
                <p class="wt-subtitle">{{ __('Werta articles are read by students and families. Every paper is checked by a verified professional before it is published or sold.') }}</p>
            </div>
            <a href="{{ route('reviewer.login') }}" class="wt-btn wt-btn--outline"><i class="bi bi-box-arrow-in-right"></i> {{ __('Reviewer sign in') }}</a>
        </div>

        @include('partials.flash')

        <div class="wt-grid apply-grid">
            <div class="wt-stack">
                <div class="wt-card wt-card--cream">
                    <h2 class="wt-section-title" style="font-size: var(--text-xl);">{{ __('What reviewers do') }}</h2>
                    <ul class="apply-list">
                        <li><i class="bi bi-clipboard-check"></i> {{ __('Score each manuscript on accuracy, originality, clarity, relevance and ethics.') }}</li>
                        <li><i class="bi bi-coin"></i> {{ __('Say whether a premium paper is good enough to sell, or better published free.') }}</li>
                        <li><i class="bi bi-chat-left-text"></i> {{ __('Give authors clear, kind feedback and flag safety concerns to editors.') }}</li>
                        <li><i class="bi bi-patch-check"></i> {{ __('Optionally appear on the article as a named reviewer.') }}</li>
                    </ul>
                </div>
                <div class="wt-card wt-card--flat">
                    <h2 class="wt-section-title" style="font-size: var(--text-xl);">{{ __('Who can apply') }}</h2>
                    <p class="wt-small wt-muted mb-2">{{ __('Psychologists, counsellors, doctors, teachers and researchers with a recognised qualification in their field. A professional registration number speeds up verification.') }}</p>
                    <p class="wt-small wt-muted mb-0">{{ __('Proposed honorarium: RM 40 per completed review.') }}</p>
                </div>
            </div>

            <form method="POST" action="{{ route('reviewer.apply.store') }}" enctype="multipart/form-data" class="wt-card" style="padding: 2rem;">
                @csrf
                <h2 class="wt-section-title" style="font-size: var(--text-xl);">{{ __('Apply to review') }}</h2>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="wt-label" for="name">{{ __('Full name') }} *</label>
                        <input id="name" name="name" value="{{ old('name') }}" class="wt-input" required>
                    </div>
                    <div class="col-md-6">
                        <label class="wt-label" for="email">{{ __('Email') }} *</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" class="wt-input" required>
                    </div>
                    <div class="col-md-6">
                        <label class="wt-label" for="title">{{ __('Professional title') }} *</label>
                        <input id="title" name="title" value="{{ old('title') }}" class="wt-input" placeholder="{{ __('e.g. Clinical Psychologist') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="wt-label" for="field">{{ __('Field you will review') }} *</label>
                        <select id="field" name="field" class="wt-select" required>
                            @foreach(Submission::CATEGORIES as $key => $cat)
                                <option value="{{ $key }}" @selected(old('field') === $key)>{{ __($cat['label']) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="wt-label" for="qualifications">{{ __('Highest qualification') }} *</label>
                        <input id="qualifications" name="qualifications" value="{{ old('qualifications') }}" class="wt-input" placeholder="{{ __('e.g. PhD Clinical Psychology') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="wt-label" for="institution">{{ __('Institution or practice') }}</label>
                        <input id="institution" name="institution" value="{{ old('institution') }}" class="wt-input">
                    </div>
                    <div class="col-md-6">
                        <label class="wt-label" for="registration_no">{{ __('Professional registration no.') }}</label>
                        <input id="registration_no" name="registration_no" value="{{ old('registration_no') }}" class="wt-input">
                    </div>
                    <div class="col-md-6">
                        <label class="wt-label" for="proof">{{ __('Proof of credentials') }}</label>
                        <input id="proof" type="file" name="proof" accept=".pdf,.jpg,.jpeg,.png" class="wt-input">
                        <div class="wt-help">{{ __('PDF or image, max 5MB. Only admins can see it.') }}</div>
                    </div>
                    <div class="col-12">
                        <label class="wt-label" for="bio">{{ __('Short bio') }}</label>
                        <textarea id="bio" name="bio" rows="3" class="wt-textarea">{{ old('bio') }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="d-flex gap-2 align-items-start wt-small">
                            <input type="checkbox" name="agree" value="1" @checked(old('agree')) style="margin-top: .2rem; accent-color: var(--primary);" required>
                            <span>{{ __('I will review fairly, declare any conflict of interest, and keep manuscripts confidential.') }}</span>
                        </label>
                    </div>
                </div>
                <div class="d-flex justify-content-end mt-4">
                    <button class="wt-btn wt-btn--gold">{{ __('Submit application') }}</button>
                </div>
            </form>
        </div>
    </div>

    @include('partials.footer')

    <style>
        .apply-grid { grid-template-columns: 1fr; }
        @media (min-width: 992px) { .apply-grid { grid-template-columns: 1fr 1.6fr; align-items: start; } }
        .apply-list { list-style: none; padding: 0; margin: 0; }
        .apply-list li { display: flex; gap: .7rem; margin-bottom: .8rem; font-size: var(--text-base); }
        .apply-list i { color: var(--gold); font-size: 1.1rem; }
    </style>
@endsection
