{{--
    Status badge + stage tracker for one submission.
    Expects $submission. Optional $showSteps (default true).
    Premium: Submitted → In review → Decision → Publish fee → Published
    Free:    Submitted → In review → Decision → Published
--}}
@php
    $s = $submission;
    $paid = $s->access_type === 'paid';
    $stages = $paid
        ? [__('Submitted'), __('In review'), __('Decision'), __('Publish fee'), __('Published')]
        : [__('Submitted'), __('In review'), __('Decision'), __('Published')];
    $last = count($stages) - 1;
    [$current, $stopped] = match ($s->status) {
        'submitted'           => [0, false],
        'under_review'        => [1, false],
        'revisions_requested' => [2, true],
        'rejected'            => [2, true],
        'accepted'            => [3, false],
        'published'           => [$last + 1, false],
        default               => [0, false],
    };
    $badge = [
        'submitted' => 'wt-badge--grey', 'under_review' => 'wt-badge--blue', 'revisions_requested' => 'wt-badge--yellow',
        'accepted' => 'wt-badge--green', 'rejected' => 'wt-badge--red', 'published' => 'wt-badge--green',
    ][$s->status] ?? 'wt-badge--grey';
    $label = \Submissions\Models\Submission::STATUS_LABELS[$s->status]['label'] ?? $s->status;
    if ($s->status === 'accepted') {
        $label = $paid ? 'Accepted — publish fee due' : 'Accepted — going live soon';
    } elseif ($s->status === 'published') {
        $label = 'Published';
    }
@endphp
@if($showBadge ?? true)
    <span class="wt-badge {{ $badge }}">{{ __($label) }}</span>
@endif
@if($showSteps ?? true)
    <div class="wt-steps mt-2" aria-label="{{ __('Progress') }}">
        @foreach($stages as $i => $stage)
            <div class="wt-step {{ $i < $current ? 'is-done' : ($i === $current ? ($stopped ? 'is-stopped' : 'is-current') : '') }}">
                <div class="wt-step-bar"></div>
                <div class="wt-step-label">{{ $stage }}</div>
            </div>
        @endforeach
    </div>
@endif
