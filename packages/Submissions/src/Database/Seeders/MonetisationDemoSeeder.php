<?php

namespace Submissions\Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * DEMO ONLY: kept so the old command still works. All demo papers, categories
 * and reviews now live in SubmissionSeeder (safe to re-run).
 * php artisan db:seed --class="Submissions\Database\Seeders\MonetisationDemoSeeder"
 */
class MonetisationDemoSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(SubmissionSeeder::class);
    }
}
