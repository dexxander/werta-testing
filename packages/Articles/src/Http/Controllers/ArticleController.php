<?php

namespace Articles\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $activeFilter = $request->query('filter');

        // TODO: pull from a real Article model once DB exists.
        // $featuredArticle expects: title, excerpt, image_url, category, read_time, lang, slug
        $featuredArticle = null;
        // TODO: pull from a real Comment model once DB exists.
        // Each $comment expects: author_name, time_ago, text, article_slug, article_title
        $trendingComments = collect();
        // TODO: pull from real Category/Article models once DB exists.
        // $categories: ['ai' => ['count' => int], 'iot' => [...], 'ar' => [...], 'multilingual' => [...]]
        $categories = collect();
        // TODO: $categoryArticles: ['ai' => [{slug, title, type, read_time}, ...], 'iot' => [...], ...]
        $categoryArticles = collect();
        // TODO: pull from a real Article model once DB exists.
        // Each $article in $moreArticles expects: slug, image_url, category, title, read_time, lang
        $moreArticles = collect();

        return view('articles::index', compact(
            'activeFilter', 'featuredArticle', 'trendingComments',
            'categories', 'categoryArticles', 'moreArticles'
        ));
    }

    public function show($slug)
    {
        // TODO: $article = Article::where('slug', $slug)->firstOrFail();
        // $article expects: title, subtitle, category, read_time, author_name,
        // author_title, author_avatar_url, date_label, image_url, image_caption, body_html
        $article = null;

        // TODO: $comments = Comment::where('article_slug', $slug)->get();
        // Each $comment expects: author_name, time_ago, text, likes, replies (array of
        // {author_name, is_author, time_ago, text, likes})
        $comments = collect();

        return view('articles::show', compact('article', 'slug', 'comments'));
    }
}