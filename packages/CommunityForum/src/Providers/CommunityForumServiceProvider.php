<?php

namespace CommunityForum\Providers;

use Illuminate\Support\ServiceProvider;

class CommunityForumServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/migrations');
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'community');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'community');
    }
}
