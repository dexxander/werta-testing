@php
    $role = $role ?? 'admin';
@endphp
@include('partials.auth-login', [
    'title' => ucfirst($role) . ' Sign In - Werta',
    'badgeIcon' => 'bi-shield-lock',
    'portalLabel' => ucfirst($role) . ' Portal',
    'heading' => 'Staff Sign In',
    'subheading' => 'Restricted access. Authorized ' . $role . ' personnel only.',
    'postUrl' => url('/' . $role . '/login'),
    'showRegisterLink' => false,
])