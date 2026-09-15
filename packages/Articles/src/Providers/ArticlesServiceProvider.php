<?php

namespace Articles\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class ArticlesServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Load the routes
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        // Load the views and give them the 'articles::' namespace
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'articles');
    }
}