<?php

namespace App\Support;

class SessionRoles
{
    // Role sessions must be strictly exclusive to prevent session stacking and privilege escalation.
    public const ALL_ROLE_KEYS = [
        'client_logged_in',
        'client_profile',
        'parent_logged_in',
        'parent_profile',
        'counselor_logged_in',
        'counselor_name',
        'staff_role',
        'staff_profile',
        'dev_bypass_session',
    ];

    public static function clearAllRoles(): void
    {
        session()->forget(self::ALL_ROLE_KEYS);
    }
}
