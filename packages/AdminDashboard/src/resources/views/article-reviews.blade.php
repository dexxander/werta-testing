@extends('admindashboard::layout')

@section('content')
@php
    use Articles\Support\MockBilling as MB;
    use Submissions\Models\Submission;
    use Submissions\Models\SubmissionReview;
    $tabs = ['open' => __('Waiting for review'), 'decided' => __('Decided'), 'published' => __('Published')];
    $statusClass = [
        'submitted' => 'bg-gray-100 text-gray-600', 'under_review' => 'bg-blue-50 text-blue-700',
        'revisions_requested' => 'bg-yellow-50 text-yellow-700', 'accepted' => 'bg-green-50 text-green-700',
        'rejected' => 'bg-red-50 text-red-700', 'published' => 'bg-green-50 text-green-700',
    ];
@endphp
<div>
    <div class="mb-6 sm:mb-8">
        <h1 class="font-display text-3xl sm:text-4xl text-dark">{{ __('Article Reviews') }}</h1>
        <p class="text-sm text-gray-500 mt-1">{{ __('Assign verified reviewers, read their evaluations and make the final decision.') }}</p>
    </div>

    <div class="flex flex-wrap gap-2 mb-6">
        @foreach($tabs as $key => $label)
            <a href="{{ url('/' . $role . '/reviews?status=' . $key) }}" class="px-4 py-2 rounded-lg text-sm font-semibold {{ $filter === $key ? 'bg-gold/10 text-primary' : 'text-gray-500 hover:bg-gray-50' }}">{{ $label }} ({{ $counts[$key] }})</a>
        @endforeach
    </div>

    @if(session('success'))
        <div class="mb-4 p-3 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm font-medium"><i class="bi bi-check-circle mr-1"></i> {{ \App\Support\SiteText::translate(session('success')) }}</div>
    @endif
    @if($errors->any())
        <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm font-medium"><i class="bi bi-exclamation-circle mr-1"></i> {{ \App\Support\SiteText::translate($errors->first()) }}</div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-5 gap-6">
        {{-- List --}}
        <div class="xl:col-span-2 bg-white rounded-2xl p-3 card-ring self-start">
            @forelse($submissions as $s)
                @php
                    $done = $s->reviews->where('status', 'completed')->count();
                    $assigned = $s->reviews->whereIn('status', ['assigned', 'completed'])->count();
                @endphp
                <a href="{{ url('/' . $role . '/reviews?status=' . $filter . '&open=' . $s->id) }}" class="block p-4 rounded-xl transition-colors {{ $selected && $selected->id === $s->id ? 'bg-gold/10 ring-1 ring-gold/40' : 'hover:bg-cream/70' }}">
                    <div class="flex justify-between gap-2 items-start">
                        <div class="font-semibold text-dark leading-snug">{{ $s->title }}</div>
                        <span class="text-[10px] font-bold uppercase px-2 py-1 rounded-full shrink-0 {{ $statusClass[$s->status] ?? 'bg-gray-100 text-gray-600' }}">{{ \App\Support\SiteText::translate($s->status) }}</span>
                    </div>
                    <div class="text-xs text-gray-500 mt-1 flex flex-wrap gap-x-3">
                        <span>{{ $s->author_name }}</span>
                        @if($s->category_label)<span>{{ __($s->category_label) }}</span>@endif
                        <span>{{ $s->access_type === 'paid' ? __('Premium') : __('Free') }}</span>
                    </div>
                    <div class="text-xs mt-2 {{ $assigned ? 'text-primary' : 'text-yellow-700' }}"><i class="bi bi-people"></i>
                        {{ $assigned ? __(':done of :n reviews in', ['done' => $done, 'n' => $assigned]) : __('No reviewer assigned yet') }}
                    </div>
                </a>
            @empty
                <div class="text-center text-gray-400 py-14"><i class="bi bi-inbox text-4xl block mb-2"></i>{{ __('Nothing here right now.') }}</div>
            @endforelse
        </div>

        {{-- Detail --}}
        <div class="xl:col-span-3">
            @if($selected)
                @php
                    $s = $selected;
                    $completed = $s->reviews->where('status', 'completed');
                    $saleVotes = $completed->whereNotNull('suitable_for_sale');
                    $assignedIds = $s->reviews->whereIn('status', ['assigned', 'completed'])->pluck('reviewer_id')->all();
                @endphp
                <div class="bg-white rounded-2xl p-6 card-ring mb-6">
                    <div class="flex flex-wrap justify-between gap-3 items-start">
                        <div class="min-w-0">
                            <p class="text-xs font-bold uppercase tracking-[2px] text-primary">{{ $s->category_label ? __($s->category_label) : __('Manuscript') }}</p>
                            <h2 class="font-display text-3xl text-dark leading-tight">{{ $s->title }}</h2>
                            <p class="text-xs text-gray-400 mt-1">{{ $s->author_name }} · {{ __('nav.roles.' . $s->author_role) }} · {{ __('Submitted :date', ['date' => $s->submitted_at->translatedFormat('d M Y')]) }}</p>
                        </div>
                        <span class="text-[10px] font-bold uppercase px-2 py-1 rounded-full {{ $s->access_type === 'paid' ? 'bg-dark text-gold' : 'bg-gray-100 text-gray-600' }}">{{ $s->access_type === 'paid' ? __('Author requested: Premium') : __('Author requested: Free') }}</span>
                    </div>
                    @if($s->abstract)<p class="text-sm text-gray-600 mt-4 italic">{{ $s->abstract }}</p>@endif
                    @if($s->status === 'published' && $s->visibility === 'public')
                        <a href="{{ route('public.articles.show', $s->slug) }}" target="_blank" class="inline-flex items-center gap-1 text-sm font-semibold text-primary mt-3"><i class="bi bi-box-arrow-up-right"></i> {{ __('View public page') }}</a>
                    @endif
                </div>

                {{-- Reviews --}}
                <div class="bg-white rounded-2xl p-6 card-ring mb-6">
                    <div class="flex flex-wrap justify-between items-center gap-3 mb-4">
                        <h3 class="font-display text-2xl text-dark">{{ __('Reviews') }}</h3>
                        @if($saleVotes->count())
                            <span class="text-xs font-semibold text-primary-dark bg-cream px-3 py-1 rounded-full"><i class="bi bi-coin text-gold"></i> {{ __(':yes of :n reviewers say sell as premium', ['yes' => $saleVotes->where('suitable_for_sale', true)->count(), 'n' => $saleVotes->count()]) }}</span>
                        @endif
                    </div>
                    <div class="space-y-4">
                        @forelse($s->reviews as $r)
                            <div class="p-4 rounded-xl {{ $r->status === 'completed' ? 'bg-cream-light' : 'bg-gray-50' }}">
                                <div class="flex flex-wrap justify-between gap-2 items-center">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $r->reviewer->avatar_url }}" alt="{{ $r->reviewer->name }}" class="h-9 w-9 rounded-full">
                                        <div>
                                            <div class="font-semibold text-dark text-sm">{{ $r->reviewer->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $r->reviewer->title }}</div>
                                        </div>
                                    </div>
                                    @if($r->status === 'completed')
                                        <div class="text-right">
                                            <span class="text-[10px] font-bold uppercase px-2 py-1 rounded-full {{ in_array($r->recommendation, ['accept', 'minor_revision']) ? 'bg-green-50 text-green-700' : ($r->recommendation === 'reject' ? 'bg-red-50 text-red-700' : 'bg-yellow-50 text-yellow-700') }}">{{ __($r->recommendationLabel()) }}</span>
                                            <div class="text-xs text-gray-500 mt-1">{{ __('Score :s/5', ['s' => $r->overallScore()]) }} · {{ trans_choice(':count day|:count days', (int) $r->turnaroundDays(), ['count' => $r->turnaroundDays()]) }}</div>
                                        </div>
                                    @elseif($r->status === 'declined')
                                        <span class="text-[10px] font-bold uppercase px-2 py-1 rounded-full bg-gray-200 text-gray-600">{{ __('Declined') }}</span>
                                    @else
                                        <span class="text-[10px] font-bold uppercase px-2 py-1 rounded-full bg-blue-50 text-blue-700">{{ __('Due :date', ['date' => optional($r->due_at)->translatedFormat('d M')]) }}</span>
                                    @endif
                                </div>
                                @if($r->status === 'completed')
                                    <div class="grid sm:grid-cols-5 gap-2 mt-3 text-center">
                                        @foreach(SubmissionReview::CRITERIA as $col => $label)
                                            <div class="bg-white rounded-lg py-1.5 px-1"><div class="text-[10px] text-gray-400 leading-tight">{{ __($label) }}</div><div class="font-bold text-dark">{{ $r->{$col} }}</div></div>
                                        @endforeach
                                    </div>
                                    @if($r->comments)<p class="text-sm text-gray-700 mt-3">{{ $r->comments }}</p>@endif
                                    @if($r->suggestions)<p class="text-xs text-gray-500 mt-1"><strong>{{ __('Requested changes') }}:</strong> {{ $r->suggestions }}</p>@endif
                                    @if($r->confidential_notes)<p class="text-xs text-red-700 mt-1"><i class="bi bi-lock"></i> <strong>{{ __('Confidential') }}:</strong> {{ $r->confidential_notes }}</p>@endif
                                    <div class="text-xs text-gray-500 mt-2 flex flex-wrap gap-x-4">
                                        @if($r->suitable_for_sale !== null)<span><i class="bi bi-coin"></i> {{ $r->suitable_for_sale ? __('Sell as premium') : __('Publish free') }}</span>@endif
                                        <span><i class="bi {{ $r->show_on_article ? 'bi-eye' : 'bi-incognito' }}"></i> {{ $r->show_on_article ? __('Named on article') : __('Anonymous') }}</span>
                                    </div>
                                @endif
                            </div>
                        @empty
                            <p class="text-sm text-gray-400">{{ __('No reviewers assigned yet.') }}</p>
                        @endforelse
                    </div>

                    @if(in_array($s->status, ['submitted', 'under_review', 'revisions_requested']))
                        <form method="POST" action="{{ url('/' . $role . '/reviews/' . $s->id . '/assign') }}" class="mt-5 pt-5 border-t border-gray-100 flex flex-wrap gap-2 items-end">
                            @csrf
                            <div class="flex-1 min-w-[220px]">
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-400 mb-1">{{ __('Assign a reviewer') }}</label>
                                <select name="reviewer_id" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-gold focus:ring-1 focus:ring-gold outline-none" required>
                                    @foreach($reviewers as $rv)
                                        <option value="{{ $rv->id }}" @disabled(in_array($rv->id, $assignedIds)) @selected($rv->field === $s->category && !in_array($rv->id, $assignedIds))>
                                            {{ $rv->name }} — {{ $rv->field_label ? __($rv->field_label) : $rv->title }}{{ $rv->field === $s->category ? ' ★' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-400 mb-1">{{ __('Due in') }}</label>
                                <select name="due_days" class="px-3 py-2 rounded-lg border border-gray-200 text-sm">
                                    @foreach([7, 14, 21] as $d)<option value="{{ $d }}" @selected($d === 14)>{{ trans_choice(':count day|:count days', $d, ['count' => $d]) }}</option>@endforeach
                                </select>
                            </div>
                            <button class="bg-gold hover:bg-primary text-dark hover:text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors"><i class="bi bi-person-plus"></i> {{ __('Assign') }}</button>
                            <p class="w-full text-[11px] text-gray-400">{{ __('★ = field matches the paper\'s category.') }}</p>
                        </form>
                    @endif
                </div>

                {{-- Decision --}}
                @if(in_array($s->status, ['submitted', 'under_review', 'revisions_requested']))
                    <div class="bg-white rounded-2xl p-6 card-ring">
                        <h3 class="font-display text-2xl text-dark mb-1">{{ __('Editor\'s decision') }}</h3>
                        <p class="text-xs text-gray-400 mb-4">{{ $completed->count() ? __('Based on :n completed review(s).', ['n' => $completed->count()]) : __('Wait for at least one completed review before deciding.') }}</p>
                        <form method="POST" action="{{ url('/' . $role . '/reviews/' . $s->id . '/decision') }}" class="space-y-4">
                            @csrf
                            <div class="grid sm:grid-cols-3 gap-2">
                                @foreach(['accept' => __('Accept'), 'revisions' => __('Request revisions'), 'reject' => __('Reject')] as $value => $label)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="decision" value="{{ $value }}" class="peer sr-only" required>
                                        <span class="block text-center py-2 rounded-lg ring-1 ring-primary/25 text-sm font-semibold text-gray-600 peer-checked:bg-primary-dark peer-checked:text-white hover:ring-primary transition">{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-400 mb-1">{{ __('Publish as') }}</label>
                                @php $suggest = $saleVotes->count() ? ($saleVotes->where('suitable_for_sale', true)->count() * 2 >= $saleVotes->count() ? 'paid' : 'free') : $s->access_type; @endphp
                                <div class="flex flex-wrap gap-4 text-sm">
                                    <label class="flex items-center gap-2"><input type="radio" name="access_type" value="paid" @checked($suggest === 'paid') class="accent-[#7B6B35]"> {{ __('Premium (:rm)', ['rm' => MB::rm(MB::articlePrice())]) }}</label>
                                    <label class="flex items-center gap-2"><input type="radio" name="access_type" value="free" @checked($suggest === 'free') class="accent-[#7B6B35]"> {{ __('Free') }}</label>
                                </div>
                                <p class="text-[11px] text-gray-400 mt-1">{{ __('Pre-selected from the reviewers’ sale verdicts.') }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-400 mb-1">{{ __('Note to the author') }}</label>
                                <textarea name="notes" rows="3" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-gold focus:ring-1 focus:ring-gold outline-none">{{ $s->decision_notes }}</textarea>
                            </div>
                            <button class="bg-gold hover:bg-primary text-dark hover:text-white px-5 py-2.5 rounded-lg text-sm font-semibold transition-colors">{{ __('Save decision') }}</button>
                        </form>
                    </div>
                @elseif($s->status === 'accepted' && $s->access_type === 'free')
                    <div class="bg-white rounded-2xl p-6 card-ring flex flex-wrap justify-between items-center gap-3">
                        <div>
                            <h3 class="font-display text-2xl text-dark">{{ __('Ready to publish') }}</h3>
                            <p class="text-xs text-gray-400">{{ __('Accepted as a free article. No publish fee applies.') }}</p>
                        </div>
                        <form method="POST" action="{{ url('/' . $role . '/reviews/' . $s->id . '/publish') }}">@csrf
                            <button class="bg-gold hover:bg-primary text-dark hover:text-white px-5 py-2.5 rounded-lg text-sm font-semibold transition-colors"><i class="bi bi-send-check"></i> {{ __('Publish now') }}</button>
                        </form>
                    </div>
                @elseif($s->status === 'accepted')
                    <div class="p-4 rounded-2xl bg-cream-light ring-1 ring-gold/40 text-sm text-primary-dark"><i class="bi bi-hourglass-split"></i> {{ __('Accepted as premium. Waiting for the author to pay the :fee publish fee.', ['fee' => MB::rm(MB::PUBLISH_FEE)]) }}</div>
                @elseif($s->decision_notes)
                    <div class="p-4 rounded-2xl bg-cream-light ring-1 ring-gold/40 text-sm text-primary-dark"><strong>{{ __('Decision note') }}:</strong> {{ $s->decision_notes }}</div>
                @endif
            @else
                <div class="bg-white rounded-2xl p-10 card-ring text-center text-gray-400"><i class="bi bi-journal-check text-4xl block mb-2"></i>{{ __('Select a manuscript to see its reviews.') }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
