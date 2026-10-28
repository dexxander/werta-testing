@extends('layouts.app')

@section('content')
    @include('partials.navbar')
    @include('partials.werta-ui')
    @php use Articles\Support\MockBilling as MB; @endphp

    <div class="wt-page">
        <a href="{{ route('submissions.index') }}" class="wt-back"><i class="bi bi-arrow-left"></i> {{ __('Back to Your Articles') }}</a>

        <div class="wt-header">
            <div>
                <p class="wt-eyebrow">{{ __('Author studio') }}</p>
                <h1 class="wt-title">{{ __('Earnings') }}</h1>
                <p class="wt-subtitle">{{ __('You earn :rm for every copy bought or gifted. Subscription reads earn nothing.', ['rm' => MB::rm(MB::AUTHOR_PER_COPY)]) }}</p>
            </div>
            <span class="wt-demo-tag"><i class="bi bi-info-circle"></i> {{ __('DEMO DATA') }}</span>
        </div>

        @include('partials.flash')

        <div class="wt-grid wt-grid-4 wt-mb">
            <div class="wt-card wt-card--flat">
                <div class="wt-card-label">{{ __('Copies sold') }}</div>
                <div class="wt-stat-value">{{ number_format($copies) }}</div>
                <div class="wt-stat-sub">{{ __('across your premium papers') }}</div>
            </div>
            <div class="wt-card wt-card--flat">
                <div class="wt-card-label">{{ __('Total earned') }}</div>
                <div class="wt-stat-value is-gold">{{ MB::rm($totalEarned) }}</div>
                <div class="wt-stat-sub">{{ __(':rm × :n copies', ['rm' => MB::rm(MB::AUTHOR_PER_COPY), 'n' => number_format($copies)]) }}</div>
            </div>
            <div class="wt-card wt-card--flat">
                <div class="wt-card-label">{{ __('Publish fees paid') }}</div>
                <div class="wt-stat-value is-bad">− {{ MB::rm($feesPaid) }}</div>
                <div class="wt-stat-sub">{{ __(':fee per accepted premium paper', ['fee' => MB::rm(MB::PUBLISH_FEE)]) }}</div>
            </div>
            <div class="wt-card wt-card--flat">
                <div class="wt-card-label">{{ __('Net') }}</div>
                <div class="wt-stat-value {{ $net >= 0 ? 'is-good' : 'is-bad' }}">{{ $net < 0 ? '− ' : '' }}{{ MB::rm(abs($net)) }}</div>
                <div class="wt-stat-sub">{{ __('earnings minus fees') }}</div>
            </div>
        </div>

        <div class="wt-grid earnings-grid wt-mb">
            {{-- Chart --}}
            <div class="wt-card">
                <h2 class="wt-section-title" style="font-size: var(--text-xl);">{{ __('Copies sold per month') }}</h2>
                <div class="wt-chart"><canvas id="earningsChart" role="img" aria-label="{{ __('Copies sold per month') }}"></canvas></div>
            </div>

            {{-- Payout --}}
            <div class="wt-card wt-card--dark d-flex flex-column">
                <div class="wt-card-label">{{ __('Available to withdraw') }}</div>
                <div class="wt-stat-value" style="color: var(--gold); font-size: 2.4rem;">{{ MB::rm($withdrawable) }}</div>
                <div class="wt-small mt-1" style="opacity: .8;">{{ __(':amount on hold for :d days.', ['amount' => MB::rm($onHold), 'd' => MB::HOLD_DAYS]) }}</div>
                @if($withdrawn > 0)
                    <div class="wt-small" style="opacity: .8;">{{ __(':amount already withdrawn.', ['amount' => MB::rm($withdrawn)]) }}</div>
                @endif
                <form method="POST" action="{{ route('submissions.earnings.withdraw') }}" class="mt-auto pt-3">@csrf
                    <button class="wt-btn wt-btn--gold wt-btn--block" @disabled($withdrawable < MB::MIN_WITHDRAW)>{{ __('Withdraw :amount', ['amount' => MB::rm($withdrawable)]) }}</button>
                    <div class="wt-small mt-2" style="opacity: .7;">{{ __('Minimum withdrawal :min. Paid to your bank account.', ['min' => MB::rm(MB::MIN_WITHDRAW)]) }}</div>
                </form>
            </div>
        </div>

        <h2 class="wt-section-title">{{ __('Progress toward recouping the :fee publish fee', ['fee' => MB::rm(MB::PUBLISH_FEE)]) }}</h2>
        <p class="wt-section-sub">{{ __('At :rm per copy, each premium paper breaks even after :n copies.', ['rm' => MB::rm(MB::AUTHOR_PER_COPY), 'n' => $breakEven]) }}</p>

        <div class="wt-stack">
            @forelse($papers as $row)
                <div class="wt-card wt-card--flat">
                    <div class="d-flex flex-wrap justify-content-between gap-3 align-items-start">
                        <div class="min-w-0">
                            <a href="{{ route('submissions.show', $row->paper) }}" class="fw-bold text-decoration-none" style="color: var(--dark); font-size: var(--text-lg);">{{ $row->paper->title }}</a>
                            <div class="wt-small wt-muted">
                                {{ __(':n copies', ['n' => $row->copies]) }}
                                @if($row->live > 0) <span class="wt-badge wt-badge--green">{{ __('+:n this session', ['n' => $row->live]) }}</span> @endif
                                · {{ __(':n subscription reads (unpaid)', ['n' => $row->subReads]) }}
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold" style="font-size: var(--text-lg);">{{ MB::rm($row->earned) }}</div>
                            <div class="wt-small wt-muted">{{ __('earned') }}</div>
                        </div>
                    </div>

                    @if($row->feePaid)
                        <div class="mt-3">
                            <div class="wt-progress"><div class="wt-progress-fill {{ $row->progress >= 100 ? 'is-done' : '' }}" style="width: {{ $row->progress }}%"></div></div>
                            <div class="d-flex flex-wrap justify-content-between wt-small mt-1">
                                <span class="wt-muted">{{ __(':copies of :need copies', ['copies' => $row->copies, 'need' => $breakEven]) }}</span>
                                @if($row->left > 0)
                                    <span class="fw-bold" style="color: var(--primary-dark);">{{ __(':n more copies to break even', ['n' => $row->left]) }}</span>
                                @else
                                    <span class="fw-bold" style="color: #3a7d44;"><i class="bi bi-check-circle-fill"></i> {{ __('Fee recouped — profit :a', ['a' => MB::rm($row->net)]) }}</span>
                                @endif
                            </div>
                        </div>
                    @elseif($row->feeDue)
                        <div class="wt-alert wt-alert--info mt-3 mb-0 d-flex flex-wrap justify-content-between align-items-center">
                            <span><i class="bi bi-credit-card"></i> {{ __('Accepted — pay the :fee publish fee to go live.', ['fee' => MB::rm(MB::PUBLISH_FEE)]) }}</span>
                            <form method="POST" action="{{ route('submissions.pay-fee', $row->paper) }}">@csrf
                                <button class="wt-btn wt-btn--gold wt-btn--sm">{{ __('Pay :fee fee', ['fee' => MB::rm(MB::PUBLISH_FEE)]) }}</button>
                            </form>
                        </div>
                    @else
                        <div class="wt-small wt-muted mt-3"><i class="bi bi-info-circle"></i> {{ __('The :fee fee is only charged after admin acceptance.', ['fee' => MB::rm(MB::PUBLISH_FEE)]) }}</div>
                    @endif
                </div>
            @empty
                <div class="wt-card wt-card--flat wt-empty">
                    <i class="bi bi-cash-coin"></i>
                    <p class="mb-0">{{ __('No paid papers yet. Submit a premium paper to start earning.') }}</p>
                </div>
            @endforelse
        </div>
    </div>

    @include('partials.footer')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
    <script>
        (function () {
            const el = document.getElementById('earningsChart');
            if (!el || !window.Chart) return;
            Chart.defaults.font.family = "'Lato', sans-serif";
            Chart.defaults.color = '#6B6455';
            new Chart(el, {
                type: 'bar',
                data: {
                    labels: @json(array_keys($monthly)),
                    datasets: [{ label: @json(__('Copies sold')), data: @json(array_values($monthly)), backgroundColor: '#C4A840', borderRadius: 6, maxBarThickness: 42 }]
                },
                options: {
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, grid: { color: 'rgba(123,107,53,0.1)' } }, x: { grid: { display: false } } }
                }
            });
        })();
    </script>

    <style>
        .earnings-grid { grid-template-columns: 1fr; }
        @media (min-width: 992px) { .earnings-grid { grid-template-columns: 2fr 1fr; } }
    </style>
@endsection
