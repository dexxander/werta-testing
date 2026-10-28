<?php

namespace Articles\Http\Controllers;

use App\Support\DemoAuth;
use Articles\Support\DemoStats;
use Articles\Support\MockBilling;
use Articles\Support\Promotions;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use Submissions\Models\Submission;
use Submissions\Support\ContentRenderer;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $published = Submission::published()
            ->withCount(['reviews as completed_reviews_count' => fn ($q) => $q->where('status', 'completed')])
            ->latest('submitted_at')
            ->get();

        // Featured: newest premium paper, else newest paper.
        $featuredArticle = $published->firstWhere('access_type', 'paid') ?? $published->first();
        $moreArticles = $published->reject(fn ($a) => $featuredArticle && $a->id === $featuredArticle->id)->take(8);

        $readers = fn (Submission $a) => MockBilling::totalSales($a) + DemoStats::subscriptionReads($a) + DemoStats::freeReads($a);
        $popular = $published->sortByDesc($readers)->take(5)->values();
        $bestSellers = $published->where('access_type', 'paid')
            ->sortByDesc(fn ($a) => MockBilling::totalSales($a))->take(3)->pluck('slug')->all();

        $categories = collect(Submission::CATEGORIES)
            ->map(fn ($c, $key) => $c + ['key' => $key, 'count' => $published->where('category', $key)->count()]);
        $categoryArticles = $published->groupBy('category');

        // "All articles" list with filters (?q=&category=&access=&sort=)
        $filters = [
            'q'        => trim((string) $request->query('q', '')),
            'category' => array_key_exists($request->query('category'), Submission::CATEGORIES) ? $request->query('category') : '',
            'access'   => in_array($request->query('access'), ['free', 'paid'], true) ? $request->query('access') : '',
            'sort'     => $request->query('sort') === 'popular' ? 'popular' : 'newest',
        ];
        $list = $this->filter($published, $filters, $readers);
        $page = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 9;
        $articles = new LengthAwarePaginator(
            $list->forPage($page, $perPage)->values(), $list->count(), $perPage, $page,
            ['path' => $request->url(), 'query' => $request->query(), 'fragment' => 'all-articles']
        );

        return view('articles::index', compact(
            'featuredArticle', 'popular', 'bestSellers', 'categories', 'categoryArticles',
            'moreArticles', 'articles', 'filters'
        ));
    }

    private function filter(Collection $published, array $f, callable $readers): Collection
    {
        $list = $published;
        if ($f['q'] !== '') {
            $q = mb_strtolower($f['q']);
            $list = $list->filter(fn ($a) => str_contains(mb_strtolower($a->title . ' ' . $a->abstract . ' ' . $a->author_name), $q));
        }
        if ($f['category'] !== '') {
            $list = $list->where('category', $f['category']);
        }
        if ($f['access'] !== '') {
            $list = $list->where('access_type', $f['access']);
        }

        return $f['sort'] === 'popular' ? $list->sortByDesc($readers)->values() : $list->values();
    }

    public function show($slug)
    {
        $article = Submission::where('slug', $slug)->firstOrFail();

        $this->authorizeArticleView($article);

        // TODO: load real comments once a comments table exists for submissions
        $comments = collect();

        // DEMO: access is re-checked on every request (never a stored flag)
        $viewer    = MockBilling::viewer();
        $hasAccess = MockBilling::hasAccess($article);
        $accessVia = MockBilling::accessVia($article);
        $state     = MockBilling::state();
        $purchase  = $state['purchases'][$article->slug] ?? null;

        $reviews = $article->publicReviews()->latest('submitted_at')->get();
        $anonymousReviews = $article->reviews()->where('status', 'completed')->count() - $reviews->count();

        $authorPapers = Submission::published()
            ->where('author_role', $article->author_role)
            ->where('author_name', $article->author_name)
            ->count();

        // Locked text preview: first paragraphs only. The rest is never sent to the browser.
        $preview = $this->previewParts($article);

        return view('articles::show', compact(
            'article', 'slug', 'comments', 'viewer', 'hasAccess', 'accessVia', 'state', 'purchase',
            'reviews', 'anonymousReviews', 'authorPapers', 'preview'
        ));
    }

    /** Checkout: buy once, subscribe, or gift. Route name kept as public.articles.subscribe. */
    public function subscribe($slug)
    {
        $article = Submission::where('slug', $slug)->firstOrFail();

        $this->authorizeArticleView($article);

        $viewer    = MockBilling::viewer();
        $state     = MockBilling::state();
        $hasAccess = MockBilling::hasAccess($article);
        $accessVia = MockBilling::accessVia($article);
        $promos    = Promotions::live();

        // People this reader can gift to: other demo readers, never themselves or the author.
        $me = DemoAuth::key();
        $recipients = collect(DemoAuth::readerAccounts())
            ->reject(fn ($acc, $key) => $key === $me
                || ($acc['role'] === $article->author_role && $acc['name'] === $article->author_name));

        return view('articles::subscribe', compact('article', 'viewer', 'state', 'hasAccess', 'accessVia', 'promos', 'recipients'));
    }

    /**
     * Split text content into the visible preview and a scrambled filler
     * (made from the preview's own words) that is blurred under the paywall.
     */
    private function previewParts(Submission $article): array
    {
        $blocks = ContentRenderer::blocks($article->content);
        $paragraphs = array_values(array_filter($blocks, fn ($b) => ! ContentRenderer::isHeading($b)));
        if (! $paragraphs) {
            return ['visible' => [], 'filler' => [], 'hidden' => 0];
        }

        // Show headings plus the first two paragraphs (one if the paper is very short).
        $want = count($paragraphs) > 2 ? 2 : 1;
        $visible = [];
        $seen = 0;
        foreach ($blocks as $block) {
            $visible[] = $block;
            if (! ContentRenderer::isHeading($block) && ++$seen === $want) {
                break;
            }
        }
        $hidden = count($paragraphs) - $want;

        $words = preg_split('/\s+/', ContentRenderer::plain($visible));
        mt_srand(crc32($article->slug));
        $filler = [];
        for ($i = 0; $i < max(3, $hidden); $i++) {
            shuffle($words);
            $filler[] = ucfirst(implode(' ', array_slice($words, 0, min(count($words), 55)))) . '.';
        }
        mt_srand();

        return ['visible' => $visible, 'filler' => $filler, 'hidden' => $hidden];
    }

    private function authorizeArticleView(Submission $article): void
    {
        if ($article->status !== 'published' || $article->visibility === 'private') {
            $viewer = DemoAuth::current();
            abort_unless(
                $viewer && $viewer[0] === $article->author_role && $viewer[1] === $article->author_name,
                404
            );
        }
    }
}
