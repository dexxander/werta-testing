@include('partials.auth-login', [
    'title' => 'Reviewer Sign In - Werta',
    'badgeIcon' => 'bi-patch-check',
    'portalLabel' => 'Reviewer Portal',
    'heading' => 'Reviewer Sign In',
    'subheading' => 'For verified professionals who evaluate Werta articles.',
    'postUrl' => url('/reviewer/login'),
    'showRegisterLink' => true,
    'registerUrl' => url('/reviewer/apply'),
])
