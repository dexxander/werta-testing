{{-- One top-up pack card. Expects $id, $pack; optional $return (path to come back to). --}}
@php use Articles\Support\MockBilling as MB; @endphp
<div class="wt-card wt-pack {{ $pack['tag'] ? 'wt-card--feature' : '' }}">
    @if($pack['tag'])
        <span class="wt-badge wt-badge--dark wt-pack-tag">{{ __($pack['tag']) }}</span>
    @endif
    <div class="wt-card-label">{{ __($pack['name']) }}</div>
    <div class="wt-pack-price">{{ MB::rm($pack['price']) }}</div>
    <div class="wt-pack-tokens">
        <span class="wt-token"><i class="bi bi-coin"></i> {{ $pack['tokens'] }}</span> {{ __('tokens') }}
        @if($pack['bonus'] > 0)
            <span class="wt-badge wt-badge--gold ms-1">+{{ $pack['bonus'] }} {{ __('bonus') }}</span>
        @endif
    </div>
    <div class="wt-pack-per">
        {{ trans_choice(':count article|:count articles', $pack['articles'], ['count' => $pack['articles']]) }}
        · {{ __(':price each', ['price' => MB::rm(MB::perArticle($pack))]) }}
    </div>
    <form method="POST" action="{{ route('mock.topup') }}">@csrf
        <input type="hidden" name="pack" value="{{ $id }}">
        @if(!empty($return))<input type="hidden" name="return" value="{{ $return }}">@endif
        <button class="wt-btn {{ $pack['tag'] ? 'wt-btn--gold' : 'wt-btn--outline' }} wt-btn--block" aria-label="{{ __('Top up :price', ['price' => MB::rm($pack['price'])]) }}">{{ __('Top up') }}</button>
    </form>
</div>
