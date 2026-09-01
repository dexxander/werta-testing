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

    public function register()
    {
        // Package-local PSR-4 bridge for the imported AdminDashboard package.
        // This keeps the package usable before the root autoloader is updated.
        spl_autoload_register(function ($class) {
            $prefix = 'AdminDashboard\\';
            if (strncmp($class, $prefix, strlen($prefix)) !== 0) {
                return;
            }

            $relative = substr($class, strlen($prefix));
            $path = __DIR__ . '/../../../AdminDashboard/src/' . str_replace('\\', '/', $relative) . '.php';

            if (is_file($path)) {
                require_once $path;
            }
        });

        // Bootstrap AdminDashboard without edits outside the packages folder.
        $this->app->register(\AdminDashboard\Providers\AdminDashboardServiceProvider::class);
    }
}
