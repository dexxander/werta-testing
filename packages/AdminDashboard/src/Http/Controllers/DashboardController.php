<?php

namespace AdminDashboard\Http\Controllers;

use AdminDashboard\Models\Administrator;
use AdminDashboard\Models\Counselor;
use AdminDashboard\Models\Client;
use Illuminate\Support\Facades\Schema;

class DashboardController
{
    public function index($role)
    {
        return view('admindashboard::dashboard', [
            'role' => $role,
            // Keep the local demo dashboard available before migrations run.
            'totalAdmins' => $this->countIfTableExists('administrators', fn () => Administrator::count()),
            'activeClients' => $this->countIfTableExists('clients', fn () => Client::where('status', 'active')->count()),
            'verifiedCounselors' => $this->countIfTableExists('counselors', fn () => Counselor::where('status', 'approved')->count()),
            'pendingCounselors' => $this->countIfTableExists('counselors', fn () => Counselor::where('status', 'pending')->count()),
        ]);
    }

    private function countIfTableExists(string $table, callable $query): int
    {
        return Schema::hasTable($table) ? (int) $query() : 0;
    }
}
