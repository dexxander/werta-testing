<p class="community-muted">{{ __('community::community.sign_in_hint') }}</p>
<div class="d-flex flex-wrap gap-3">
    <a href="{{ url('/client/login') }}">{{ __('nav.client_sign_in') }}</a>
    <a href="{{ url('/parent/login') }}">{{ __('nav.parent_sign_in') }}</a>
    <a href="{{ route('counselor.login') }}">{{ __('nav.counselor_sign_in') }}</a>
</div>
