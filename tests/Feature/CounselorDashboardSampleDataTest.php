<?php

namespace Tests\Feature;

use Tests\TestCase;

class CounselorDashboardSampleDataTest extends TestCase
{
    protected array $session = [
        'counselor_logged_in' => true,
        'counselor_name'      => 'Counselor User',
    ];

    public function test_counselor_dashboard_shows_sample_data_and_correct_stats(): void
    {
        $response = $this->withSession($this->session)->get('/counselor/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Sample data:');
        $response->assertSee('C-1042');
        $response->assertSeeInOrder(["Today's Sessions", '2', 'Active Clients', '5'], false);
        $response->assertSeeInOrder(['Pending Reports', '2'], false);
        $response->assertSee('Pending');
        $response->assertSee('bg-amber-50', false);
        $response->assertSee('Today, 10:00 AM');
        $response->assertDontSee('@example.com');
        $response->assertDontSee('@werta.com');
    }

    public function test_counselor_clients_shows_sample_data_and_no_invented_fallbacks(): void
    {
        $response = $this->withSession($this->session)->get('/counselor/clients');

        $response->assertStatus(200);
        $response->assertSee('Sample data:');
        $response->assertSee('C-0988');
        $response->assertSee('Discharged');
        $response->assertDontSee('responded well to interventions');
        $response->assertDontSee('Cognitive Behavioral Therapy');
        $response->assertDontSee('@example.com');
        $response->assertDontSee('@werta.com');
    }

    public function test_counselor_schedule_shows_sample_appointments(): void
    {
        $response = $this->withSession($this->session)->get('/counselor/schedule');

        $response->assertStatus(200);
        $response->assertSee('Sample data:');
        $response->assertSee('G-07');
        $response->assertDontSee('@example.com');
        $response->assertDontSee('@werta.com');
    }
}
