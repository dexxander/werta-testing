@include('partials.auth-login', [
    'title' => 'Counselor Sign In - Werta',
    'badgeIcon' => 'bi-person-badge',
    'portalLabel' => 'Counselor Portal',
    'heading' => 'Welcome Back',
    'subheading' => 'Sign in to access your dashboard.',
    'postUrl' => url('/counselor/login'),
    'showRegisterLink' => false,
])
