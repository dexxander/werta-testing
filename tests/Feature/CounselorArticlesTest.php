<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Submissions\Models\Submission;
use Tests\TestCase;

class CounselorArticlesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate');
    }

    public function test_counselor_articles_index_shows_only_counselor_submissions_with_status_labels(): void
    {
        Submission::create([
            'title'        => 'Mindfulness for Stress Relief',
            'slug'         => 'mindfulness-for-stress-relief',
            'abstract'     => 'An in-depth guide on mindfulness techniques.',
            'content'      => 'Full content here...',
            'author_role'  => 'counselor',
            'author_name'  => 'Counselor User',
            'status'       => 'published',
            'visibility'   => 'public',
            'access_type'  => 'free',
            'submitted_at' => now()->subDays(5),
        ]);

        Submission::create([
            'title'        => 'Navigating Academic Anxiety',
            'slug'         => 'navigating-academic-anxiety',
            'abstract'     => 'A resource for handling exam stress.',
            'content'      => 'Full content here...',
            'author_role'  => 'counselor',
            'author_name'  => 'Counselor User',
            'status'       => 'submitted',
            'visibility'   => 'public',
            'access_type'  => 'free',
            'submitted_at' => now()->subDay(),
        ]);

        Submission::create([
            'title'        => 'My Personal Journey With Therapy',
            'slug'         => 'my-personal-journey-with-therapy',
            'abstract'     => 'A client story.',
            'content'      => 'Full content here...',
            'author_role'  => 'client',
            'author_name'  => 'Client User',
            'status'       => 'published',
            'visibility'   => 'public',
            'access_type'  => 'free',
            'submitted_at' => now()->subDays(2),
        ]);

        $response = $this->withSession([
            'counselor_logged_in' => true,
            'counselor_name'      => 'Counselor User',
        ])->get('/counselor/articles');

        $response->assertStatus(200);
        $response->assertSee('Mindfulness for Stress Relief');
        $response->assertSee('Navigating Academic Anxiety');
        $response->assertSee('Approved');
        $response->assertSee('Waiting for Review');
        $response->assertDontSee('My Personal Journey With Therapy');
    }

    public function test_counselor_articles_create_redirects_to_submissions_create(): void
    {
        $response = $this->withSession([
            'counselor_logged_in' => true,
            'counselor_name'      => 'Counselor User',
        ])->get('/counselor/articles/create');

        $response->assertRedirect(route('submissions.create'));
    }

    public function test_counselor_dashboard_shows_published_articles_count(): void
    {
        Submission::create([
            'title'        => 'First Published Article',
            'slug'         => 'first-published-article',
            'abstract'     => 'Abstract',
            'content'      => 'Content',
            'author_role'  => 'counselor',
            'author_name'  => 'Counselor User',
            'status'       => 'published',
            'visibility'   => 'public',
            'access_type'  => 'free',
            'submitted_at' => now(),
        ]);

        Submission::create([
            'title'        => 'Draft In Review Article',
            'slug'         => 'draft-in-review-article',
            'abstract'     => 'Abstract',
            'content'      => 'Content',
            'author_role'  => 'counselor',
            'author_name'  => 'Counselor User',
            'status'       => 'submitted',
            'visibility'   => 'public',
            'access_type'  => 'free',
            'submitted_at' => now(),
        ]);

        Submission::create([
            'title'        => 'Client Published Article',
            'slug'         => 'client-published-article',
            'abstract'     => 'Abstract',
            'content'      => 'Content',
            'author_role'  => 'client',
            'author_name'  => 'Client User',
            'status'       => 'published',
            'visibility'   => 'public',
            'access_type'  => 'free',
            'submitted_at' => now(),
        ]);

        $response = $this->withSession([
            'counselor_logged_in' => true,
            'counselor_name'      => 'Counselor User',
        ])->get('/counselor/dashboard');

        $response->assertStatus(200);
        $response->assertSeeInOrder(['Articles Published', '1'], false);
    }
}
