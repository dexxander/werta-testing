<?php

namespace Articles\Support;

use Submissions\Models\Submission;

/**
 * DEMO ONLY. Session-backed fake billing. No real money, no DB tables.
 * All amounts are integer sen (RM 1.00 = 100) to match the planned real ledger.
 * Replace with real wallet/purchase/ledger tables once the model is confirmed.
 */
class MockBilling
{
    public const PUBLISH_FEE   = 8000;   // RM 80
    public const SUB_PRICE     = 1500;   // RM 15 / month
    public const SUB_CAP       = 10;     // papers / month
    public const AUTHOR_SHARE  = 0.60;   // of each first-time purchase
    public const GATEWAY_RATE  = 0.03;
    public const START_BALANCE = 2000;   // RM 20

    private const KEY = 'mock_billing';

    /** TODO: replace with auth()->user() (same logic as SubmissionController::currentAuthor). */
    public static function viewer(): ?array
    {
        if (session('counselor_logged_in')) {
            return ['counselor', session('counselor_name', 'Counselor')];
        }
        if (session('client_logged_in')) {
            return ['client', session('client_profile.username', 'Client User')];
        }
        if (session('parent_logged_in')) {
            return ['parent', session('parent_profile.username', 'Parent User')];
        }
        return null;
    }

    public static function rm(int $sen): string
    {
        return 'RM ' . number_format($sen / 100, 2);
    }

    public static function sen($price): int
    {
        return (int) round(((float) $price) * 100);
    }

    private static function userKey(): ?string
    {
        $v = self::viewer();
        return $v ? $v[0] . ':' . $v[1] : null;
    }

    private static function blank(): array
    {
        return [
            'wallet'    => self::START_BALANCE,
            'purchases' => [],          // slug => timestamp
            'sub'       => ['active' => false, 'used' => [], 'renews' => null],
            'ledger'    => [[
                'at' => now()->toDateTimeString(), 'type' => 'topup',
                'desc' => 'Welcome credit (demo)', 'amount' => self::START_BALANCE,
            ]],
        ];
    }

    /** Per-viewer state. Guests get a throwaway blank state (never saved). */
    public static function state(): array
    {
        $k = self::userKey();
        if ($k === null) {
            return self::blank();
        }
        $all = session(self::KEY . '.users', []);
        if (!isset($all[$k])) {
            $all[$k] = self::blank();
            session([self::KEY . '.users' => $all]);
        }
        return $all[$k];
    }

    private static function save(array $s): void
    {
        $k = self::userKey();
        if ($k === null) {
            return;
        }
        $all = session(self::KEY . '.users', []);
        $all[$k] = $s;
        session([self::KEY . '.users' => $all]);
    }

    private static function ledger(array &$s, string $type, string $desc, int $amount): void
    {
        array_unshift($s['ledger'], [
            'at' => now()->toDateTimeString(), 'type' => $type, 'desc' => $desc, 'amount' => $amount,
        ]);
    }

    /* ---------- global (platform-wide) session figures ---------- */

    public static function globals(): array
    {
        return session(self::KEY . '.global', ['sales' => [], 'sub_revenue' => 0, 'subscribers' => 0]);
    }

    private static function saveGlobals(array $g): void
    {
        session([self::KEY . '.global' => $g]);
    }

    /* ---------- actions ---------- */

    public static function topUp(int $sen): ?string
    {
        if (!self::viewer() || $sen < 500 || $sen > 50000) {
            return 'Invalid top-up amount.';
        }
        $s = self::state();
        $s['wallet'] += $sen;
        self::ledger($s, 'topup', 'Wallet top-up (demo gateway)', $sen);
        self::save($s);
        return null;
    }

    public static function hasPurchased(string $slug): bool
    {
        return isset(self::state()['purchases'][$slug]);
    }

    /** @return string|null error message, null on success */
    public static function buy(Submission $a): ?string
    {
        if (!self::viewer()) {
            return 'Please sign in first.';
        }
        if (self::isAuthor($a)) {
            return 'You wrote this paper, so you can read it free.';
        }
        $s = self::state();
        if (isset($s['purchases'][$a->slug])) {
            return 'You already own this paper.';
        }
        $price = self::sen($a->price);
        if ($s['wallet'] < $price) {
            return 'Not enough balance. Please top up your wallet.';
        }
        $s['wallet'] -= $price;
        $s['purchases'][$a->slug] = now()->toDateTimeString();
        self::ledger($s, 'purchase', 'Bought: ' . $a->title, -$price);
        self::save($s);

        $g = self::globals();
        $g['sales'][$a->slug] = ($g['sales'][$a->slug] ?? 0) + 1;
        self::saveGlobals($g);
        return null;
    }

    public static function subscribe(): ?string
    {
        if (!self::viewer()) {
            return 'Please sign in first.';
        }
        $s = self::state();
        if ($s['sub']['active']) {
            return 'Your subscription is already active.';
        }
        if ($s['wallet'] < self::SUB_PRICE) {
            return 'Not enough balance. Please top up your wallet.';
        }
        $s['wallet'] -= self::SUB_PRICE;
        $s['sub'] = ['active' => true, 'used' => [], 'renews' => now()->addMonth()->toDateString()];
        self::ledger($s, 'subscription', 'Monthly subscription', -self::SUB_PRICE);
        self::save($s);

        $g = self::globals();
        $g['sub_revenue'] += self::SUB_PRICE;
        $g['subscribers'] += 1;
        self::saveGlobals($g);
        return null;
    }

    /** Re-opening a paper already read this period costs no allowance. */
    public static function useAllowance(Submission $a): ?string
    {
        $s = self::state();
        if (!$s['sub']['active']) {
            return 'You need an active subscription.';
        }
        if (in_array($a->slug, $s['sub']['used'], true)) {
            return null;
        }
        if (count($s['sub']['used']) >= self::SUB_CAP) {
            return 'Monthly allowance used up. Buy this paper instead.';
        }
        $s['sub']['used'][] = $a->slug;
        self::ledger($s, 'allowance', 'Used allowance: ' . $a->title, 0);
        self::save($s);
        return null;
    }

    public static function expireSubscription(): void
    {
        $s = self::state();
        $s['sub']['active'] = false;   // 'used' kept so the demo can show what locks
        self::save($s);
    }

    public static function reset(): void
    {
        session()->forget(self::KEY);
    }

    /* ---------- access ---------- */

    public static function isAuthor(Submission $a): bool
    {
        $v = self::viewer();
        return $v && $v[0] === $a->author_role && $v[1] === $a->author_name;
    }

    /** Checked on every request, never a stored flag. */
    public static function hasAccess(Submission $a): bool
    {
        if ($a->access_type !== 'paid') {
            return true;
        }
        if (self::isAuthor($a)) {
            return true;
        }
        $s = self::state();
        if (isset($s['purchases'][$a->slug])) {
            return true;
        }
        return $s['sub']['active'] && in_array($a->slug, $s['sub']['used'], true);
    }

    /** 'author' | 'purchased' | 'subscription' | 'locked' | 'free' */
    public static function accessVia(Submission $a): string
    {
        if ($a->access_type !== 'paid') return 'free';
        if (self::isAuthor($a)) return 'author';
        $s = self::state();
        if (isset($s['purchases'][$a->slug])) return 'purchased';
        if ($s['sub']['active'] && in_array($a->slug, $s['sub']['used'], true)) return 'subscription';
        return 'locked';
    }

    /* ---------- sales / earnings ---------- */

    /** Fake baseline (stable per slug) + sales made in this demo session. */
    public static function totalSales(Submission $a): int
    {
        return (crc32($a->slug) % 10 + 4) + (self::globals()['sales'][$a->slug] ?? 0);
    }

    public static function perSaleEarning(Submission $a): int
    {
        return (int) round(self::sen($a->price) * self::AUTHOR_SHARE);
    }

    /** Total author earnings (sen) for one paper. Subscription reads earn nothing. */
    public static function authorEarning(Submission $a): int
    {
        return $a->access_type === 'paid' ? self::perSaleEarning($a) * self::totalSales($a) : 0;
    }
}
