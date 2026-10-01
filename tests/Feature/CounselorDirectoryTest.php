<?php

namespace Tests\Feature;

use AdminDashboard\Models\Counselor;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class CounselorDirectoryTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate');
    }

    public function test_only_approved_counselors_are_listed_and_pending_or_rejected_are_not(): void
    {
        Counselor::create([
            'name'             => 'Aisyah binti Ahmad',
            'qualification'    => 'M.Couns',
            'email'            => 'aisyah@example.com',
            'status'           => 'approved',
            'display_title'    => 'Counselor',
            'specialties'      => ['Academic Anxiety'],
            'languages'        => ['English', 'Bahasa Melayu'],
            'session_modes'    => ['Online'],
            'state'            => 'Kuala Lumpur',
            'years_experience' => 5,
            'bio'              => 'Aisyah offers a calm and structured environment for students. She focuses on practical coping methods.',
            'rate_individual'  => 100.00,
            'is_sample'        => true,
        ]);

        Counselor::create([
            'name'             => 'Pending Applicant',
            'qualification'    => 'PhD Clin Psych',
            'email'            => 'pending@example.com',
            'status'           => 'pending',
            'display_title'    => 'Counselor',
            'specialties'      => ['Depression & Mood'],
            'languages'        => ['English'],
            'session_modes'    => ['Online'],
            'state'            => 'Selangor',
            'years_experience' => 10,
            'bio'              => 'Bio for pending counselor applicant.',
            'rate_individual'  => 150.00,
            'is_sample'        => true,
        ]);

        Counselor::create([
            'name'             => 'Rejected Applicant',
            'qualification'    => 'B.Psych',
            'email'            => 'rejected@example.com',
            'status'           => 'rejected',
            'display_title'    => 'Counselor',
            'specialties'      => ['Career Transitions'],
            'languages'        => ['English'],
            'session_modes'    => ['In person'],
            'state'            => 'Melaka',
            'years_experience' => 2,
            'bio'              => 'Bio for rejected counselor applicant.',
            'rate_individual'  => 80.00,
            'is_sample'        => true,
        ]);

        $response = $this->get('/counselors');

        $response->assertStatus(200);
        $response->assertSee('Aisyah binti Ahmad');
        $response->assertDontSee('Pending Applicant');
        $response->assertDontSee('Rejected Applicant');

        // Never expose qualification or email on public directory
        $response->assertDontSee('aisyah@example.com');
        $response->assertDontSee('pending@example.com');
        $response->assertDontSee('M.Couns');
        $response->assertDontSee('PhD Clin Psych');
    }

    public function test_counselor_directory_filters_correctly_by_specialty(): void
    {
        Counselor::create([
            'name'             => 'Specialist Anxiety',
            'qualification'    => 'M.Couns',
            'email'            => 'anxiety@example.com',
            'status'           => 'approved',
            'display_title'    => 'Counselor',
            'specialties'      => ['Academic Anxiety'],
            'languages'        => ['English'],
            'session_modes'    => ['Online'],
            'state'            => 'Sabah',
            'years_experience' => 6,
            'bio'              => 'Supportive sessions focused on managing academic pressures. Clear and steady pacing.',
            'rate_individual'  => 110.00,
            'is_sample'        => true,
        ]);

        Counselor::create([
            'name'             => 'Specialist Relationships',
            'qualification'    => 'M.Couns',
            'email'            => 'rel@example.com',
            'status'           => 'approved',
            'display_title'    => 'Counselor',
            'specialties'      => ['Relationship Counseling'],
            'languages'        => ['English'],
            'session_modes'    => ['Online'],
            'state'            => 'Sarawak',
            'years_experience' => 8,
            'bio'              => 'Navigating family and interpersonal dynamics together. Respectful and attentive approach.',
            'rate_individual'  => 140.00,
            'is_sample'        => true,
        ]);

        $response = $this->get('/counselors?specialty=Academic+Anxiety');

        $response->assertStatus(200);
        $response->assertSee('Specialist Anxiety');
        $response->assertDontSee('Specialist Relationships');
    }

    public function test_counselor_directory_shows_empty_state_when_no_approved_counselors_exist(): void
    {
        // No approved counselors in table
        $response = $this->get('/counselors');

        $response->assertStatus(200);
        $response->assertSee('Directory Coming Soon');
    }

    public function test_counselor_directory_shows_no_results_when_filter_matches_nothing(): void
    {
        Counselor::create([
            'name'             => 'Approved Counselor',
            'qualification'    => 'M.Couns',
            'email'            => 'counselor@example.com',
            'status'           => 'approved',
            'display_title'    => 'Counselor',
            'specialties'      => ['Career Transitions'],
            'languages'        => ['English'],
            'session_modes'    => ['In person'],
            'state'            => 'Johor',
            'years_experience' => 4,
            'bio'              => 'Gentle and structured guidance for career exploration. Objective feedback provided.',
            'rate_individual'  => 95.00,
            'is_sample'        => true,
        ]);

        $response = $this->get('/counselors?specialty=Academic+Anxiety');

        $response->assertStatus(200);
        $response->assertSee('No counselors match your selected filters.');
        $response->assertSee('Clear filters');
    }
}
