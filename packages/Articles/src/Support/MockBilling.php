<?php

namespace Articles\Support;

use App\Support\DemoAuth;
use Submissions\Models\Submission;

/**
 * DEMO ONLY. Session-backed token wallet. No real money, no DB tables.
 *
 * Money is integer sen (RM 1.00 = 100). The wallet holds tokens (1 token = 10 sen).
 * Paid tokens (bought) and bonus tokens (promos, pack freebies, referrals) are
 * tracked separately: bonus tokens expire and are spent first, paid tokens never expire.
 * The same tokens pay for articles, gifts and the subscription, and later other
 * Werta products (courses, sessions, partner vouchers).
 *
 * Replace with real wallet / purchases / ledger tables once the model is confirmed.
 */
class MockBilling
{
    /* ---------- pricing (decided with supervisor, still adjustable) ---------- */

    public const TOKEN_SEN         = 10;     // 1 token = 10 sen
    public const ARTICLE_TOKENS    = 50;     // every premium paper, any category: RM 5
    public const AUTHOR_PER_COPY   = 100;    // author earns RM 1 per copy bought (sen)
    public const PUBLISH_FEE       = 8000;   // RM 80, charged only after acceptance (sen)
    public const SUB_TOKENS        = 199;    // RM 19.90 / month
    public const SUB_CAP           = 5;      // papers per month
    public const BONUS_EXPIRY_DAYS = 90;     // bonus tokens expire, paid tokens do not
    public const GATEWAY_RATE      = 0.03;   // assumed gateway fee on cash taken
    public const MIN_WITHDRAW      = 2000;   // RM 20 minimum author withdrawal (sen)
    public const HOLD_DAYS         = 14;     // earnings held before withdrawal

    /** Top-up packs. price in sen, tokens = paid tokens, bonus = free bonus tokens. */
    public const PACKS = [
        'single' => ['name' => 'Single',     'price' => 500,  'tokens' => 50,  'bonus' => 0,   'articles' => 1,  'tag' => null],
        'double' => ['name' => 'Double',     'price' => 1000, 'tokens' => 100, 'bonus' => 0,   'articles' => 2,  'tag' => null],
        'five'   => ['name' => 'Five + 1',   'price' => 2500, 'tokens' => 250, 'bonus' => 50,  'articles' => 6,  'tag' => 'Most popular'],
        'ten'    => ['name' => 'Ten + 2',    'price' => 5000, 'tokens' => 500, 'bonus' => 100, 'articles' => 12, 'tag' => 'Best value'],
    ];

    private const KEY = 'mock_billing';

    /* ---------- identity ---------- */

    public static function viewer(): ?array
    {
        return DemoAuth::current();
    }

    private static function userKey(): ?string
    {
        return DemoAuth::key();
    }

    /* ---------- formatting ---------- */

    public static function rm(int $sen): string
    {
        return 'RM ' . number_format($sen / 100, 2);
    }

    public static function sen($price): int
    {
        return (int) round(((float) $price) * 100);
    }

    public static function tokensToSen(int $tokens): int
    {
        return $tokens * self::TOKEN_SEN;
    }

    /** Fixed article price in sen (RM 5). */
    public static function articlePrice(): int
    {
        return self::tokensToSen(self::ARTICLE_TOKENS);
    }

    /** Fixed article price in RM, as stored in submissions.price for paid papers. */
    public static function articlePriceRm(): float
    {
        return self::articlePrice() / 100;
    }

    public static function subPrice(): int
    {
        return self::tokensToSen(self::SUB_TOKENS);
    }

    /** Effective price per article for a pack, in sen. */
    public static function perArticle(array $pack): int
    {
        return (int) round($pack['price'] / max(1, $pack['articles']));
    }

    /* ---------- per-user state ---------- */

    private static function blank(?string $key = null): array
    {
        return [
            'paid'          => 0,
            'bonus'         => 0,
            'bonus_expires' => null,
            'purchases'     => [],   // slug => ['at', 'via' => purchase|gift, 'from' => ?string, 'tokens' => int]
            'sub'           => ['active' => false, 'used' => [], 'renews' => null],
            'promos'        => [],   // code => redeemed_at
            'armed'         => null, // bonus_pct promo waiting for the next top-up
            'referred_by'   => null, // referrer user key
            'ref_rewarded'  => false,
            'topups'        => 0,
            'ledger'        => [],
        ];
    }

    /** State of the signed-in reader. Guests get a throwaway blank state (never saved). */
    public static function state(): array
    {
        $k = self::userKey();

        return $k === null ? self::blank() : self::stateFor($k);
    }

    public static function stateFor(string $key): array
    {
        $all = session(self::KEY . '.users', []);
        $s = array_replace(self::blank($key), $all[$key] ?? []);

        // Bonus tokens lapse after their expiry date.
        if ($s['bonus'] > 0 && $s['bonus_expires'] && $s['bonus_expires'] < now()->toDateString()) {
            self::ledger($s, 'expiry', 'Bonus tokens expired', 0, -$s['bonus']);
            $s['bonus'] = 0;
            $s['bonus_expires'] = null;
            self::saveFor($key, $s);
        } elseif (! isset($all[$key])) {
            self::saveFor($key, $s);
        }

        return $s;
    }

    private static function save(array $s): void
    {
        $k = self::userKey();
        if ($k !== null) {
            self::saveFor($k, $s);
        }
    }

    private static function saveFor(string $key, array $s): void
    {
        $all = session(self::KEY . '.users', []);
        $all[$key] = $s;
        session([self::KEY . '.users' => $all]);
    }

    /** All reader states touched in this demo session, keyed by user key. */
    public static function allStates(): array
    {
        return session(self::KEY . '.users', []);
    }

    public static function balance(?array $s = null): int
    {
        $s ??= self::state();

        return $s['paid'] + $s['bonus'];
    }

    public static function ledger(array &$s, string $type, string $desc, int $paid, int $bonus = 0, ?int $money = null): void
    {
        array_unshift($s['ledger'], [
            'at' => now()->toDateTimeString(), 'type' => $type, 'desc' => $desc,
            'paid' => $paid, 'bonus' => $bonus, 'money' => $money,
        ]);
    }

    /** Add bonus tokens and push the expiry date out. */
    public static function grantBonus(array &$s, int $tokens, string $type, string $desc): void
    {
        if ($tokens <= 0) {
            return;
        }
        $s['bonus'] += $tokens;
        $s['bonus_expires'] = now()->addDays(self::BONUS_EXPIRY_DAYS)->toDateString();
        self::ledger($s, $type, $desc, 0, $tokens);

        $g = self::globals();
        $g['tokens_bonus_issued'] += $tokens;
        self::saveGlobals($g);
    }

    /** Spend tokens, bonus first. Returns the split, or null when the balance is too low. */
    private static function spend(array &$s, int $tokens): ?array
    {
        if (self::balance($s) < $tokens) {
            return null;
        }
        $fromBonus = min($s['bonus'], $tokens);
        $fromPaid = $tokens - $fromBonus;
        $s['bonus'] -= $fromBonus;
        $s['paid'] -= $fromPaid;

        $g = self::globals();
        $g['tokens_bonus_spent'] += $fromBonus;
        $g['tokens_paid_spent'] += $fromPaid;
        self::saveGlobals($g);

        return ['paid' => $fromPaid, 'bonus' => $fromBonus];
    }

    /* ---------- platform-wide session figures ---------- */

    public static function globals(): array
    {
        return array_replace([
            'sales'               => [],  // slug => copies sold in this demo (purchases + gifts)
            'gifts'               => 0,
            'topup_sen'           => 0,
            'topups'              => 0,
            'pack_counts'         => [],  // pack id => count
            'tokens_paid_issued'  => 0,
            'tokens_bonus_issued' => 0,
            'tokens_paid_spent'   => 0,
            'tokens_bonus_spent'  => 0,
            'sub_tokens'          => 0,
            'subscribers'         => 0,
            'promo'               => [],  // code => redemptions
            'referrals'           => 0,
            'fees_paid'           => [],  // slug => date
            'withdrawals'         => [],  // author key => [[at, sen]]
        ], session(self::KEY . '.global', []));
    }

    public static function saveGlobals(array $g): void
    {
        session([self::KEY . '.global' => $g]);
    }

    /* ---------- top-ups, promos, referrals ---------- */

    public static function topUp(string $packId): ?string
    {
        if (! self::viewer()) {
            return __('Please sign in first.');
        }
        $pack = self::PACKS[$packId] ?? null;
        if (! $pack) {
            return __('Unknown top-up pack.');
        }

        $s = self::state();
        $s['paid'] += $pack['tokens'];
        $s['topups']++;
        self::ledger($s, 'topup', __(':name pack (demo gateway)', ['name' => __($pack['name'])]), $pack['tokens'], 0, $pack['price']);

        $g = self::globals();
        $g['topup_sen'] += $pack['price'];
        $g['topups']++;
        $g['tokens_paid_issued'] += $pack['tokens'];
        $g['pack_counts'][$packId] = ($g['pack_counts'][$packId] ?? 0) + 1;
        self::saveGlobals($g);

        if ($pack['bonus'] > 0) {
            self::grantBonus($s, $pack['bonus'], 'bonus', __('Pack bonus: :n free tokens', ['n' => $pack['bonus']]));
        }

        // A bonus-% promo applies to this top-up, then is used up.
        if ($s['armed'] && ($promo = Promotions::find($s['armed'])) && $promo['type'] === 'bonus_pct') {
            $extra = (int) floor($pack['tokens'] * $promo['value'] / 100);
            self::grantBonus($s, $extra, 'promo', __('Promo :code: +:n bonus tokens', ['code' => $promo['code'], 'n' => $extra]));
            $s['armed'] = null;
        }

        self::save($s);

        // Referral reward fires on the referred person's first paid top-up.
        if ($s['topups'] === 1) {
            Referrals::rewardIfDue(self::userKey());
        }

        return null;
    }

    /** @return string|null error message, null on success */
    public static function redeemPromo(string $code): ?string
    {
        if (! self::viewer()) {
            return __('Please sign in first.');
        }
        $promo = Promotions::find($code);
        if (! $promo || ! Promotions::isLive($promo)) {
            return __('That promo code is not valid right now.');
        }
        $s = self::state();
        if (isset($s['promos'][$promo['code']])) {
            return __('You have already used this promo code.');
        }
        if (($promo['new_users_only'] ?? false) && $s['topups'] > 0) {
            return __('This promo is for new readers only.');
        }

        $s['promos'][$promo['code']] = now()->toDateTimeString();
        if ($promo['type'] === 'flat_tokens') {
            self::grantBonus($s, $promo['value'], 'promo', __('Promo :code: :n bonus tokens', ['code' => $promo['code'], 'n' => $promo['value']]));
        } elseif ($promo['type'] === 'bonus_pct') {
            $s['armed'] = $promo['code'];
            self::ledger($s, 'promo', __('Promo :code ready: +:p% on your next top-up', ['code' => $promo['code'], 'p' => $promo['value']]), 0);
        }
        self::save($s);

        $g = self::globals();
        $g['promo'][$promo['code']] = ($g['promo'][$promo['code']] ?? 0) + 1;
        self::saveGlobals($g);

        return null;
    }

    /** Store who referred this reader. The reward is paid later (see Referrals). */
    public static function applyReferral(string $code): ?string
    {
        $key = self::userKey();
        if (! $key) {
            return __('Please sign in first.');
        }
        $referrer = Referrals::ownerOf($code);
        if (! $referrer) {
            return __('That referral code was not found.');
        }
        if ($referrer === $key) {
            return __('You cannot use your own referral code.');
        }
        $s = self::state();
        if ($s['referred_by']) {
            return __('A referral code is already linked to your account.');
        }
        if ($s['topups'] > 0) {
            return __('Referral codes must be added before your first top-up.');
        }
        $s['referred_by'] = $referrer;
        self::ledger($s, 'referral', __('Referral code linked. Bonus arrives after your first top-up.'), 0);
        self::save($s);

        return null;
    }

    /** Used by Referrals to pay a bonus into any reader's wallet. */
    public static function grantBonusTo(string $key, int $tokens, string $type, string $desc, ?callable $mutate = null): void
    {
        $s = self::stateFor($key);
        self::grantBonus($s, $tokens, $type, $desc);
        if ($mutate) {
            $mutate($s);
        }
        self::saveFor($key, $s);
    }

    /* ---------- articles ---------- */

    public static function hasPurchased(string $slug): bool
    {
        return isset(self::state()['purchases'][$slug]);
    }

    private static function recordSale(Submission $a): void
    {
        $g = self::globals();
        $g['sales'][$a->slug] = ($g['sales'][$a->slug] ?? 0) + 1;
        self::saveGlobals($g);
    }

    /** @return string|null error message, null on success */
    public static function buy(Submission $a): ?string
    {
        if (! self::viewer()) {
            return __('Please sign in first.');
        }
        if (self::isAuthor($a)) {
            return __('You wrote this paper, so you can read it free.');
        }
        $s = self::state();
        if (isset($s['purchases'][$a->slug])) {
            return __('You already own this paper.');
        }
        $split = self::spend($s, self::ARTICLE_TOKENS);
        if ($split === null) {
            return __('Not enough tokens. Please top up your wallet.');
        }
        $s['purchases'][$a->slug] = ['at' => now()->toDateTimeString(), 'via' => 'purchase', 'from' => null, 'tokens' => self::ARTICLE_TOKENS];
        self::ledger($s, 'purchase', __('Bought: :title', ['title' => $a->title]), -$split['paid'], -$split['bonus']);
        self::save($s);
        self::recordSale($a);

        return null;
    }

    /**
     * Gift a paper to another reader. Counts as a copy sold, so the author still earns RM 1.
     * @return string|null error message, null on success
     */
    public static function gift(Submission $a, string $recipientKey, ?string $note = null): ?string
    {
        $me = self::userKey();
        if (! $me) {
            return __('Please sign in first.');
        }
        $accounts = DemoAuth::readerAccounts();
        if (! isset($accounts[$recipientKey]) || $recipientKey === $me) {
            return __('Choose someone else to receive the gift.');
        }
        $to = $accounts[$recipientKey];
        if ($to['role'] === $a->author_role && $to['name'] === $a->author_name) {
            return __('The author already has free access to this paper.');
        }
        $theirs = self::stateFor($recipientKey);
        if (isset($theirs['purchases'][$a->slug])) {
            return __(':name already owns this paper.', ['name' => $to['name']]);
        }

        $s = self::state();
        $split = self::spend($s, self::ARTICLE_TOKENS);
        if ($split === null) {
            return __('Not enough tokens. Please top up your wallet.');
        }
        self::ledger($s, 'gift', __('Gift to :name: :title', ['name' => $to['name'], 'title' => $a->title]), -$split['paid'], -$split['bonus']);
        self::save($s);

        $from = self::viewer()[1];
        $theirs['purchases'][$a->slug] = [
            'at' => now()->toDateTimeString(), 'via' => 'gift', 'from' => $from,
            'tokens' => self::ARTICLE_TOKENS, 'note' => $note ? mb_substr($note, 0, 140) : null,
        ];
        self::ledger($theirs, 'gift_in', __('Gift from :name: :title', ['name' => $from, 'title' => $a->title]), 0);
        self::saveFor($recipientKey, $theirs);

        self::recordSale($a);
        $g = self::globals();
        $g['gifts']++;
        self::saveGlobals($g);

        return null;
    }

    public static function subscribe(): ?string
    {
        if (! self::viewer()) {
            return __('Please sign in first.');
        }
        $s = self::state();
        if ($s['sub']['active']) {
            return __('Your subscription is already active.');
        }
        $split = self::spend($s, self::SUB_TOKENS);
        if ($split === null) {
            return __('Not enough tokens. Please top up your wallet.');
        }
        $s['sub'] = ['active' => true, 'used' => [], 'renews' => now()->addMonth()->toDateString()];
        self::ledger($s, 'subscription', __('Monthly subscription'), -$split['paid'], -$split['bonus']);
        self::save($s);

        $g = self::globals();
        $g['sub_tokens'] += self::SUB_TOKENS;
        $g['subscribers']++;
        self::saveGlobals($g);

        return null;
    }

    /** Re-opening a paper already read this period costs no allowance. */
    public static function useAllowance(Submission $a): ?string
    {
        $s = self::state();
        if (! $s['sub']['active']) {
            return __('You need an active subscription.');
        }
        if (in_array($a->slug, $s['sub']['used'], true)) {
            return null;
        }
        if (count($s['sub']['used']) >= self::SUB_CAP) {
            return __('Monthly allowance used up. Buy this paper instead.');
        }
        $s['sub']['used'][] = $a->slug;
        self::ledger($s, 'allowance', __('Used allowance: :title', ['title' => $a->title]), 0);
        self::save($s);

        return null;
    }

    public static function expireSubscription(): void
    {
        $s = self::state();
        $s['sub']['active'] = false;   // 'used' kept so the demo can show what locks
        self::save($s);
    }

    /** Author pays the publish fee for an accepted premium paper (demo gateway). */
    public static function payPublishFee(Submission $a): ?string
    {
        if (! self::isAuthor($a)) {
            return __('Only the author can pay this fee.');
        }
        if ($a->access_type !== 'paid' || $a->status !== 'accepted') {
            return __('This paper has no publish fee due.');
        }
        $a->update(['status' => 'published']);

        $g = self::globals();
        $g['fees_paid'][$a->slug] = now()->toDateString();
        self::saveGlobals($g);

        return null;
    }

    /** Author asks to withdraw earnings (demo: recorded, not paid). */
    public static function requestWithdrawal(int $sen): ?string
    {
        $key = self::userKey();
        if (! $key) {
            return __('Please sign in first.');
        }
        if ($sen < self::MIN_WITHDRAW) {
            return __('The minimum withdrawal is :min.', ['min' => self::rm(self::MIN_WITHDRAW)]);
        }
        $g = self::globals();
        $g['withdrawals'][$key][] = ['at' => now()->toDateTimeString(), 'sen' => $sen];
        self::saveGlobals($g);

        return null;
    }

    public static function withdrawnBy(string $key): int
    {
        return (int) array_sum(array_column(self::globals()['withdrawals'][$key] ?? [], 'sen'));
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
        return self::accessVia($a) !== 'locked';
    }

    /** 'free' | 'author' | 'purchased' | 'gift' | 'subscription' | 'locked' */
    public static function accessVia(Submission $a): string
    {
        if ($a->access_type !== 'paid') {
            return 'free';
        }
        if (self::isAuthor($a)) {
            return 'author';
        }
        $s = self::state();
        if (isset($s['purchases'][$a->slug])) {
            return ($s['purchases'][$a->slug]['via'] ?? 'purchase') === 'gift' ? 'gift' : 'purchased';
        }
        if ($s['sub']['active'] && in_array($a->slug, $s['sub']['used'], true)) {
            return 'subscription';
        }

        return 'locked';
    }

    /* ---------- sales / earnings ---------- */

    /** Fake baseline (stable per slug) + copies sold in this demo session. Only premium papers sell. */
    public static function totalSales(Submission $a): int
    {
        if ($a->access_type !== 'paid' || $a->status !== 'published') {
            return 0;
        }

        return DemoStats::baselineSales($a) + self::sessionSales($a);
    }

    public static function sessionSales(Submission $a): int
    {
        return self::globals()['sales'][$a->slug] ?? 0;
    }

    /** Author earnings (sen) for one paper: RM 1 per copy. Subscription reads earn nothing. */
    public static function authorEarning(Submission $a): int
    {
        return self::AUTHOR_PER_COPY * self::totalSales($a);
    }

    /** Copies needed to recoup the RM 80 publish fee at RM 1 each. */
    public static function breakEvenCopies(): int
    {
        return (int) ceil(self::PUBLISH_FEE / self::AUTHOR_PER_COPY);
    }
}
