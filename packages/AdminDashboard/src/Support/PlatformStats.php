<?php

namespace AdminDashboard\Support;

use AdminDashboard\Models\Client;
use AdminDashboard\Models\Counselor;
use AdminDashboard\Models\Reviewer;
use Articles\Support\DemoStats;
use Articles\Support\MockBilling as MB;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Submissions\Models\Submission;
use Submissions\Models\SubmissionReview;

/**
 * DEMO ONLY: platform analytics for the admin pages.
 *
 * Mixes three sources, so the charts move during a live demo:
 *  1. real database rows (papers, reviews, reviewers, users),
 *  2. this browser session's mock wallet activity (MockBilling::globals()),
 *  3. fixed baselines for the months before this one (marked DEMO on the pages).
 *
 * All money is integer sen. Article copies come from the same per-paper numbers
 * the author pages use, so author, reviewer and admin figures agree.
 */
class PlatformStats
{
    public const MONTHS = 6;

    /** Demo baselines for the five months before the current one, oldest first. */
    private const BASE = [
        'topups'      => [118000, 142500, 171000, 198000, 231500], // cash taken for token packs (sen)
        'subscribers' => [24, 30, 37, 44, 52],
        'fees_papers' => [3, 4, 5, 5, 6],                          // other authors' premium papers going live
        'bonus_spent' => [3900, 5200, 6400, 8100, 9600],           // bonus tokens spent (tokens)
        'users'       => ['client' => [34, 41, 52, 60, 71], 'parent' => [12, 15, 19, 22, 27], 'counselor' => [3, 4, 4, 6, 7]],
        'promo'       => ['WELCOME50' => 142, 'EXAM20' => 61],
        'referrals'   => 38,
        'packs'       => ['single' => 210, 'double' => 168, 'five' => 236, 'ten' => 74],
        'subscribers_now' => 58,
    ];

    private ?Collection $papers = null;

    /** Published premium papers with their copy counts. */
    public function paidPapers(): Collection
    {
        return $this->papers ??= Submission::published()->where('access_type', 'paid')->get()
            ->map(fn ($p) => (object) ['paper' => $p, 'copies' => MB::totalSales($p)]);
    }

    public function labels(): array
    {
        return DemoStats::monthLabels(self::MONTHS);
    }

    /** Copies sold per month across all papers (same split the author pages use). */
    public function copiesByMonth(): array
    {
        $out = array_values(array_fill_keys($this->labels(), 0));
        foreach ($this->paidPapers() as $row) {
            $i = 0;
            foreach (DemoStats::monthly(DemoStats::baselineSales($row->paper), $row->paper->slug, self::MONTHS) as $n) {
                $out[$i++] += $n;
            }
            // Copies sold during this demo session land in the current month.
            $out[self::MONTHS - 1] += MB::sessionSales($row->paper);
        }

        return $out;
    }

    /** Base series (5 months) + current month value. */
    private function series(array $base, int $current): array
    {
        return array_merge($base, [$current]);
    }

    public function monthly(): array
    {
        $g = MB::globals();
        $copies = $this->copiesByMonth();

        $subscribers = $this->series(self::BASE['subscribers'], self::BASE['subscribers_now'] + $g['subscribers']);
        $topups = $this->series(self::BASE['topups'], 254000 + $g['topup_sen']);

        // Publish fees: real premium papers that went live, by month of submission, plus other authors.
        $realFees = array_fill_keys($this->labels(), 0);
        foreach ($this->paidPapers() as $row) {
            $label = $row->paper->submitted_at->translatedFormat('M');
            if (array_key_exists($label, $realFees) && $row->paper->submitted_at->gte(now()->startOfMonth()->subMonths(self::MONTHS - 1))) {
                $realFees[$label]++;
            }
        }
        $feePapers = array_map(fn ($b, $r) => $b + $r, $this->series(self::BASE['fees_papers'], 6), array_values($realFees));

        $bonusSpent = $this->series(self::BASE['bonus_spent'], 10400 + $g['tokens_bonus_spent']);

        $m = [];
        foreach ($this->labels() as $i => $label) {
            $sales = $copies[$i] * MB::articlePrice();
            $subs = $subscribers[$i] * MB::subPrice();
            $fees = $feePapers[$i] * MB::PUBLISH_FEE;
            $payouts = $copies[$i] * MB::AUTHOR_PER_COPY;
            $gateway = (int) round(($topups[$i] + $fees) * MB::GATEWAY_RATE);
            $promoCost = MB::tokensToSen($bonusSpent[$i]);
            $m[] = [
                'label' => $label, 'copies' => $copies[$i], 'subscribers' => $subscribers[$i],
                'sales' => $sales, 'subs' => $subs, 'fees' => $fees, 'topups' => $topups[$i],
                'payouts' => $payouts, 'gateway' => $gateway, 'promo' => $promoCost,
                'net' => $sales - $payouts + $subs + $fees - $gateway - $promoCost,
                'tokens_sold' => intdiv($topups[$i], MB::TOKEN_SEN) + $bonusSpent[$i],
                'tokens_spent' => $copies[$i] * MB::ARTICLE_TOKENS + $subscribers[$i] * MB::SUB_TOKENS,
            ];
        }

        // Outstanding tokens = what readers hold but have not spent (a liability).
        $running = 4200;
        foreach ($m as &$row) {
            $running += $row['tokens_sold'] - $row['tokens_spent'];
            $row['tokens_outstanding'] = max(0, $running);
        }

        return $m;
    }

    public function current(): array
    {
        $m = $this->monthly();

        return end($m);
    }

    /** Copies per category (all time). */
    public function salesByCategory(): array
    {
        $out = [];
        foreach (Submission::CATEGORIES as $key => $cat) {
            $out[$key] = ['label' => $cat['label'], 'copies' => 0];
        }
        foreach ($this->paidPapers() as $row) {
            if (isset($out[$row->paper->category])) {
                $out[$row->paper->category]['copies'] += $row->copies;
            }
        }

        return $out;
    }

    public function topArticles(int $n = 5): Collection
    {
        return $this->paidPapers()->sortByDesc('copies')->take($n)->values();
    }

    public function topAuthors(int $n = 5): Collection
    {
        return $this->paidPapers()
            ->groupBy(fn ($r) => $r->paper->author_name)
            ->map(fn ($rows, $name) => (object) ['name' => $name, 'copies' => $rows->sum('copies'), 'papers' => $rows->count()])
            ->sortByDesc('copies')->take($n)->values();
    }

    /** Submissions per status (real rows). */
    public function pipeline(): array
    {
        $counts = Submission::selectRaw('status, count(*) as n')->groupBy('status')->pluck('n', 'status');
        $out = [];
        foreach (['submitted', 'under_review', 'revisions_requested', 'accepted', 'published', 'rejected'] as $status) {
            $out[$status] = (int) ($counts[$status] ?? 0);
        }

        return $out;
    }

    /** Assigned, completed and average turnaround per approved reviewer (real rows). */
    public function reviewerWorkload(): Collection
    {
        if (! Schema::hasTable('reviewers')) {
            return collect();
        }

        return Reviewer::approved()->with('reviews')->orderBy('name')->get()->map(function ($r) {
            $done = $r->reviews->where('status', 'completed');

            return (object) [
                'name' => $r->name,
                'assigned' => $r->reviews->where('status', 'assigned')->count(),
                'completed' => $done->count(),
                'avgDays' => round((float) $done->avg(fn ($x) => $x->turnaroundDays()), 1),
            ];
        });
    }

    public function avgReviewDays(): float
    {
        $done = SubmissionReview::where('status', 'completed')->get();

        return round((float) $done->avg(fn ($r) => $r->turnaroundDays()), 1);
    }

    public function promoRedemptions(): array
    {
        $g = MB::globals();
        $out = [];
        foreach (self::BASE['promo'] as $code => $base) {
            $out[$code] = $base + ($g['promo'][$code] ?? 0);
        }
        $out['Referrals'] = self::BASE['referrals'] + $g['referrals'];
        $out['Gifts'] = 47 + $g['gifts'];

        return $out;
    }

    public function packMix(): array
    {
        $g = MB::globals();
        $out = [];
        foreach (MB::PACKS as $id => $pack) {
            $out[$pack['name']] = self::BASE['packs'][$id] + ($g['pack_counts'][$id] ?? 0);
        }

        return $out;
    }

    /** Author payouts accrued vs paid out per month (paid lags a month for the hold period). */
    public function payouts(): array
    {
        $m = $this->monthly();
        $owed = array_column($m, 'payouts');
        $paid = [];
        foreach ($owed as $i => $v) {
            $paid[] = $i === 0 ? (int) round($v * 0.6) : (int) round($owed[$i - 1] * 0.92);
        }
        $paid[count($paid) - 1] += array_sum(array_map(fn ($rows) => array_sum(array_column($rows, 'sen')), MB::globals()['withdrawals']));

        return ['owed' => $owed, 'paid' => $paid];
    }

    public function newUsers(): array
    {
        $out = [];
        $realClients = Schema::hasTable('clients') ? Client::where('created_at', '>=', now()->startOfMonth())->count() : 0;
        $realCounselors = Schema::hasTable('counselors') ? Counselor::where('created_at', '>=', now()->startOfMonth())->count() : 0;
        $out['client'] = $this->series(self::BASE['users']['client'], 78 + $realClients);
        $out['parent'] = $this->series(self::BASE['users']['parent'], 31);
        $out['counselor'] = $this->series(self::BASE['users']['counselor'], 5 + $realCounselors);

        return $out;
    }

    public function pendingReviewers(): int
    {
        return Schema::hasTable('reviewers') ? Reviewer::where('status', 'pending')->count() : 0;
    }

    /** Totals over the six months shown. */
    public function totals(): array
    {
        $m = collect($this->monthly());
        $owed = $this->paidPapers()->sum('copies') * MB::AUTHOR_PER_COPY;

        return [
            'topups' => $m->sum('topups'), 'sales' => $m->sum('sales'), 'subs' => $m->sum('subs'),
            'fees' => $m->sum('fees'), 'payouts' => $m->sum('payouts'), 'gateway' => $m->sum('gateway'),
            'promo' => $m->sum('promo'), 'net' => $m->sum('net'),
            'owedAllTime' => $owed,
            'outstandingTokens' => $m->last()['tokens_outstanding'],
        ];
    }
}
