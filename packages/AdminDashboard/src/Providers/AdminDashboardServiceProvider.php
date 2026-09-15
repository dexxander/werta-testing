<?php

namespace AdminDashboard\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class AdminDashboardServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
{
    Route::middleware('web')
        ->group(__DIR__ . '/../routes/web.php');

    $this->loadViewsFrom(__DIR__ . '/../resources/views', 'admindashboard');

    // NEW: register this package's own migrations
    $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
}
}