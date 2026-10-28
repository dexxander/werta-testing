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

    <div class="wt-page">
        <a href="{{ route('submissions.index') }}" class="wt-back"><i class="bi bi-arrow-left"></i> {{ __('Back to Your Articles') }}</a>

        <div class="wt-header">
            <div>
                <p class="wt-eyebrow">{{ __('Author studio') }}</p>
                <h1 class="wt-title">{{ __('Manuscript Submission') }}</h1>
                <p class="wt-subtitle">{{ __('Submit your work for editorial review. All submissions are reviewed before publication.') }}</p>
            </div>
        </div>

        @include('partials.flash')

        <div class="submit-layout">
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
                <textarea id="abstract" name="abstract" rows="3" class="wt-textarea" placeholder="{{ __('150–250 words: the purpose, your approach, the key points and why they matter.') }}">{{ old('abstract') }}</textarea>
                <div class="wt-help">{{ __('The abstract and the first part of your paper are shown free as a preview.') }} <span id="abstractCount" class="word-count"></span></div>
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
                <div class="template-box">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
                        <div>
                            <div class="fw-bold" style="color: var(--dark);"><i class="bi bi-layout-text-sidebar-reverse" style="color: var(--gold);"></i> {{ __('Start from a template') }}</div>
                            <div class="wt-small wt-muted">{{ __('Pick the type of paper. The sections and guidance are filled in for you to replace.') }}</div>
                        </div>
                    </div>
                    <div class="template-options">
                        @foreach($templates as $key => $tpl)
                            <label class="template-option">
                                <input type="radio" name="template_type" value="{{ $key }}" @checked(old('template_type', 'research') === $key)>
                                <span>
                                    <i class="bi {{ $tpl['icon'] }}"></i>
                                    <strong>{{ $tpl['label'] }}</strong>
                                    <small>{{ $tpl['desc'] }}</small>
                                    <small class="template-words">{{ $tpl['words'] }}</small>
                                </span>
                            </label>
                        @endforeach
                    </div>
                    <button type="button" class="wt-btn wt-btn--outline wt-btn--sm mt-2" id="insertTemplate"><i class="bi bi-file-earmark-plus"></i> {{ __('Insert template') }}</button>
                    @foreach($templates as $key => $tpl)
                        <template id="tpl-{{ $key }}">{{ \Submissions\Support\ManuscriptTemplates::text($key) }}</template>
                    @endforeach
                </div>

                <textarea id="contentField" name="content" rows="18" class="wt-textarea manuscript-input" placeholder="{{ __('Write your article or manuscript here...') }}" aria-label="{{ __('Content') }}">{{ old('content') }}</textarea>
                <div class="d-flex flex-wrap justify-content-between gap-2 mt-1">
                    <div class="wt-help mt-0"><i class="bi bi-type"></i> {{ __('Start a line with ## for a section heading and - for a bullet point. Leave a blank line between paragraphs.') }}</div>
                    <div class="wt-help mt-0"><span id="contentCount" class="word-count"></span></div>
                </div>
            </div>
            <div id="uploadField" @if($mode !== 'upload') hidden @endif>
                <div class="template-box d-flex flex-wrap gap-3 align-items-center justify-content-between">
                    <div>
                        <div class="fw-bold" style="color: var(--dark);"><i class="bi bi-file-earmark-word" style="color: var(--gold);"></i> {{ __('Use the Werta manuscript template') }}</div>
                        <div class="wt-small wt-muted">{{ __('Keep the headings, replace the guidance text, then export to PDF or upload the Word file.') }}</div>
                    </div>
                    <a href="{{ asset('downloads/werta-manuscript-template.docx') }}" class="wt-btn wt-btn--outline wt-btn--sm" download><i class="bi bi-download"></i> {{ __('Download template (.docx)') }}</a>
                </div>
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

        {{-- Author guidelines, as on journal submission sites --}}
        <aside class="submit-aside">
            <div class="wt-card wt-card--cream">
                <p class="wt-eyebrow mb-1">{{ __('Guide for authors') }}</p>
                <h2 class="wt-section-title" style="font-size: var(--text-xl);">{{ __('Before you submit') }}</h2>
                <ul class="guideline-list">
                    @foreach($guidelines as [$icon, $title, $text])
                        <li>
                            <i class="bi {{ $icon }}"></i>
                            <div><strong>{{ $title }}</strong><span>{{ $text }}</span></div>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="wt-card wt-card--flat mt-3">
                <div class="wt-card-label">{{ __('What happens next') }}</div>
                <ol class="next-steps">
                    <li>{{ __('Two verified reviewers evaluate your paper, usually within 14 days.') }}</li>
                    <li>{{ __('You see their scores and comments in Your Articles.') }}</li>
                    <li>{{ __('The editor accepts it, asks for revisions, or declines it.') }}</li>
                    <li>{{ __('Accepted premium papers go live once the publish fee is paid.') }}</li>
                </ol>
            </div>
        </aside>
        </div>
    </div>

    @include('partials.footer')

    <script>
        (function () {
            const field = document.getElementById('contentField');
            const abstract = document.getElementById('abstract');
            const words = t => (t.trim().match(/\S+/g) || []).length;
            function count(el, out, min, max) {
                if (!el || !out) return;
                const n = words(el.value);
                out.textContent = WertaI18n.t(':count words', { count: n.toLocaleString() });
                out.classList.toggle('is-off', n > 0 && (n < min || n > max));
            }
            const update = () => {
                count(field, document.getElementById('contentCount'), 800, 5000);
                count(abstract, document.getElementById('abstractCount'), 150, 250);
            };
            [field, abstract].forEach(el => el && el.addEventListener('input', update));
            update();

            document.getElementById('insertTemplate')?.addEventListener('click', function () {
                const type = (document.querySelector('input[name="template_type"]:checked') || {}).value || 'research';
                const tpl = document.getElementById('tpl-' + type);
                if (!tpl || !field) return;
                if (field.value.trim() !== '' && !window.confirm(WertaI18n.t('Replace what you have written with the template?'))) return;
                field.value = tpl.content.textContent.trim();
                field.focus();
                field.setSelectionRange(0, 0);
                field.scrollTop = 0;
                update();
            });
        })();
    </script>

    <style>
        .submit-layout { display: grid; grid-template-columns: 1fr; gap: 1.5rem; align-items: start; }
        .submit-layout > * { min-width: 0; }
        @media (min-width: 992px) { .submit-layout { grid-template-columns: minmax(0, 2fr) minmax(280px, 1fr); } .submit-aside { position: sticky; top: 90px; } }
        .template-box { background: var(--cream-light); border: 1px dashed rgba(123,107,53,.35); border-radius: var(--radius-md); padding: 1rem; margin-bottom: .9rem; }
        .template-options { display: grid; grid-template-columns: repeat(auto-fit, minmax(170px, 1fr)); gap: .6rem; }
        .template-option { cursor: pointer; margin: 0; }
        .template-option input { position: absolute; opacity: 0; pointer-events: none; }
        .template-option > span { display: flex; flex-direction: column; gap: .15rem; height: 100%; padding: .75rem .85rem; background: #fff; border-radius: var(--radius-md); box-shadow: inset 0 0 0 1px rgba(123,107,53,.25); transition: box-shadow .2s; }
        .template-option > span i { color: var(--primary); font-size: 1.15rem; }
        .template-option strong { font-size: var(--text-base); color: var(--dark); }
        .template-option small { font-size: var(--text-sm); color: var(--muted); line-height: 1.4; }
        .template-option .template-words { color: var(--primary-dark); font-weight: 700; margin-top: auto; padding-top: .2rem; }
        .template-option input:checked + span { box-shadow: inset 0 0 0 2px var(--gold); }
        .template-option input:focus-visible + span { outline: 2px solid var(--primary); outline-offset: 2px; }
        .manuscript-input { font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace; font-size: .85rem; line-height: 1.6; }
        .word-count { font-weight: 700; color: var(--primary-dark); }
        .word-count.is-off { color: #a35a1f; }
        .guideline-list { list-style: none; padding: 0; margin: 0; }
        .guideline-list li { display: flex; gap: .7rem; padding: .6rem 0; border-bottom: 1px solid rgba(123,107,53,.12); }
        .guideline-list li:last-child { border-bottom: none; }
        .guideline-list i { color: var(--gold); font-size: 1.05rem; margin-top: .1rem; }
        .guideline-list strong { display: block; font-size: var(--text-base); color: var(--dark); }
        .guideline-list span { display: block; font-size: var(--text-sm); color: var(--muted); line-height: 1.5; }
        .next-steps { padding-left: 1.1rem; margin: 0; font-size: var(--text-sm); color: var(--dark); }
        .next-steps li { margin-bottom: .4rem; }
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
