<?php

namespace AdminDashboard\Database\Seeders;

use AdminDashboard\Models\Reviewer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * DEMO ONLY: fictional professional reviewers. Safe to re-run (matches on email).
 * php artisan db:seed --class="AdminDashboard\Database\Seeders\ReviewerSeeder"
 */
class ReviewerSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['Dr. Aisyah Rahman', 'Clinical Psychologist', 'mental-health', 'Klang Valley Psychology Centre', 'PhD Clinical Psychology', 'MPS-CP-1187', 'aisyah.rahman@example.com', 'approved', true,
                'Works with adolescents on anxiety and mood. Reviews papers on mental health and wellbeing.'],
            ['Prof. Lim Wei Jie', 'Professor of Education', 'education', 'Northern Institute of Education', 'PhD Education', null, 'lim.weijie@example.com', 'approved', true,
                'Researches classroom practice and study skills. Reviews papers on education and careers.'],
            ['Dr. Kavitha Nair', 'Developmental Paediatrician', 'science', 'Seri Damai Child Health Clinic', 'MBBS, MRCPCH', 'MMC-58213', 'kavitha.nair@example.com', 'approved', true,
                'Specialises in child development and sleep. Reviews papers on health science and parenting.'],
            ['Tan Mei Ling', 'School Psychologist', 'social', 'Harmony Secondary School Network', 'MSc Educational Psychology', null, 'tan.meiling@example.com', 'approved', false,
                'Supports students with peer and family difficulties. Prefers to review anonymously.'],
            ['Farid Hassan', 'Registered Counsellor', 'career', 'Lembah Career Guidance Centre', 'M.Couns', 'LKM-PA-04512', 'farid.hassan@example.com', 'pending', true,
                'Career counsellor for school leavers. Applied to review career and education papers.'],
        ];

        foreach ($rows as [$name, $title, $field, $inst, $qual, $reg, $email, $status, $public, $bio]) {
            Reviewer::updateOrCreate(['email' => $email], [
                'name' => $name, 'slug' => Str::slug($name), 'title' => $title, 'field' => $field,
                'institution' => $inst, 'qualifications' => $qual, 'registration_no' => $reg, 'bio' => $bio,
                'status' => $status, 'show_publicly' => $public,
                'approved_at' => $status === 'approved' ? now()->subMonths(3) : null,
            ]);
        }
    }
}
