@include('partials.auth-login', [
    'title' => 'Client Sign In - Werta',
    'badgeIcon' => 'bi-person',
    'portalLabel' => 'Client Portal',
    'heading' => 'Welcome Back',
    'subheading' => 'Sign in to access your dashboard.',
    'postUrl' => url('/client/login'),
    'showRegisterLink' => true,
    'registerUrl' => url('/auth/register'),
])
