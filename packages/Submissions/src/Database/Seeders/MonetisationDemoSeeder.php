<?php

namespace Submissions\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Submissions\Models\Submission;

/**
 * DEMO ONLY: extra premium papers so the monetisation mock has something to buy.
 * Safe to re-run (matches on slug). Run:
 * php artisan db:seed --class="Submissions\Database\Seeders\MonetisationDemoSeeder"
 */
class MonetisationDemoSeeder extends Seeder
{
    public function run(): void
    {
        $body = "This is demo premium content for the monetisation mock.\n\nIn the real build this text (or the uploaded paper) is served only to readers who have bought the paper or have it unlocked by their subscription, with a per-reader watermark.\n\nIt stays in the database for now so the checkout, wallet and library can be demonstrated end to end.";

        $rows = [
            ['Coping With Social Media Pressure', 'Practical steps for teens and parents to reduce comparison stress.', 'counselor', 'Counselor User', 12.00],
            ['A Parent\'s Guide to Teen Sleep', 'Evidence-based routines that help adolescents sleep better.', 'parent', 'Parent User', 15.00],
            ['Talking About Anxiety With Your Counselor', 'A client\'s guide to getting more from sessions.', 'client', 'Client User', 8.00],
        ];

        foreach ($rows as [$title, $abstract, $role, $name, $price]) {
            Submission::updateOrCreate(
                ['slug' => Str::slug($title)],
                [
                    'title' => $title, 'abstract' => $abstract, 'content' => $body,
                    'author_role' => $role, 'author_name' => $name,
                    'status' => 'published', 'visibility' => 'public',
                    'access_type' => 'paid', 'price' => $price,
                    'submitted_at' => now()->subDays(rand(3, 20)),
                ]
            );
        }
    }
}
