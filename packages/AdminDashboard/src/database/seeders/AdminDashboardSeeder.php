<?php

namespace AdminDashboard\Database\Seeders;

use Illuminate\Database\Seeder;
use AdminDashboard\Models\Administrator;
use AdminDashboard\Models\Counselor;
use AdminDashboard\Models\Client;

class AdminDashboardSeeder extends Seeder
{
    public function run(): void
    {
        Administrator::insert([
            ['name' => 'Nur Aina Zulkifli', 'email' => 'aina@werta.com', 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Marcus Tan', 'email' => 'marcus@werta.com', 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
        ]);

        Counselor::insert([
            ['name' => 'Nurul Huda', 'qualification' => 'M.Couns', 'email' => 'nurul@werta.com', 'status' => 'pending', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Dr. James Lee', 'qualification' => 'PhD Clin Psych', 'email' => 'james@werta.com', 'status' => 'pending', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Siti Rahman', 'qualification' => 'M.Couns', 'email' => 'siti@werta.com', 'status' => 'approved', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Kevin Ong', 'qualification' => 'B.Psych (Hons)', 'email' => 'kevin@werta.com', 'status' => 'rejected', 'created_at' => now(), 'updated_at' => now()],
        ]);

        Client::insert([
            ['name' => 'Ahmad bin Ali', 'email' => 'ahmad@example.com', 'path' => 'A', 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Grace Wong', 'email' => 'grace@example.com', 'path' => 'B', 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Rajesh Kumar', 'email' => 'rajesh@example.com', 'path' => 'A', 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}