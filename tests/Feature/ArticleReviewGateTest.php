<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Submissions\Models\Submission;
use Tests\TestCase;

class ArticleReviewGateTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate');
    }

    public function test_submitted_public_submission_is_not_listed_and_returns_404_for_guest(): void
    {
        $submitted = Submission::create([
            'title'        => 'Unreviewed Article Draft',
            'slug'         => 'unreviewed-article-draft',
            'abstract'     => 'Draft abstract pending editorial review.',
            'content'      => 'This is draft content not yet published.',
            'author_role'  => 'counselor',
            'author_name'  => 'Dr. Jane Doe',
            'status'       => 'submitted',
            'visibility'   => 'public',
            'access_type'  => 'free',
            'submitted_at' => now(),
        ]);

        // A public submission with status 'submitted' is not listed on /articles
        $indexResponse = $this->get('/articles');
        $indexResponse->assertStatus(200);
        $indexResponse->assertDontSee($submitted->title);

        // and returns 404 for a guest
        $showResponse = $this->get('/articles/' . $submitted->slug);
        $showResponse->assertStatus(404);

        $subscribeResponse = $this->get('/articles/' . $submitted->slug . '/subscribe');
        $subscribeResponse->assertStatus(404);
    }

    public function test_published_public_submission_is_listed_and_opens(): void
    {
        $published = Submission::create([
            'title'        => 'Approved And Published Article',
            'slug'         => 'approved-and-published-article',
            'abstract'     => 'Public abstract for everyone to see.',
            'content'      => 'Full published article body.',
            'author_role'  => 'counselor',
            'author_name'  => 'Dr. Jane Doe',
            'status'       => 'published',
            'visibility'   => 'public',
            'access_type'  => 'free',
            'submitted_at' => now(),
        ]);

        // A 'published' one is listed
        $indexResponse = $this->get('/articles');
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee($published->title);

        // and opens
        $showResponse = $this->get('/articles/' . $published->slug);
        $showResponse->assertStatus(200);
        $showResponse->assertSee($published->title);
    }

    public function test_author_can_view_own_submitted_submission(): void
    {
        $submitted = Submission::create([
            'title'        => 'Author Unpublished Work',
            'slug'         => 'author-unpublished-work',
            'abstract'     => 'My unreviewed draft.',
            'content'      => 'Draft content.',
            'author_role'  => 'counselor',
            'author_name'  => 'Counselor User',
            'status'       => 'submitted',
            'visibility'   => 'public',
            'access_type'  => 'free',
            'submitted_at' => now(),
        ]);

        // Counselor author viewing their own submitted article
        $response = $this->withSession([
            'counselor_logged_in' => true,
            'counselor_name'      => 'Counselor User',
        ])->get('/articles/' . $submitted->slug);

        $response->assertStatus(200);
        $response->assertSee($submitted->title);
    }
}
