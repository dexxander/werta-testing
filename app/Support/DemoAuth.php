<?php

namespace App\Support;

use AdminDashboard\Models\Reviewer;
use Illuminate\Support\Facades\Schema;

/**
 * DEMO ONLY: one place that answers "who is signed in?" for the mock.
 * Replaces the identity logic that used to be copied into
 * SubmissionController, ArticleController and MockBilling.
 *
 * TODO: replace with auth()->user() once unified login and real user IDs exist.
 */
class DemoAuth
{
    /** Roles that can read, buy and publish articles. */
    public const READER_ROLES = ['client', 'parent', 'counselor'];

    /** Fixed demo accounts. Names must match the seeded author_name values. */
    public const ACCOUNTS = [
        'client'    => 'Client User',
        'parent'    => 'Parent User',
        'counselor' => 'Counselor User',
    ];

    /** Demo login is on for local development unless DEMO_LOGIN=false. */
    public static function enabled(): bool
    {
        return (bool) config('dev.demo_login', false);
    }

    /**
     * Reader/author identity as [role, name], or null for guests,
     * staff and reviewers.
     */
    public static function current(): ?array
    {
        if (session('counselor_logged_in')) {
            return ['counselor', session('counselor_name', self::ACCOUNTS['counselor'])];
        }
        if (session('client_logged_in')) {
            return ['client', session('client_profile.username', self::ACCOUNTS['client'])];
        }
        if (session('parent_logged_in')) {
            return ['parent', session('parent_profile.username', self::ACCOUNTS['parent'])];
        }

        return null;
    }

    /** Stable key for one reader account, e.g. "client:Client User". */
    public static function key(?array $identity = null): ?string
    {
        $identity ??= self::current();

        return $identity ? $identity[0] . ':' . $identity[1] : null;
    }

    public static function isReviewer(): bool
    {
        return (bool) session('reviewer_logged_in');
    }

    public static function reviewer(): ?Reviewer
    {
        if (! self::isReviewer() || ! Schema::hasTable('reviewers')) {
            return null;
        }

        return Reviewer::where('status', 'approved')->find(session('reviewer_id'));
    }

    /** Reader accounts other people can gift to or refer (demo only). */
    public static function readerAccounts(): array
    {
        $out = [];
        foreach (self::ACCOUNTS as $role => $name) {
            $out[$role . ':' . $name] = ['role' => $role, 'name' => $name];
        }

        return $out;
    }

    /** Sign in as one demo persona, using the same session keys as the real login routes. */
    public static function loginAs(string $persona, ?Reviewer $reviewer = null): ?string
    {
        SessionRoles::clearAllRoles();
        session()->regenerate();

        switch ($persona) {
            case 'client':
                session([
                    'client_logged_in' => true,
                    'client_profile'   => ['username' => self::ACCOUNTS['client'], 'picture' => 'bi-person-circle'],
                ]);
                return '/articles';
            case 'parent':
                session([
                    'parent_logged_in' => true,
                    'parent_profile'   => ['username' => self::ACCOUNTS['parent'], 'picture' => 'bi-person-heart'],
                ]);
                return '/articles';
            case 'counselor':
                session([
                    'counselor_logged_in' => true,
                    'counselor_name'      => self::ACCOUNTS['counselor'],
                ]);
                return '/submissions';
            case 'reviewer':
                if (! $reviewer) {
                    return null;
                }
                session([
                    'reviewer_logged_in' => true,
                    'reviewer_id'        => $reviewer->id,
                    'reviewer_name'      => $reviewer->name,
                ]);
                return '/reviewer/dashboard';
            case 'admin':
            case 'superadmin':
                session(['staff_role' => $persona]);
                return '/' . $persona . '/dashboard';
        }

        return null;
    }
}
