<?php

namespace Articles\Http\Controllers;

use Articles\Support\MockBilling;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Submissions\Models\Submission;

/** DEMO ONLY: fake wallet / purchases / subscription. */
class MockBillingController extends Controller
{
    public function wallet()
    {
        $viewer = MockBilling::viewer();
        $state  = MockBilling::state();
        $library = Submission::whereIn('slug', array_keys($state['purchases']))->get()->keyBy('slug');
        $allowancePapers = Submission::whereIn('slug', $state['sub']['used'])->get();

        return view('articles::wallet', compact('viewer', 'state', 'library', 'allowancePapers'));
    }

    public function topUp(Request $r)
    {
        $amount = (int) round(((float) $r->input('amount', 0)) * 100);
        $err = MockBilling::topUp($amount);
        return $this->back($r, $err, 'Wallet topped up (demo).');
    }

    public function buy(Request $r, $slug)
    {
        $a = Submission::where('slug', $slug)->where('access_type', 'paid')->firstOrFail();
        $err = MockBilling::buy($a);
        return $err
            ? back()->with('mock_error', $err)
            : redirect()->route('public.articles.show', $slug)->with('mock_ok', 'Purchase complete. Paper unlocked forever.');
    }

    public function allowance(Request $r, $slug)
    {
        $a = Submission::where('slug', $slug)->where('access_type', 'paid')->firstOrFail();
        $err = MockBilling::useAllowance($a);
        return $err
            ? back()->with('mock_error', $err)
            : redirect()->route('public.articles.show', $slug)->with('mock_ok', 'Unlocked with your subscription allowance.');
    }

    public function subscribe(Request $r)
    {
        return $this->back($r, MockBilling::subscribe(), 'Subscription started (demo).');
    }

    public function expire(Request $r)
    {
        MockBilling::expireSubscription();
        return back()->with('mock_ok', 'Subscription expired (simulated). Subscription-only papers are locked again.');
    }

    public function reset()
    {
        MockBilling::reset();
        return redirect()->route('mock.wallet')->with('mock_ok', 'Demo data reset.');
    }

    private function back(Request $r, ?string $err, string $ok)
    {
        $to = $r->input('return');
        $redirect = ($to && str_starts_with($to, '/') && !str_starts_with($to, '//')) ? redirect($to) : back();
        return $err ? $redirect->with('mock_error', $err) : $redirect->with('mock_ok', $ok);
    }
}
