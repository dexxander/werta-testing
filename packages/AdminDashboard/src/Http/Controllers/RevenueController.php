<?php

namespace AdminDashboard\Http\Controllers;

use Articles\Support\MockBilling as MB;
use Illuminate\Routing\Controller;
use Submissions\Models\Submission;

/** DEMO ONLY: platform revenue = real DB papers + session sales + fixed demo baselines. */
class RevenueController extends Controller
{
    private const BASE_PAPERS_PUBLISHED = 6;     // demo baseline: already-published premium papers
    private const BASE_SUBSCRIBERS      = 38;    // demo baseline
    private const BASE_SALES_GROSS      = 124000; // sen, demo baseline sales not tied to a listed paper

    public function index(string $role)
    {
        $paid = Submission::where('access_type', 'paid')->get();

        $published = $paid->where('status', 'published');
        $feesCount = $published->count() + self::BASE_PAPERS_PUBLISHED;
        $publishFees = $feesCount * MB::PUBLISH_FEE;

        $g = MB::globals();
        $salesGross = self::BASE_SALES_GROSS
            + $published->sum(fn ($p) => MB::sen($p->price) * MB::totalSales($p));
        $subRevenue = (self::BASE_SUBSCRIBERS * MB::SUB_PRICE) + $g['sub_revenue'];

        $gross    = $publishFees + $salesGross + $subRevenue;
        $gateway  = (int) round($gross * MB::GATEWAY_RATE);
        $payouts  = (int) round($salesGross * MB::AUTHOR_SHARE);
        $net      = $gross - $gateway - $payouts;

        // Monthly chart: five demo months + current month from the live figures.
        $months = collect([
            ['Jun', 95000], ['Jul', 128000], ['Aug', 164000], ['Sep', 207000],
        ])->map(fn ($m) => ['label' => $m[0], 'value' => $m[1], 'live' => false]);
        $months->push(['label' => 'Oct', 'value' => $gross, 'live' => true]);
        $maxMonth = max(1, $months->max('value'));

        // Premium review queue: real rows first, then demo rows.
        $stageMap = ['submitted' => 0, 'under_review' => 1, 'accepted' => 2, 'payment_pending' => 3];
        $queue = $paid->filter(fn ($p) => isset($stageMap[$p->status]))->map(fn ($p) => [
            'title' => $p->title, 'author' => $p->author_name, 'stage' => $stageMap[$p->status], 'demo' => false,
        ])->values();
        foreach ([
            ['Teen Screen-Time Coaching Toolkit', 'Parent User', 1],
            ['Mindfulness for Exam Season', 'Counselor User', 2],
            ['Supporting a Grieving Classmate', 'Client User', 3],
        ] as [$t, $a, $st]) {
            $queue->push(['title' => $t, 'author' => $a, 'stage' => $st, 'demo' => true]);
        }

        // Top earning papers (gross sales) — real rows plus demo fill.
        $top = $published->map(fn ($p) => [
            'title' => $p->title, 'author' => $p->author_name,
            'sales' => MB::totalSales($p), 'gross' => MB::sen($p->price) * MB::totalSales($p), 'demo' => false,
        ])->values();
        foreach ([
            ['Raising Resilient Teens', 'Counselor User', 41, 1200],
            ['Study Habits That Last', 'Parent User', 27, 1500],
        ] as [$t, $a, $n, $price]) {
            $top->push(['title' => $t, 'author' => $a, 'sales' => $n, 'gross' => $n * $price, 'demo' => true]);
        }
        $top = $top->sortByDesc('gross')->values();

        return view('admindashboard::revenue', compact(
            'role', 'feesCount', 'publishFees', 'salesGross', 'subRevenue', 'gateway',
            'payouts', 'net', 'months', 'maxMonth', 'queue', 'top'
        ) + ['subscribers' => self::BASE_SUBSCRIBERS + $g['subscribers']]);
    }
}
