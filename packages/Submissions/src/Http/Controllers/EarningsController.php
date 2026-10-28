<?php

namespace Submissions\Http\Controllers;

use App\Support\DemoAuth;
use Articles\Support\DemoStats;
use Articles\Support\MockBilling as MB;
use Illuminate\Routing\Controller;
use Submissions\Models\Submission;

/**
 * DEMO ONLY: author earnings built on MockBilling's fake sales.
 * RM 1 per copy bought (or gifted). Subscription reads earn nothing.
 */
class EarningsController extends Controller
{
    public function index()
    {
        $viewer = DemoAuth::current();
        if ($viewer === null) {
            return response()->view('submissions::sign-in', ['isStaff' => (bool) session('staff_role')]);
        }

        $f = $this->figures($viewer);

        // Monthly copies across all papers: the demo baseline spread over six months,
        // plus copies sold during this session in the current month (same as the admin charts).
        $labels = DemoStats::monthLabels();
        $counts = array_fill(0, count($labels), 0);
        foreach ($f['papers'] as $row) {
            if ($row->copies > 0) {
                $i = 0;
                foreach (DemoStats::monthly(DemoStats::baselineSales($row->paper), $row->paper->slug) as $n) {
                    $counts[$i++] += $n;
                }
                $counts[count($labels) - 1] += $row->live;
            }
        }
        $monthly = array_combine($labels, $counts);

        return view('submissions::earnings', $f + ['viewer' => $viewer, 'monthly' => $monthly]);
    }

    /** DEMO: request a payout of everything that is withdrawable right now. */
    public function withdraw()
    {
        $viewer = DemoAuth::current();
        if ($viewer === null) {
            return redirect()->route('submissions.earnings');
        }
        $amount = $this->figures($viewer)['withdrawable'];
        $err = MB::requestWithdrawal($amount);

        return $err
            ? back()->with('mock_error', $err)
            : back()->with('mock_ok', __('Withdrawal of :amount requested (demo). Payouts reach your bank in 3–5 working days.', ['amount' => MB::rm($amount)]));
    }

    private function figures(array $viewer): array
    {
        $breakEven = MB::breakEvenCopies();

        $papers = Submission::where('author_role', $viewer[0])
            ->where('author_name', $viewer[1])
            ->where('access_type', 'paid')
            ->latest('submitted_at')
            ->get()
            ->map(function ($p) use ($breakEven) {
                $copies  = MB::totalSales($p);
                $earned  = MB::authorEarning($p);
                $feePaid = $p->status === 'published';          // fee is charged after acceptance

                return (object) [
                    'paper'    => $p,
                    'copies'   => $copies,
                    'subReads' => DemoStats::subscriptionReads($p),
                    'earned'   => $earned,
                    'feePaid'  => $feePaid,
                    'feeDue'   => $p->status === 'accepted',
                    'net'      => $earned - ($feePaid ? MB::PUBLISH_FEE : 0),
                    'progress' => $feePaid ? min(100, (int) floor($copies / $breakEven * 100)) : 0,
                    'left'     => $feePaid ? max(0, $breakEven - $copies) : null,
                    'live'     => MB::sessionSales($p),
                ];
            });

        $totalEarned = $papers->sum('earned');
        $feesPaid    = $papers->where('feePaid', true)->count() * MB::PUBLISH_FEE;
        $withdrawn   = MB::withdrawnBy(DemoAuth::key($viewer));
        $onHold      = (int) round($totalEarned * 0.25);          // mock: the last 14 days are still on hold

        return [
            'papers'       => $papers,
            'breakEven'    => $breakEven,
            'totalEarned'  => $totalEarned,
            'copies'       => $papers->sum('copies'),
            'feesPaid'     => $feesPaid,
            'net'          => $totalEarned - $feesPaid,
            'withdrawn'    => $withdrawn,
            'onHold'       => $onHold,
            'withdrawable' => max(0, $totalEarned - $onHold - $withdrawn),
        ];
    }
}
