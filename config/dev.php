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
];
