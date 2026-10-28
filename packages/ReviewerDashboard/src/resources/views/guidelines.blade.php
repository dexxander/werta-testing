@extends('reviewerdashboard::layout')

@section('title', __('Evaluation guidelines'))

@section('content')
@php use Submissions\Models\SubmissionReview; @endphp
<div class="max-w-4xl">
    <div class="mb-6 sm:mb-8">
        <h1 class="font-display text-3xl sm:text-4xl text-dark">{{ __('Evaluation guidelines') }}</h1>
        <p class="text-sm text-gray-500 mt-1">{{ __('How to score a manuscript and decide whether it should be sold.') }}</p>
    </div>

    <div class="bg-white rounded-2xl p-6 card-ring mb-6">
        <h2 class="font-display text-2xl text-dark mb-3">{{ __('1. Score five criteria') }}</h2>
        <div class="grid sm:grid-cols-2 gap-3 text-sm">
            @foreach([
                'score_accuracy'    => __('Are the facts right? Is advice in line with recognised practice? Are sources or experience made clear?'),
                'score_originality' => __('Does it add something beyond common tips? Personal or local insight counts.'),
                'score_clarity'     => __('Could a Form 4 student or a busy parent follow it? Is it well structured?'),
                'score_relevance'   => __('Does it address a real need of Werta readers in Malaysia?'),
                'score_ethics'      => __('Is it safe and respectful? No identifying details, no harmful advice, and clear signposting to help.'),
            ] as $col => $text)
                <div class="p-4 rounded-xl bg-cream-light">
                    <div class="font-semibold text-dark mb-1">{{ __(SubmissionReview::CRITERIA[$col]) }}</div>
                    <p class="text-gray-600">{{ $text }}</p>
                </div>
            @endforeach
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 card-ring mb-6">
        <h2 class="font-display text-2xl text-dark mb-3">{{ __('2. Recommend a decision') }}</h2>
        <ul class="text-sm text-gray-600 space-y-2">
            <li><strong class="text-dark">{{ __('Accept') }}</strong> — {{ __('ready to publish as it is, or with tiny edits.') }}</li>
            <li><strong class="text-dark">{{ __('Minor revisions') }}</strong> — {{ __('good, but needs small, specific changes.') }}</li>
            <li><strong class="text-dark">{{ __('Major revisions') }}</strong> — {{ __('promising, but needs substantial work before it can be judged again.') }}</li>
            <li><strong class="text-dark">{{ __('Reject') }}</strong> — {{ __('unsafe, inaccurate or not suitable for Werta readers.') }}</li>
        </ul>
    </div>

    <div class="bg-white rounded-2xl p-6 card-ring mb-6">
        <h2 class="font-display text-2xl text-dark mb-3"><i class="bi bi-coin text-gold"></i> {{ __('3. Decide if it is suitable to sell') }}</h2>
        <p class="text-sm text-gray-600 mb-3">{{ __('Every premium paper costs readers RM 5, and the author earns RM 1 per copy. Recommend premium only when the paper gives readers clear value they would pay for.') }}</p>
        <div class="grid sm:grid-cols-2 gap-3 text-sm">
            <div class="p-4 rounded-xl bg-green-50 text-green-900">
                <div class="font-semibold mb-1">{{ __('Good fit for premium') }}</div>
                <ul class="list-disc pl-4 space-y-1">
                    <li>{{ __('Practical programmes, toolkits or step-by-step guides') }}</li>
                    <li>{{ __('Original research or rich case experience') }}</li>
                    <li>{{ __('Depth that goes well beyond free articles') }}</li>
                </ul>
            </div>
            <div class="p-4 rounded-xl bg-cream text-primary-dark">
                <div class="font-semibold mb-1">{{ __('Better published free') }}</div>
                <ul class="list-disc pl-4 space-y-1">
                    <li>{{ __('Short introductions or general awareness pieces') }}</li>
                    <li>{{ __('Crisis or safety information everyone should reach') }}</li>
                    <li>{{ __('Personal stories shared to help others') }}</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 card-ring">
        <h2 class="font-display text-2xl text-dark mb-3">{{ __('4. Conduct') }}</h2>
        <ul class="text-sm text-gray-600 space-y-2 list-disc pl-5">
            <li>{{ __('Decline any manuscript where you have a conflict of interest.') }}</li>
            <li>{{ __('Keep manuscripts confidential. Do not share or reuse them.') }}</li>
            <li>{{ __('Be specific and kind. Authors include students and parents.') }}</li>
            <li>{{ __('Use the confidential note for concerns only the editor should see.') }}</li>
            <li>{{ __('You can choose not to be named on the published article.') }}</li>
        </ul>
    </div>
</div>
@endsection
