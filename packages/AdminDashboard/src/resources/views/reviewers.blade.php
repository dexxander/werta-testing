@extends('admindashboard::layout')

@section('content')
<div>
    <div class="mb-6 sm:mb-8 flex flex-wrap justify-between items-end gap-3">
        <div>
            <h1 class="font-display text-3xl sm:text-4xl text-dark">{{ __('Reviewers') }}</h1>
            <p class="text-sm text-gray-500 mt-1">{{ __('Verify professional credentials before anyone can review articles.') }}</p>
        </div>
        <a href="{{ route('reviewer.apply') }}" target="_blank" class="text-sm font-semibold text-primary hover:text-primary-dark"><i class="bi bi-box-arrow-up-right"></i> {{ __('Public application form') }}</a>
    </div>

    <div class="flex flex-wrap gap-2 mb-6">
        @foreach(['pending' => __('Pending'), 'approved' => __('Approved'), 'other' => __('Rejected or suspended')] as $key => $label)
            <a href="{{ url('/' . $role . '/reviewers?tab=' . $key) }}" class="px-4 py-2 rounded-lg text-sm font-semibold {{ $tab === $key ? 'bg-gold/10 text-primary' : 'text-gray-500 hover:bg-gray-50' }}">{{ $label }} ({{ $counts[$key] }})</a>
        @endforeach
    </div>

    @if(session('success'))
        <div class="mb-4 p-3 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm font-medium"><i class="bi bi-check-circle mr-1"></i> {{ \App\Support\SiteText::translate(session('success')) }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @forelse($reviewers as $r)
            <div class="bg-white rounded-2xl p-6 card-ring">
                <div class="flex items-start gap-4">
                    <img src="{{ $r->avatar_url }}" alt="{{ $r->name }}" class="h-14 w-14 rounded-full border-2 border-cream">
                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center gap-2">
                            <h2 class="font-bold text-dark text-lg">{{ $r->name }}</h2>
                            <span class="text-[10px] font-bold uppercase px-2 py-1 rounded-full {{ ['pending' => 'bg-yellow-50 text-yellow-700', 'approved' => 'bg-green-50 text-green-700', 'rejected' => 'bg-red-50 text-red-700', 'suspended' => 'bg-gray-100 text-gray-600'][$r->status] ?? 'bg-gray-100 text-gray-600' }}">{{ \App\Support\SiteText::translate($r->status) }}</span>
                            @if(!$r->show_publicly)<span class="text-[10px] font-bold uppercase px-2 py-1 rounded-full bg-gray-100 text-gray-500"><i class="bi bi-incognito"></i> {{ __('Anonymous') }}</span>@endif
                        </div>
                        <div class="text-sm text-gray-600">{{ $r->title }}@if($r->institution) · {{ $r->institution }}@endif</div>
                    </div>
                </div>

                <dl class="grid grid-cols-2 gap-x-4 gap-y-2 text-sm mt-4">
                    <div><dt class="text-[11px] font-bold uppercase tracking-wider text-gray-400">{{ __('Field') }}</dt><dd class="text-dark">{{ $r->field_label ? __($r->field_label) : '—' }}</dd></div>
                    <div><dt class="text-[11px] font-bold uppercase tracking-wider text-gray-400">{{ __('Qualification') }}</dt><dd class="text-dark">{{ $r->qualifications ?? '—' }}</dd></div>
                    <div><dt class="text-[11px] font-bold uppercase tracking-wider text-gray-400">{{ __('Registration no.') }}</dt><dd class="text-dark">{{ $r->registration_no ?? '—' }}</dd></div>
                    <div><dt class="text-[11px] font-bold uppercase tracking-wider text-gray-400">{{ __('Email') }}</dt><dd class="text-dark truncate">{{ $r->email }}</dd></div>
                    @if($r->status === 'approved')
                        <div><dt class="text-[11px] font-bold uppercase tracking-wider text-gray-400">{{ __('Reviews') }}</dt><dd class="text-dark">{{ __(':done completed · :open open', ['done' => $r->completed_count, 'open' => $r->assigned_count]) }}</dd></div>
                        <div><dt class="text-[11px] font-bold uppercase tracking-wider text-gray-400">{{ __('Verified since') }}</dt><dd class="text-dark">{{ optional($r->approved_at)->translatedFormat('d M Y') }}</dd></div>
                    @else
                        <div><dt class="text-[11px] font-bold uppercase tracking-wider text-gray-400">{{ __('Applied') }}</dt><dd class="text-dark">{{ $r->created_at->translatedFormat('d M Y') }}</dd></div>
                        <div><dt class="text-[11px] font-bold uppercase tracking-wider text-gray-400">{{ __('Proof of credentials') }}</dt><dd class="text-dark">{{ $r->proof_path ? __('Uploaded') : __('Not uploaded') }}</dd></div>
                    @endif
                </dl>
                @if($r->bio)<p class="text-sm text-gray-500 mt-3">{{ $r->bio }}</p>@endif

                <div class="flex flex-wrap gap-3 mt-4 pt-4 border-t border-gray-100">
                    @if($r->status !== 'approved')
                        <form method="POST" action="{{ url('/' . $role . '/reviewers/' . $r->id . '/approve') }}">@csrf
                            <button class="bg-gold hover:bg-primary text-dark hover:text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors"><i class="bi bi-patch-check"></i> {{ __('Approve') }}</button>
                        </form>
                    @endif
                    @if($r->status === 'pending')
                        <form method="POST" action="{{ url('/' . $role . '/reviewers/' . $r->id . '/reject') }}">@csrf
                            <button class="px-4 py-2 rounded-lg text-sm font-semibold text-red-600 hover:bg-red-50">{{ __('Reject') }}</button>
                        </form>
                    @endif
                    @if($r->status === 'approved')
                        @if($r->show_publicly)
                            <a href="{{ route('public.reviewers.show', $r->slug) }}" target="_blank" class="px-4 py-2 rounded-lg text-sm font-semibold text-primary hover:bg-cream"><i class="bi bi-person-vcard"></i> {{ __('Public profile') }}</a>
                        @endif
                        <form method="POST" action="{{ url('/' . $role . '/reviewers/' . $r->id . '/suspend') }}" class="ml-auto">@csrf
                            <button class="px-4 py-2 rounded-lg text-sm font-semibold text-gray-500 hover:bg-gray-50">{{ __('Suspend') }}</button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="lg:col-span-2 bg-white rounded-2xl p-14 card-ring text-center text-gray-400">
                <i class="bi bi-patch-check text-4xl block mb-2"></i>
                {{ __('No reviewers in this list.') }}
            </div>
        @endforelse
    </div>
</div>
@endsection
