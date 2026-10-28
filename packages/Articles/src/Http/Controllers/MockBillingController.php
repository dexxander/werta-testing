<?php

namespace Articles\Http\Controllers;

use App\Support\DemoAuth;
use Articles\Support\MockBilling;
use Articles\Support\Promotions;
use Articles\Support\Referrals;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Submissions\Models\Submission;

/** DEMO ONLY: fake token wallet, purchases, gifts, subscription, promos and referrals. */
class MockBillingController extends Controller
{
    public function wallet()
    {
        $viewer = MockBilling::viewer();
        $state  = MockBilling::state();

        $owned = Submission::whereIn('slug', array_keys($state['purchases']))->get()->keyBy('slug');
        $library = collect($state['purchases'])
            ->map(fn ($p, $slug) => $owned->has($slug) ? (object) (['paper' => $owned[$slug]] + $p) : null)
            ->filter()
            ->sortByDesc('at')
            ->values();
        $allowancePapers = Submission::whereIn('slug', $state['sub']['used'])->get();

        $promos = Promotions::live();
        $partners = Promotions::upcomingPartners();
        $myCode = Referrals::myCode();
        $referredByName = $state['referred_by'] ? explode(':', $state['referred_by'], 2)[1] : null;
        $prefill = session('referral_prefill');

        return view('articles::wallet', compact(
            'viewer', 'state', 'library', 'allowancePapers', 'promos', 'partners', 'myCode', 'referredByName', 'prefill'
        ));
    }

    public function topUp(Request $r)
    {
        $err = MockBilling::topUp((string) $r->input('pack'));
        $pack = MockBilling::PACKS[$r->input('pack')] ?? null;
        $ok = $pack ? __(':tokens tokens added (demo payment of :price).', ['tokens' => $pack['tokens'] + $pack['bonus'], 'price' => MockBilling::rm($pack['price'])]) : '';

        return $this->back($r, $err, $ok);
    }

    public function promo(Request $r)
    {
        $code = strtoupper(trim((string) $r->input('code')));
        $err = MockBilling::redeemPromo($code);

        return $this->back($r, $err, __('Promo :code applied.', ['code' => $code]));
    }

    public function referral(Request $r)
    {
        $err = MockBilling::applyReferral((string) $r->input('code'));
        if (! $err) {
            session()->forget('referral_prefill');
        }

        return $this->back($r, $err, __('Referral code linked. You both get bonus tokens after your first top-up.'));
    }

    /** Shareable link: /r/{code} remembers the code, then sends the visitor on. */
    public function referralLink(string $code)
    {
        if (Referrals::ownerOf($code)) {
            session(['referral_prefill' => strtoupper($code)]);
        }
        if (! DemoAuth::current()) {
            return redirect()->route(DemoAuth::enabled() ? 'demo.index' : 'public.articles')
                ->with('mock_ok', __('Referral code saved. Sign in, then add it in your wallet before your first top-up.'));
        }

        return redirect()->route('mock.wallet');
    }

    public function buy(Request $r, $slug)
    {
        $a = Submission::published()->where('slug', $slug)->where('access_type', 'paid')->firstOrFail();
        $err = MockBilling::buy($a);

        return $err
            ? back()->with('mock_error', $err)
            : redirect()->route('public.articles.show', $slug)->with('mock_ok', __('Unlocked for :n tokens. This paper is yours to keep.', ['n' => MockBilling::ARTICLE_TOKENS]));
    }

    public function gift(Request $r, $slug)
    {
        $a = Submission::published()->where('slug', $slug)->where('access_type', 'paid')->firstOrFail();
        $data = $r->validate([
            'recipient' => 'required|string',
            'note'      => 'nullable|string|max:140',
        ]);
        $err = MockBilling::gift($a, $data['recipient'], $data['note'] ?? null);
        $name = DemoAuth::readerAccounts()[$data['recipient']]['name'] ?? '';

        return $err
            ? back()->with('mock_error', $err)
            : redirect()->route('mock.wallet')->with('mock_ok', __('Gift sent. “:title” is now in :name\'s library.', ['title' => $a->title, 'name' => $name]));
    }

    public function allowance(Request $r, $slug)
    {
        $a = Submission::published()->where('slug', $slug)->where('access_type', 'paid')->firstOrFail();
        $err = MockBilling::useAllowance($a);

        return $err
            ? back()->with('mock_error', $err)
            : redirect()->route('public.articles.show', $slug)->with('mock_ok', __('Unlocked with your subscription allowance.'));
    }

    public function subscribe(Request $r)
    {
        return $this->back($r, MockBilling::subscribe(), __('Subscription started (demo).'));
    }

    public function expire(Request $r)
    {
        MockBilling::expireSubscription();

        return back()->with('mock_ok', __('Subscription expired (simulated). Subscription-only papers are locked again.'));
    }

    public function reset()
    {
        MockBilling::reset();

        return redirect()->route('mock.wallet')->with('mock_ok', __('Demo data reset.'));
    }

    private function back(Request $r, ?string $err, string $ok)
    {
        $to = $r->input('return');
        $redirect = ($to && str_starts_with($to, '/') && ! str_starts_with($to, '//')) ? redirect($to) : back();

        return $err ? $redirect->with('mock_error', $err) : $redirect->with('mock_ok', $ok);
    }
}
