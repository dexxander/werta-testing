@include('partials.auth-login', [
    'title' => 'Parent Sign In - Werta',
    'badgeIcon' => 'bi-people',
    'portalLabel' => 'Parent Portal',
    'heading' => 'Welcome Back',
    'subheading' => 'Sign in to access your dashboard.',
    'postUrl' => url('/parent/login'),
    'showRegisterLink' => false,
])
