<?php

namespace Articles\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Submissions\Models\Submission;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $published = Submission::where('status', 'published')
            ->where('visibility', 'public')
            ->latest('submitted_at')
            ->get();

        $featuredArticle  = $published->first();
        $moreArticles     = $published->skip(1);

        // TODO: build these out once categories/comments exist for submissions
        $trendingComments = collect();
        $categories       = collect();
        $categoryArticles = collect();

        return view('articles::index', compact(
            'featuredArticle', 'trendingComments', 'categories', 'categoryArticles', 'moreArticles'
        ));
    }

    public function show($slug)
    {
        $article = Submission::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();
        // Note: private articles are intentionally still reachable by direct slug link,
        // just excluded from the public listing above.

        return view('articles::show', compact('article', 'slug'));
    }

    public function subscribe($slug)
    {
        $article = \Submissions\Models\Submission::where('slug', $slug)->firstOrFail();
        return view('articles::subscribe', compact('article'));
    }
}