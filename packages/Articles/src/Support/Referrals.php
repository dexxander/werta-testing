<?php

namespace Articles\Support;

use App\Support\DemoAuth;

/**
 * DEMO ONLY: referral hook. The rules are a draft and will change, so they live
 * in one array instead of being spread through the code.
 *
 * Current draft: when a referred reader makes their first PAID top-up, both
 * people get bonus tokens. Rewarding only after a paid top-up stops fake
 * sign-ups from earning anything.
 *
 * Later this becomes: users.referral_code, referrals table, and a RewardService.
 */
class Referrals
{
    public const RULES = [
        'status'          => 'draft',            // rules still being decided
        'trigger'         => 'first_paid_topup', // when the reward is paid
        'referrer_tokens' => 50,                 // to the person who shared the code
        'referee_tokens'  => 25,                 // to the new reader
        'monthly_cap'     => 10,                 // max rewarded referrals per referrer (not enforced in the mock)
    ];

    /** Stable code per reader account, e.g. WT-3K9F2A. */
    public static function codeFor(string $key): string
    {
        return 'WT-' . strtoupper(substr(base_convert((string) crc32('werta-ref:' . $key), 10, 36), 0, 6));
    }

    public static function myCode(): ?string
    {
        $key = DemoAuth::key();

        return $key ? self::codeFor($key) : null;
    }

    /** Find which demo reader owns a code. */
    public static function ownerOf(string $code): ?string
    {
        $code = strtoupper(trim($code));
        $keys = array_unique(array_merge(array_keys(DemoAuth::readerAccounts()), array_keys(MockBilling::allStates())));
        foreach ($keys as $key) {
            if (self::codeFor($key) === $code) {
                return $key;
            }
        }

        return null;
    }

    /** Pay both sides once the referred reader has made their first paid top-up. */
    public static function rewardIfDue(?string $refereeKey): void
    {
        if (! $refereeKey) {
            return;
        }
        $s = MockBilling::stateFor($refereeKey);
        if (! $s['referred_by'] || $s['ref_rewarded'] || $s['topups'] < 1) {
            return;
        }

        [$role, $name] = explode(':', $refereeKey, 2);
        [, $referrerName] = explode(':', $s['referred_by'], 2);

        MockBilling::grantBonusTo(
            $refereeKey, self::RULES['referee_tokens'], 'referral',
            __('Referral bonus (invited by :name)', ['name' => $referrerName]),
            function (array &$state) {
                $state['ref_rewarded'] = true;
            }
        );
        MockBilling::grantBonusTo(
            $s['referred_by'], self::RULES['referrer_tokens'], 'referral',
            __('Referral bonus (:name joined and topped up)', ['name' => $name])
        );

        $g = MockBilling::globals();
        $g['referrals']++;
        MockBilling::saveGlobals($g);
    }
}
