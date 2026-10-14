@extends('submissions::layout')

@section('title', __('Earnings'))

@section('content')
@php use Articles\Support\MockBilling as MB; @endphp
<div class="max-w-3xl mx-auto py-10 px-4">
    <a href="{{ route('submissions.index') }}" class="inline-flex items-center gap-1 text-sm text-[#7B6B35] hover:text-[#C4A840] font-semibold mb-6 transition-colors">
        <i class="bi bi-arrow-left"></i> {{ __('Back to Your Articles') }}
    </a>

    <div class="flex justify-between items-start mb-6 gap-3">
        <div>
            <h1 class="text-2xl font-bold text-[#2C2416]">{{ __('Earnings') }}</h1>
            <p class="text-sm text-gray-500 mt-1">{{ __('You earn :p% of every first-time purchase. Subscription reads earn nothing.', ['p' => (int) (MB::AUTHOR_SHARE * 100)]) }}</p>
        </div>
        <span class="text-[10px] font-bold tracking-wider text-[#7B6B35] bg-[#F5EFE0] border border-[#C4A840] rounded-full px-3 py-1 whitespace-nowrap">{{ __('DEMO DATA') }}</span>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
        @foreach([
            [__('Total earned'), MB::rm($totalEarned), 'text-[#2C2416]'],
            [__('Publish fees paid'), '− ' . MB::rm($feesPaid), 'text-red-700'],
            [__('Net'), MB::rm($net), $net >= 0 ? 'text-green-700' : 'text-red-700'],
            [__('Withdrawable'), MB::rm($withdrawable), 'text-[#7B6B35]'],
        ] as [$label, $val, $cls])
            <div class="bg-white rounded-2xl border border-[#C4A840]/20 shadow-sm p-4">
                <div class="text-[11px] font-bold uppercase tracking-wider text-gray-400">{{ $label }}</div>
                <div class="text-xl font-bold mt-1 {{ $cls }}">{{ $val }}</div>
            </div>
        @endforeach
    </div>
    <p class="text-xs text-gray-400 mb-6"><i class="bi bi-hourglass-split"></i> {{ __(':amount is held for 14–30 days before it can be withdrawn (mock 70/30 split).', ['amount' => MB::rm($pending)]) }}</p>

    <h2 class="text-xs font-bold uppercase tracking-wider text-[#C4A840] mb-3">{{ __('Progress toward recouping the :fee publish fee', ['fee' => MB::rm(MB::PUBLISH_FEE)]) }}</h2>

    <div class="space-y-4">
        @forelse($papers as $row)
            <div class="bg-white rounded-2xl border border-[#C4A840]/20 shadow-sm p-5">
                <div class="flex justify-between gap-3 items-start">
                    <div class="min-w-0">
                        <a href="{{ route('submissions.show', $row->paper) }}" class="font-bold text-[#2C2416] hover:text-[#7B6B35]">{{ $row->paper->title }}</a>
                        <div class="text-xs text-gray-400 mt-0.5">
                            {{ MB::rm(MB::sen($row->paper->price)) }} · {{ $row->sales }} {{ __('sales') }} · {{ MB::rm($row->per) }} {{ __('per sale') }}
                        </div>
                    </div>
                    <div class="text-right shrink-0">
                        <div class="font-bold text-[#2C2416]">{{ MB::rm($row->earned) }}</div>
                        <div class="text-[11px] text-gray-400">{{ __('earned') }}</div>
                    </div>
                </div>

                @if($row->feePaid)
                    <div class="mt-4">
                        <div class="h-2.5 rounded-full bg-[#F5EFE0] overflow-hidden">
                            <div class="h-full rounded-full {{ $row->progress >= 100 ? 'bg-green-600' : 'bg-[#C4A840]' }}" style="width: {{ $row->progress }}%"></div>
                        </div>
                        <div class="flex justify-between text-xs mt-1.5 text-gray-500">
                            <span>{{ $row->progress }}% {{ __('of :fee fee recouped', ['fee' => MB::rm(MB::PUBLISH_FEE)]) }}</span>
                            @if($row->left > 0)
                                <span class="font-semibold text-[#7B6B35]">{{ $row->left }} {{ __('more sales to break even') }}</span>
                            @else
                                <span class="font-semibold text-green-700"><i class="bi bi-check-circle-fill"></i> {{ __('Fee recouped — profit :a', ['a' => MB::rm($row->net)]) }}</span>
                            @endif
                        </div>
                    </div>
                @elseif($row->feeDue)
                    <div class="mt-4 text-xs text-yellow-700 bg-yellow-50 rounded-lg px-3 py-2"><i class="bi bi-credit-card"></i> {{ __('Accepted — pay the :fee publish fee to go live.', ['fee' => MB::rm(MB::PUBLISH_FEE)]) }}</div>
                @else
                    <div class="mt-4 text-xs text-gray-500 bg-gray-50 rounded-lg px-3 py-2"><i class="bi bi-info-circle"></i> {{ __('The :fee fee is only charged after admin acceptance.', ['fee' => MB::rm(MB::PUBLISH_FEE)]) }}</div>
                @endif
            </div>
        @empty
            <div class="bg-white rounded-2xl border border-[#C4A840]/20 p-10 text-center text-gray-400">
                <i class="bi bi-cash-coin text-3xl block mb-2 text-[#C4A840]/60"></i>
                <p class="text-sm">{{ __('No paid papers yet. Submit a premium paper to start earning.') }}</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
