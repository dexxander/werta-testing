<?php

namespace Articles\Http\Controllers;

use Articles\Support\DemoStats;
use Articles\Support\MockBilling;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Submissions\Models\Submission;

/** Public author page: /authors/{role}/{author-slug} */
class AuthorController extends Controller
{
    public function show(string $role, string $author)
    {
        abort_unless(in_array($role, ['counselor', 'client', 'parent'], true), 404);

        $papers = Submission::published()
            ->where('author_role', $role)
            ->withCount(['reviews as completed_reviews_count' => fn ($q) => $q->where('status', 'completed')])
            ->latest('submitted_at')
            ->get()
            ->filter(fn ($p) => Str::slug($p->author_name) === $author)
            ->values();

        abort_if($papers->isEmpty(), 404);

        $name = $papers->first()->author_name;

        $rows = $papers->map(fn ($p) => (object) [
            'paper'   => $p,
            'copies'  => MockBilling::totalSales($p),                       // premium copies bought (incl. gifts)
            'reads'   => DemoStats::subscriptionReads($p) + DemoStats::freeReads($p),
        ]);
        $premium = $rows->filter(fn ($r) => $r->paper->access_type === 'paid');
        $bestSellers = $premium->sortByDesc('copies')->take(3)->values();
        $bestSlug = optional($bestSellers->first())->paper->slug ?? null;

        $chart = [
            'labels' => $rows->map(fn ($r) => Str::limit($r->paper->title, 34))->values(),
            'copies' => $rows->pluck('copies')->values(),
            'reads'  => $rows->pluck('reads')->values(),
        ];

        $stats = [
            'papers'     => $papers->count(),
            'premium'    => $premium->count(),
            'copies'     => $premium->sum('copies'),
            'readers'    => $rows->sum('copies') + $rows->sum('reads'),
            'categories' => $papers->pluck('category')->filter()->unique()->count(),
            'reviewed'   => $papers->where('completed_reviews_count', '>', 0)->count(),
        ];

        $categoryFilter = request('category');
        if ($categoryFilter && array_key_exists($categoryFilter, Submission::CATEGORIES)) {
            $rows = $rows->filter(fn ($r) => $r->paper->category === $categoryFilter)->values();
        } else {
            $categoryFilter = null;
        }

        return view('articles::author', compact('role', 'author', 'name', 'rows', 'bestSellers', 'bestSlug', 'chart', 'stats', 'papers', 'categoryFilter'));
    }
}
