<?php

namespace Articles\Support;

/**
 * DEMO ONLY: promotional offers. Each row mirrors the planned `promotions` table:
 * code, type, value, start/end dates, per-user cap, and a nullable partner.
 *
 * Types:
 *  - flat_tokens:     a fixed number of bonus tokens on redeem
 *  - bonus_pct:       extra bonus tokens (% of paid tokens) on the next top-up
 *  - partner_voucher: spend tokens on a partner's voucher (phase 2, not live yet)
 *
 * Partner rows are placeholders only. Real partners are added once agreements exist.
 */
class Promotions
{
    public static function all(): array
    {
        return [
            [
                'code' => 'WELCOME50', 'type' => 'flat_tokens', 'value' => 50,
                'title' => 'Welcome gift',
                'desc' => '50 bonus tokens, enough for your first premium paper.',
                'starts' => '2026-10-01', 'ends' => '2026-12-31',
                'per_user' => 1, 'new_users_only' => true, 'partner' => null, 'active' => true,
            ],
            [
                'code' => 'EXAM20', 'type' => 'bonus_pct', 'value' => 20,
                'title' => 'Exam season boost',
                'desc' => '+20% bonus tokens on your next top-up.',
                'starts' => '2026-10-15', 'ends' => '2026-11-30',
                'per_user' => 1, 'new_users_only' => false, 'partner' => null, 'active' => true,
            ],
            [
                'code' => null, 'type' => 'partner_voucher', 'value' => 150,
                'title' => 'Partner café voucher',
                'desc' => 'Swap 150 tokens for a drink voucher at a partner café.',
                'starts' => null, 'ends' => null,
                'per_user' => null, 'new_users_only' => false, 'partner' => 'Partner café (example)', 'active' => false,
            ],
            [
                'code' => null, 'type' => 'partner_voucher', 'value' => 200,
                'title' => 'Partner meal deal',
                'desc' => 'Swap 200 tokens for a meal voucher at a partner restaurant.',
                'starts' => null, 'ends' => null,
                'per_user' => null, 'new_users_only' => false, 'partner' => 'Partner restaurant (example)', 'active' => false,
            ],
        ];
    }

    public static function find(?string $code): ?array
    {
        $code = strtoupper(trim((string) $code));
        if ($code === '') {
            return null;
        }
        foreach (self::all() as $p) {
            if ($p['code'] === $code) {
                return $p;
            }
        }

        return null;
    }

    public static function isLive(array $p): bool
    {
        if (! $p['active'] || ! $p['code']) {
            return false;
        }
        $today = now()->toDateString();

        return (! $p['starts'] || $p['starts'] <= $today) && (! $p['ends'] || $p['ends'] >= $today);
    }

    /** Offers a reader can use now. */
    public static function live(): array
    {
        return array_values(array_filter(self::all(), [self::class, 'isLive']));
    }

    /** Partner offers that are planned but not live. */
    public static function upcomingPartners(): array
    {
        return array_values(array_filter(self::all(), fn ($p) => $p['partner'] && ! $p['active']));
    }
}
