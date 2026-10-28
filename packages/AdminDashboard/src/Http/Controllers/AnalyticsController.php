<?php

namespace AdminDashboard\Http\Controllers;

use AdminDashboard\Support\PlatformStats;
use Illuminate\Routing\Controller;

/** DEMO ONLY: analytics charts (real rows + session activity + baselines). */
class AnalyticsController extends Controller
{
    public function index(string $role)
    {
        $stats = new PlatformStats;
        $monthly = $stats->monthly();

        return view('admindashboard::analytics', [
            'role'       => $role,
            'labels'     => $stats->labels(),
            'monthly'    => $monthly,
            'totals'     => $stats->totals(),
            'categories' => $stats->salesByCategory(),
            'topArticles' => $stats->topArticles(),
            'topAuthors' => $stats->topAuthors(),
            'workload'   => $stats->reviewerWorkload(),
            'avgDays'    => $stats->avgReviewDays(),
            'promos'     => $stats->promoRedemptions(),
            'packs'      => $stats->packMix(),
            'payouts'    => $stats->payouts(),
            'users'      => $stats->newUsers(),
            'pipeline'   => $stats->pipeline(),
        ]);
    }
}
