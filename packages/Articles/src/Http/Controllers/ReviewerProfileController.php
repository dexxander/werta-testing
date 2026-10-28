<?php

namespace Articles\Http\Controllers;

use AdminDashboard\Models\Reviewer;
use Illuminate\Routing\Controller;
use Submissions\Models\Submission;

/** Public reviewer profile: /reviewers/{slug}. Only approved reviewers who chose to be shown. */
class ReviewerProfileController extends Controller
{
    public function show(string $slug)
    {
        $reviewer = Reviewer::approved()->where('show_publicly', true)->where('slug', $slug)->firstOrFail();

        $reviewed = $reviewer->reviews()
            ->where('status', 'completed')
            ->where('show_on_article', true)
            ->whereHas('submission', fn ($q) => $q->published())
            ->with('submission')
            ->latest('submitted_at')
            ->get();

        $stats = [
            'reviews'  => $reviewer->reviews()->where('status', 'completed')->count(),
            'avgDays'  => round((float) $reviewer->reviews()->where('status', 'completed')->get()->avg(fn ($r) => $r->turnaroundDays()), 1),
            'fields'   => $reviewed->pluck('submission.category')->filter()->unique()->map(fn ($c) => Submission::categoryLabel($c))->values(),
        ];

        return view('articles::reviewer', compact('reviewer', 'reviewed', 'stats'));
    }
}
