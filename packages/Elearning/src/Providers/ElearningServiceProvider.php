<?php

namespace Elearning\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class ElearningServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Load module routes
        Route::middleware('web')
            ->group(__DIR__ . '/../routes/web.php');

        // Load module views
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'elearning');
    }
}
