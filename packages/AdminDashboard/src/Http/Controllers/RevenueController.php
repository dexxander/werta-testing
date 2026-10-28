<?php

namespace AdminDashboard\Http\Controllers;

use AdminDashboard\Support\PlatformStats;
use Articles\Support\MockBilling as MB;
use Illuminate\Routing\Controller;
use Submissions\Models\Submission;

/** DEMO ONLY: platform revenue = real DB papers + session wallet activity + fixed demo baselines. */
class RevenueController extends Controller
{
    public function index(string $role)
    {
        $stats = new PlatformStats;
        $monthly = $stats->monthly();
        $current = end($monthly);
        $totals = $stats->totals();

        // Premium review queue: real rows only.
        $stageMap = ['submitted' => 0, 'under_review' => 1, 'accepted' => 3];
        $queue = Submission::where('access_type', 'paid')->whereIn('status', array_keys($stageMap))->latest('submitted_at')->get()
            ->map(fn ($p) => ['title' => $p->title, 'author' => $p->author_name, 'stage' => $stageMap[$p->status]]);

        $top = $stats->topArticles(6)->map(fn ($r) => [
            'title' => $r->paper->title, 'author' => $r->paper->author_name, 'category' => $r->paper->category_label,
            'copies' => $r->copies, 'gross' => $r->copies * MB::articlePrice(), 'author_share' => $r->copies * MB::AUTHOR_PER_COPY,
        ]);

        return view('admindashboard::revenue', [
            'role' => $role, 'labels' => $stats->labels(), 'monthly' => $monthly, 'current' => $current,
            'totals' => $totals, 'payouts' => $stats->payouts(), 'packs' => $stats->packMix(), 'queue' => $queue, 'top' => $top,
        ]);
    }
}
