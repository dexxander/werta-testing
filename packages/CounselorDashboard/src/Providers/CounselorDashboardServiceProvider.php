<?php

namespace CounselorDashboard\Providers;

use Illuminate\Support\ServiceProvider;

class CounselorDashboardServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Register the guard middleware so routes can reference it as 'counselor.auth'
        $this->app['router']->aliasMiddleware(
            'counselor.auth',
            \CounselorDashboard\Http\Middleware\EnsureCounselorLoggedIn::class
        );

        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'counselor-dashboard');
    }
}