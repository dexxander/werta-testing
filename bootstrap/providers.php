<?php

use App\Providers\AppServiceProvider;

return [
    AppServiceProvider::class,
    AdminDashboard\Providers\AdminDashboardServiceProvider::class,
    Elearning\Providers\ElearningServiceProvider::class,
    ParentDashboard\Providers\ParentDashboardServiceProvider::class,
    ClientDashboard\Providers\ClientDashboardServiceProvider::class,
    CommunityForum\Providers\CommunityForumServiceProvider::class,
    Articles\Providers\ArticlesServiceProvider::class,
    GuidanceCenter\Providers\GuidanceCenterServiceProvider::class,
    CounselorDashboard\Providers\CounselorDashboardServiceProvider::class,
    Submissions\Providers\SubmissionsServiceProvider::class,
];
