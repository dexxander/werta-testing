<?php

namespace Submissions\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Submissions\Models\Submission;

class SubmissionSeeder extends Seeder
{
    public function run(): void
    {
        Submission::insert([
            [
                'title' => 'Managing Exam Anxiety: A Student\'s Guide',
                'slug' => Str::slug('Managing Exam Anxiety: A Student\'s Guide'),
                'abstract' => 'Practical, evidence-based strategies for reducing exam-related stress.',
                'content' => 'Full manuscript content goes here...',
                'author_role' => 'counselor',
                'author_name' => 'Dr. Sarah Jenkins',
                'status' => 'submitted',
                'visibility' => 'public',
                'access_type' => 'free',
                'price' => null,
                'decision_notes' => null,
                'submitted_at' => now()->subDays(2),
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'title' => 'Navigating Parenting in the Digital Age',
                'slug' => Str::slug('Navigating Parenting in the Digital Age'),
                'abstract' => 'Balanced strategies for healthy tech habits at home.',
                'content' => 'Full manuscript content goes here...',
                'author_role' => 'counselor',
                'author_name' => 'Dr. Abu',
                'status' => 'published',
                'visibility' => 'public',
                'access_type' => 'paid',
                'price' => 9.90,
                'decision_notes' => null,
                'submitted_at' => now()->subDays(5),
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'title' => 'A Client\'s Journey Through Grief',
                'slug' => Str::slug('A Client\'s Journey Through Grief'),
                'abstract' => 'A personal reflection shared with consent for educational use.',
                'content' => 'Full manuscript content goes here...',
                'author_role' => 'client',
                'author_name' => 'Client User',
                'status' => 'revisions_requested',
                'visibility' => 'private',
                'access_type' => 'free',
                'price' => null,
                'decision_notes' => 'Please expand the section on coping mechanisms.',
                'submitted_at' => now()->subDays(10),
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'title' => 'Understanding Sleep Deprivation in Teens',
                'slug' => Str::slug('Understanding Sleep Deprivation in Teens'),
                'abstract' => 'How screen time and sleep hygiene affect adolescent mood.',
                'content' => 'Full manuscript content goes here...',
                'author_role' => 'parent',
                'author_name' => 'Parent User',
                'status' => 'accepted',
                'visibility' => 'public',
                'access_type' => 'free',
                'price' => null,
                'decision_notes' => null,
                'submitted_at' => now()->subDays(14),
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'title' => 'Building Healthy Habits Early',
                'slug' => Str::slug('Building Healthy Habits Early'),
                'abstract' => 'Foundations for lifelong emotional regulation in young children.',
                'content' => 'Full manuscript content goes here...',
                'author_role' => 'counselor',
                'author_name' => 'Dr. Sarah Jenkins',
                'status' => 'published',
                'visibility' => 'public',
                'access_type' => 'free',
                'price' => null,
                'decision_notes' => null,
                'submitted_at' => now()->subDays(20),
                'created_at' => now(), 'updated_at' => now(),
            ],
        ]);
    }
}