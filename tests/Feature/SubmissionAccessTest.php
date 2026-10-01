<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Submissions\Models\Submission;
use Tests\TestCase;

class SubmissionAccessTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate');
    }

    public function test_guest_get_submissions_create_shows_sign_in_and_no_form(): void
    {
        $response = $this->get('/submissions/create');
        $response->assertStatus(200);
        $response->assertSee('Sign in to publish an article');
        $response->assertDontSee('name="title"', false);
    }

    public function test_guest_get_submissions_index_shows_sign_in(): void
    {
        $response = $this->get('/submissions');
        $response->assertStatus(200);
        $response->assertSee('Sign in to publish an article');
    }

    public function test_guest_post_submissions_redirects_and_creates_no_submission(): void
    {
        $initialCount = Submission::count();

        $response = $this->post('/submissions', [
            'title'       => 'Guest Attempt',
            'content'     => 'Some content written by guest',
            'visibility'  => 'public',
            'access_type' => 'free',
        ]);

        $response->assertRedirect(route('submissions.create'));
        $this->assertSame($initialCount, Submission::count());
    }

    public function test_admin_get_submissions_create_shows_staff_not_available_message(): void
    {
        $response = $this->withSession([
            'staff_role' => 'admin',
        ])->get('/submissions/create');

        $response->assertStatus(200);
        $response->assertSee('Publishing is not available for staff accounts');
    }

    public function test_admin_home_does_not_see_publish_an_article(): void
    {
        $response = $this->withSession([
            'staff_role' => 'admin',
        ])->get('/');

        $response->assertStatus(200);
        $response->assertDontSee('Publish an Article');
    }

    public function test_client_get_submissions_create_shows_form(): void
    {
        $response = $this->withSession([
            'client_logged_in' => true,
            'client_profile'   => ['username' => 'Client User'],
        ])->get('/submissions/create');

        $response->assertStatus(200);
        $response->assertSee('name="title"', false);
    }
}
