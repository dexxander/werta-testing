<?php

namespace AdminDashboard\Http\Controllers;

use AdminDashboard\Models\Administrator;
use AdminDashboard\Models\Counselor;
use AdminDashboard\Models\Client;
use AdminDashboard\Support\PlatformStats;
use Illuminate\Support\Facades\Schema;

class DashboardController
{
    public function index($role)
    {
        $ready = Schema::hasTable('submissions') && Schema::hasTable('reviewers');
        $stats = $ready ? new PlatformStats : null;

        return view('admindashboard::dashboard', [
            'role' => $role,
            // Keep the local demo dashboard available before migrations run.
            'totalAdmins' => $this->countIfTableExists('administrators', fn () => Administrator::count()),
            'activeClients' => $this->countIfTableExists('clients', fn () => Client::where('status', 'active')->count()),
            'verifiedCounselors' => $this->countIfTableExists('counselors', fn () => Counselor::where('status', 'approved')->count()),
            'pendingCounselors' => $this->countIfTableExists('counselors', fn () => Counselor::where('status', 'pending')->count()),
            'stats' => $stats ? [
                'labels'   => $stats->labels(),
                'monthly'  => $stats->monthly(),
                'current'  => $stats->current(),
                'pipeline' => $stats->pipeline(),
                'users'    => $stats->newUsers(),
                'pendingReviewers' => $stats->pendingReviewers(),
                'avgDays'  => $stats->avgReviewDays(),
            ] : null,
        ]);
    }

    private function countIfTableExists(string $table, callable $query): int
    {
        return Schema::hasTable($table) ? (int) $query() : 0;
    }
}
