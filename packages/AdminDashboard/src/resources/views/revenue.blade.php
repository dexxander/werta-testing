@extends('admindashboard::layout')

@section('content')
@php
    use Articles\Support\MockBilling as MB;
    $stages = [__('Submitted'), __('Under review'), __('Accepted'), __('Fee pending'), __('Published')];
@endphp
<div>
    <div class="mb-6 sm:mb-8 flex flex-wrap justify-between items-start gap-2">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-dark">{{ __('Revenue') }}</h1>
            <p class="text-sm text-gray-500 mt-1">{{ __('Publish fees, paper sales, subscriptions and author payouts.') }}</p>
        </div>
        <span class="text-[10px] font-bold tracking-wider text-primary bg-cream border border-gold rounded-full px-3 py-1">{{ __('DEMO DATA — session sales are live, the rest is baseline') }}</span>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
        @foreach([
            [__('Publish fees'), MB::rm($publishFees), $feesCount . ' × ' . MB::rm(MB::PUBLISH_FEE), 'text-dark'],
            [__('Article sales (gross)'), MB::rm($salesGross), __('one-off purchases'), 'text-dark'],
            [__('Subscriptions'), MB::rm($subRevenue), $subscribers . ' ' . __('subscribers'), 'text-dark'],
            [__('Est. gateway fees'), '− ' . MB::rm($gateway), (int) (MB::GATEWAY_RATE * 100) . '% ' . __('of takings'), 'text-red-700'],
            [__('Author payouts owed'), '− ' . MB::rm($payouts), (int) (MB::AUTHOR_SHARE * 100) . '% ' . __('of sales only'), 'text-red-700'],
            [__('Net platform revenue'), MB::rm($net), __('after fees and payouts'), 'text-green-700'],
        ] as [$label, $val, $sub, $cls])
            <div class="bg-white rounded-2xl p-5 border border-gold/20 shadow-sm">
                <div class="text-[11px] font-bold uppercase tracking-wider text-gray-400">{{ $label }}</div>
                <div class="text-2xl font-bold mt-1 {{ $cls }}">{{ $val }}</div>
                <div class="text-xs text-gray-400 mt-0.5">{{ $sub }}</div>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        {{-- Monthly bars --}}
        <div class="bg-white rounded-2xl p-6 border border-gold/20 shadow-sm">
            <h2 class="text-lg font-bold text-dark mb-4">{{ __('Monthly gross takings') }}</h2>
            <div class="flex items-end gap-3 h-56">
                @foreach($months as $m)
                    <div class="flex-1 flex flex-col items-center justify-end h-full">
                        <div class="text-[11px] text-gray-500 mb-1">{{ number_format($m['value'] / 100) }}</div>
                        <div class="w-full rounded-t-lg {{ $m['live'] ? 'bg-gold' : 'bg-gold/40' }}" style="height: {{ max(4, $m['value'] / $maxMonth * 85) }}%"></div>
                        <div class="text-xs font-semibold text-dark mt-2">{{ $m['label'] }}</div>
                    </div>
                @endforeach
            </div>
            <p class="text-[11px] text-gray-400 mt-3">{{ __('RM. The darker bar is this month, driven by live demo activity.') }}</p>
        </div>

        {{-- Top papers --}}
        <div class="bg-white rounded-2xl p-6 border border-gold/20 shadow-sm">
            <h2 class="text-lg font-bold text-dark mb-4">{{ __('Top-earning papers') }}</h2>
            <div class="divide-y divide-gray-100">
                @foreach($top as $t)
                    <div class="py-3 flex justify-between gap-3">
                        <div class="min-w-0">
                            <div class="font-semibold text-dark truncate">{{ $t['title'] }}
                                @if($t['demo'])<span class="text-[9px] font-bold text-gray-400 border border-gray-200 rounded px-1">DEMO</span>@endif</div>
                            <div class="text-xs text-gray-400">{{ $t['author'] }} · {{ $t['sales'] }} {{ __('sales') }}</div>
                        </div>
                        <div class="font-bold text-primary shrink-0">{{ MB::rm($t['gross']) }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Review queue --}}
    <div class="bg-white rounded-2xl p-6 border border-gold/20 shadow-sm">
        <h2 class="text-lg font-bold text-dark mb-1">{{ __('Premium review queue') }}</h2>
        <p class="text-xs text-gray-400 mb-4">{{ __('The :fee fee is only requested after acceptance.', ['fee' => MB::rm(MB::PUBLISH_FEE)]) }}</p>
        <div class="space-y-4">
            @foreach($queue as $q)
                <div>
                    <div class="flex justify-between text-sm mb-1.5">
                        <span class="font-semibold text-dark">{{ $q['title'] }}
                            @if($q['demo'])<span class="text-[9px] font-bold text-gray-400 border border-gray-200 rounded px-1">DEMO</span>@endif</span>
                        <span class="text-xs text-gray-400">{{ $q['author'] }}</span>
                    </div>
                    <div class="flex items-center gap-1">
                        @foreach($stages as $i => $label)
                            <div class="flex-1">
                                <div class="h-1.5 rounded-full {{ $i < $q['stage'] ? 'bg-gold' : ($i === $q['stage'] ? 'bg-primary' : 'bg-gray-200') }}"></div>
                                <div class="text-[10px] mt-1 {{ $i === $q['stage'] ? 'font-bold text-primary' : 'text-gray-400' }}">{{ $label }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
