<?php

use App\Providers\AppServiceProvider;

return [
    AppServiceProvider::class,
    Elearning\Providers\ElearningServiceProvider::class,
    ParentDashboard\Providers\ParentDashboardServiceProvider::class,
    ClientDashboard\Providers\ClientDashboardServiceProvider::class,
];
