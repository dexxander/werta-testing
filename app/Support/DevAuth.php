<?php

namespace App\Support;

class DevAuth
{
    /**
     * DEV BYPASS: Determine whether development authentication bypass is active.
     *
     * Strict triple-lock gating:
     * 1. Must be local environment (app()->environment('local'))
     * 2. Application debug mode must be active (config('app.debug') === true)
     * 3. DEV_BYPASS_AUTH must be explicitly enabled via config
     *
     * If ANY of these conditions is not met, this method returns false.
     */
    public static function isBypassActive(): bool
    {
        return app()->environment('local')
            && config('app.debug') === true
            && (bool) config('dev.bypass_auth', false);
    }
}
