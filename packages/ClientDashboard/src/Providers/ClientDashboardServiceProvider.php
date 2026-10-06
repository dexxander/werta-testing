<?php

namespace ClientDashboard\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class ClientDashboardServiceProvider extends ServiceProvider
{
    public function boot(\Illuminate\Contracts\Http\Kernel $kernel)
    {
        // Load Routes
        Route::middleware('web')
            ->group(__DIR__.'/../routes/web.php');

        // Load Views (accessible via 'clientdashboard::viewname')
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'clientdashboard');
    }
}
