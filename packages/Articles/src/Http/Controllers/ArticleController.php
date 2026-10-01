<?php

namespace Articles\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Submissions\Models\Submission;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $published = Submission::where('visibility', 'public')
            ->where('status', 'published')
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
        $article = Submission::where('slug', $slug)->firstOrFail();

        $this->authorizeArticleView($article);

        // TODO: load real comments once a comments table exists for submissions
        $comments = collect();

        return view('articles::show', compact('article', 'slug', 'comments'));
    }

    public function subscribe($slug)
    {
        $article = Submission::where('slug', $slug)->firstOrFail();

        $this->authorizeArticleView($article);

        return view('articles::subscribe', compact('article'));
    }

    private function authorizeArticleView(Submission $article): void
    {
        if ($article->status !== 'published' || $article->visibility === 'private') {
            $viewer = $this->currentViewer();
            abort_unless(
                $viewer && $viewer[0] === $article->author_role && $viewer[1] === $article->author_name,
                404
            );
        }
    }

    /**
     * Same identity logic as SubmissionController::currentAuthor(),
     * but returns null for guests instead of aborting.
     * TODO: replace with auth()->user() once unified login exists.
     */
    private function currentViewer(): ?array
    {
        if (session('counselor_logged_in')) {
            return ['counselor', session('counselor_name', 'Counselor')];
        }
        if (session('client_logged_in')) {
            return ['client', session('client_profile.username', 'Client User')];
        }
        if (session('parent_logged_in')) {
            return ['parent', session('parent_profile.username', 'Parent User')];
        }
        return null;
    }
}