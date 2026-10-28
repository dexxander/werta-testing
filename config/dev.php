<?php

/* DEV BYPASS */
return [
    /*
    |--------------------------------------------------------------------------
    | Dev Authentication Bypass
    |--------------------------------------------------------------------------
    |
    | Enables one-click role switching on login pages for development review.
    | MUST remain false or unset in production. Gated additionally by APP_ENV=local
    | and APP_DEBUG=true.
    |
    */
    'bypass_auth' => (bool) env('DEV_BYPASS_AUTH', false),

    /*
    |--------------------------------------------------------------------------
    | Demo Login (monetisation mock)
    |--------------------------------------------------------------------------
    |
    | Shows the /demo page where one click signs in as a reader, author,
    | reviewer or admin. On by default only when APP_ENV=local.
    | Set DEMO_LOGIN=false to hide it. Never enable on a public server.
    |
    */
    'demo_login' => (bool) env('DEMO_LOGIN', env('APP_ENV') === 'local'),
];
