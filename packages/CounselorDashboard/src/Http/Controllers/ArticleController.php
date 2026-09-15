<?php

namespace CounselorDashboard\Http\Controllers;

use Illuminate\Routing\Controller;

class ArticleController extends Controller
{
    public function index()
    {
        // TODO: pull from a real Article model once DB exists.
        // Each $article is expected to expose: title, excerpt, date_label,
        // category, status ('Published'|'Draft'), views (int, only meaningful when Published)
        $articles = collect();

        return view('counselor-dashboard::articles.index', compact('articles'));
    }

    public function create()
    {
        return view('counselor-dashboard::articles.create');
    }
}