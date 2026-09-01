<?php

use App\Providers\AppServiceProvider;

return [
    AppServiceProvider::class,
    Elearning\Providers\ElearningServiceProvider::class,
    ParentDashboard\Providers\ParentDashboardServiceProvider::class,
    ClientDashboard\Providers\ClientDashboardServiceProvider::class,
    Articles\Providers\ArticlesServiceProvider::class,
    CounselorDashboard\Providers\CounselorDashboardServiceProvider::class,
];
