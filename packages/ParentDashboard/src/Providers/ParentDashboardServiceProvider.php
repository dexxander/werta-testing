<?php

namespace ParentDashboard\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class ParentDashboardServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Load module routes
        Route::middleware('web')
            ->group(__DIR__ . '/../routes/web.php');

        // Load module views
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'parentdashboard');
    }
}
