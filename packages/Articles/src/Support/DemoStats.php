<?php

namespace Articles\Support;

use Submissions\Models\Submission;

/**
 * DEMO ONLY: stable fake numbers so charts look alive before real data exists.
 * Every figure is derived from a hash of a fixed string, so it never changes
 * between page loads. Live session activity is added on top elsewhere.
 */
class DemoStats
{
    public static function seed(string $key, int $mod, int $min = 0): int
    {
        return (int) (crc32($key) % $mod) + $min;
    }

    /** Copies bought before this demo session (20–109). */
    public static function baselineSales(Submission $a): int
    {
        return self::seed($a->slug . ':sales', 90, 20);
    }

    /** Reads through the subscription allowance (earn the author nothing). */
    public static function subscriptionReads(Submission $a): int
    {
        if ($a->access_type !== 'paid' || $a->status !== 'published') {
            return 0;
        }

        return self::seed($a->slug . ':sub', 45, 8);
    }

    /** Reads of a free paper. */
    public static function freeReads(Submission $a): int
    {
        if ($a->access_type === 'paid' || $a->status !== 'published') {
            return 0;
        }

        return self::seed($a->slug . ':reads', 380, 90);
    }

    /**
     * Split a total over the last $months months with a gentle upward trend.
     * Returns [label => value], oldest first. The sum equals $total.
     */
    public static function monthly(int $total, string $key, int $months = 6): array
    {
        $weights = [];
        for ($i = 0; $i < $months; $i++) {
            $weights[] = 6 + $i * 3 + self::seed($key . ':m' . $i, 5);
        }
        $sum = array_sum($weights);
        $out = [];
        $given = 0;
        foreach ($weights as $i => $w) {
            $label = now()->startOfMonth()->subMonths($months - 1 - $i)->translatedFormat('M');
            $value = $i === $months - 1 ? $total - $given : (int) floor($total * $w / $sum);
            $given += $value;
            $out[$label] = $value;
        }

        return $out;
    }

    /** Month labels, oldest first. */
    public static function monthLabels(int $months = 6): array
    {
        $labels = [];
        for ($i = $months - 1; $i >= 0; $i--) {
            $labels[] = now()->startOfMonth()->subMonths($i)->translatedFormat('M');
        }

        return $labels;
    }
}
