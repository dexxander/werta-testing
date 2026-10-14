<?php

namespace Submissions\Http\Controllers;

use Articles\Support\MockBilling as MB;
use Illuminate\Routing\Controller;
use Submissions\Models\Submission;

/** DEMO ONLY: author earnings built on MockBilling's fake sales. */
class EarningsController extends Controller
{
    public function index()
    {
        $viewer = MB::viewer();
        if ($viewer === null) {
            return response()->view('submissions::sign-in', ['isStaff' => (bool) session('staff_role')]);
        }

        $papers = Submission::where('author_role', $viewer[0])
            ->where('author_name', $viewer[1])
            ->where('access_type', 'paid')
            ->latest()
            ->get()
            ->map(function ($p) {
                $feePaid = $p->status === 'published';          // fee is charged after acceptance
                $earned  = MB::authorEarning($p);
                $net     = $earned - ($feePaid ? MB::PUBLISH_FEE : 0);
                $per     = max(1, MB::perSaleEarning($p));
                return (object) [
                    'paper'    => $p,
                    'sales'    => MB::totalSales($p),
                    'earned'   => $earned,
                    'feePaid'  => $feePaid,
                    'feeDue'   => $p->status === 'accepted',
                    'net'      => $net,
                    'progress' => $feePaid ? min(100, (int) floor($earned / MB::PUBLISH_FEE * 100)) : 0,
                    'left'     => $feePaid ? max(0, (int) ceil((MB::PUBLISH_FEE - $earned) / $per)) : null,
                    'per'      => $per,
                ];
            });

        $totalEarned = $papers->sum('earned');
        $feesPaid    = $papers->where('feePaid', true)->count() * MB::PUBLISH_FEE;
        $withdrawable = (int) round($totalEarned * 0.70);   // mock 70/30 split
        $pending      = $totalEarned - $withdrawable;

        return view('submissions::earnings', compact(
            'viewer', 'papers', 'totalEarned', 'feesPaid', 'withdrawable', 'pending'
        ) + ['net' => $totalEarned - $feesPaid]);
    }
}
