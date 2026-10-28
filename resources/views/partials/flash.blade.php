{{-- Flash messages for the public pages (uses partials.werta-ui styles). --}}
@if(session('mock_ok') || session('success'))
    <div class="wt-alert wt-alert--ok" role="status"><i class="bi bi-check-circle-fill"></i><span>{{ __(session('mock_ok') ?? session('success')) }}</span></div>
@endif
@if(session('mock_error'))
    <div class="wt-alert wt-alert--err" role="alert"><i class="bi bi-exclamation-circle-fill"></i><span>{{ __(session('mock_error')) }}</span></div>
@endif
@if(isset($errors) && $errors->any())
    <div class="wt-alert wt-alert--err" role="alert"><i class="bi bi-exclamation-circle-fill"></i><span>{{ \App\Support\SiteText::translate($errors->first()) }}</span></div>
@endif
