<?php

namespace GuidanceCenter\Providers;

use Illuminate\Support\ServiceProvider;

class GuidanceCenterServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            base_path('config/guidance-center.php'),
            'guidance-center'
        );
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'guidance-center');
    }
}
