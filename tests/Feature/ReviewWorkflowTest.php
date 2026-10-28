<?php

namespace Tests\Feature;

use AdminDashboard\Models\Reviewer;
use App\Support\SessionRoles;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Submissions\Database\Seeders\SubmissionSeeder;
use Submissions\Models\Submission;
use Submissions\Models\SubmissionReview;
use Tests\TestCase;

/** Reviewer role, editorial workflow, admin pages and the demo login. */
class ReviewWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(SubmissionSeeder::class);
        $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class);
    }

    private function reviewerSession(string $email = 'aisyah.rahman@example.com'): array
    {
        $r = Reviewer::where('email', $email)->firstOrFail();

        return ['reviewer_logged_in' => true, 'reviewer_id' => $r->id, 'reviewer_name' => $r->name];
    }

    public function test_reviewer_login_and_guard(): void
    {
        $this->get('/reviewer/dashboard')->assertRedirect('/reviewer/login');
        $this->get('/reviewer/login')->assertOk()->assertSee('Reviewer Portal');
        $this->post('/reviewer/login', ['username' => 'reviewer', 'password' => 'reviewer'])->assertRedirect('/reviewer/dashboard');
        $this->assertTrue(session('reviewer_logged_in'));
        $this->get('/reviewer/logout')->assertRedirect('/');
        foreach (SessionRoles::ALL_ROLE_KEYS as $key) {
            $this->assertNull(session($key));
        }
    }

    public function test_pending_reviewer_cannot_use_the_dashboard(): void
    {
        $this->withSession($this->reviewerSession('farid.hassan@example.com'))
            ->get('/reviewer/dashboard')->assertRedirect('/reviewer/login');
    }

    public function test_reviewer_evaluates_an_assigned_paper(): void
    {
        $paper = Submission::where('slug', 'the-science-of-teen-sleep-cycles')->firstOrFail();
        $review = SubmissionReview::where('submission_id', $paper->id)
            ->whereHas('reviewer', fn ($q) => $q->where('email', 'aisyah.rahman@example.com'))->firstOrFail();

        $this->withSession($this->reviewerSession())->get('/reviewer/dashboard')->assertOk()->assertSee($paper->title);
        $this->get('/reviewer/reviews/' . $review->id)->assertOk()->assertSee('Is it suitable to sell?')->assertDontSee('Counselor User');
        $this->get('/reviewer/completed')->assertOk();
        $this->get('/reviewer/guidelines')->assertOk();

        $this->post('/reviewer/reviews/' . $review->id, [
            'score_accuracy' => 5, 'score_originality' => 4, 'score_clarity' => 5, 'score_relevance' => 5, 'score_ethics' => 5,
            'recommendation' => 'accept', 'suitable_for_sale' => '1', 'comments' => 'Clear and accurate.',
            'no_conflict' => '1', 'show_on_article' => '1',
        ])->assertRedirect('/reviewer/dashboard');

        $review->refresh();
        $this->assertSame('completed', $review->status);
        $this->assertTrue($review->suitable_for_sale);
        $this->assertSame(4.8, $review->overallScore());
    }

    public function test_reviewer_cannot_open_someone_elses_review(): void
    {
        $other = SubmissionReview::whereHas('reviewer', fn ($q) => $q->where('email', 'kavitha.nair@example.com'))->firstOrFail();
        $this->withSession($this->reviewerSession())->get('/reviewer/reviews/' . $other->id)->assertForbidden();
    }

    public function test_admin_assigns_decides_and_publishes(): void
    {
        $paper = Submission::where('slug', 'managing-exam-anxiety-a-students-guide')->firstOrFail();
        $lim = Reviewer::where('email', 'lim.weijie@example.com')->firstOrFail();

        $this->withSession(['staff_role' => 'admin'])->get('/admin/reviews')->assertOk()->assertSee($paper->title);
        $this->post('/admin/reviews/' . $paper->id . '/assign', ['reviewer_id' => $lim->id, 'due_days' => 14])->assertRedirect();
        $this->assertSame('under_review', $paper->fresh()->status);
        $this->assertDatabaseHas('submission_reviews', ['submission_id' => $paper->id, 'reviewer_id' => $lim->id, 'status' => 'assigned']);

        $this->post('/admin/reviews/' . $paper->id . '/decision', ['decision' => 'accept', 'access_type' => 'free', 'notes' => 'Great'])->assertRedirect();
        $this->assertSame('accepted', $paper->fresh()->status);

        $this->post('/admin/reviews/' . $paper->id . '/publish')->assertRedirect();
        $this->assertSame('published', $paper->fresh()->status);
        $this->get('/articles')->assertSee($paper->title);
    }

    public function test_admin_verifies_reviewer_applications(): void
    {
        $this->get('/reviewer/apply')->assertOk();
        $this->post('/reviewer/apply', [
            'name' => 'Dr. New Person', 'email' => 'new@example.com', 'title' => 'Psychiatrist', 'field' => 'mental-health',
            'qualifications' => 'MBBS, MPsych', 'agree' => '1',
        ])->assertRedirect(route('reviewer.apply'));
        $new = Reviewer::where('email', 'new@example.com')->firstOrFail();
        $this->assertSame('pending', $new->status);

        $this->withSession(['staff_role' => 'superadmin'])->get('/superadmin/reviewers')->assertOk()->assertSee('Dr. New Person');
        $this->post('/superadmin/reviewers/' . $new->id . '/approve')->assertRedirect();
        $this->assertSame('approved', $new->fresh()->status);
    }

    public function test_admin_pages_render_with_charts(): void
    {
        foreach (['admin', 'superadmin'] as $role) {
            $this->flushSession();
            $session = ['staff_role' => $role];
            $this->withSession($session)->get("/{$role}/dashboard")->assertOk()->assertSee('revenueChart', false);
            $this->withSession($session)->get("/{$role}/analytics")->assertOk()->assertSee('tokensChart', false)->assertSee('workloadChart', false);
            $this->withSession($session)->get("/{$role}/revenue")->assertOk()->assertSee('payoutChart', false);
            $this->withSession($session)->get("/{$role}/reviews?status=decided")->assertOk();
            $this->withSession($session)->get("/{$role}/reviews?status=published")->assertOk();
            $this->withSession($session)->get("/{$role}/reviewers?tab=approved")->assertOk();
        }
        $this->withSession(['staff_role' => 'admin'])->get('/admin/reviews')->assertOk();
        $this->flushSession();
        $this->withSession(['client_logged_in' => true])->get('/admin/reviews')->assertRedirect('/admin/login');
    }

    public function test_demo_login_switches_roles(): void
    {
        config(['dev.demo_login' => true]);
        $this->get('/demo')->assertOk()->assertSee('Choose who to be');
        $this->post('/demo/client')->assertRedirect('/articles');
        $this->assertTrue(session('client_logged_in'));
        $this->post('/demo/reviewer', ['reviewer_id' => Reviewer::where('email', 'lim.weijie@example.com')->value('id')])->assertRedirect('/reviewer/dashboard');
        $this->assertNull(session('client_logged_in'));
        $this->assertTrue(session('reviewer_logged_in'));
        $this->post('/demo/admin')->assertRedirect('/admin/dashboard');
        $this->assertSame('admin', session('staff_role'));

        config(['dev.demo_login' => false]);
        $this->get('/demo')->assertNotFound();
    }

    public function test_localized_new_pages_render(): void
    {
        config(['dev.demo_login' => true]);
        $reviewer = $this->reviewerSession();
        $review = SubmissionReview::where('reviewer_id', $reviewer['reviewer_id'])->firstOrFail();
        $pages = [
            [[], ['/demo', '/articles', '/articles/mindfulness-for-exam-season', '/authors/counselor/counselor-user', '/reviewers/dr-aisyah-rahman', '/reviewer/apply', '/reviewer/login']],
            [['client_logged_in' => true, 'client_profile' => ['username' => 'Client User']], ['/wallet', '/articles/choosing-a-career-path-after-spm/subscribe', '/articles/choosing-a-career-path-after-spm']],
            [['counselor_logged_in' => true, 'counselor_name' => 'Counselor User'], ['/submissions', '/submissions/earnings', '/submissions/create']],
            [$reviewer, ['/reviewer/dashboard', '/reviewer/reviews/' . $review->id, '/reviewer/completed', '/reviewer/guidelines']],
            [['staff_role' => 'admin'], ['/admin/dashboard', '/admin/analytics', '/admin/revenue', '/admin/reviews', '/admin/reviewers']],
        ];
        $untranslated = [];
        foreach (['ms', 'zh'] as $locale) {
            foreach ($pages as [$session, $paths]) {
                $this->flushSession();
                foreach ($paths as $path) {
                    $response = $this->withSession($session + ['locale' => $locale])->get($path)->assertOk();
                    // Same rule as SiteLocalizationTest: catalog text must never show in English.
                    $document = new \DOMDocument;
                    @$document->loadHTML('<?xml encoding="UTF-8">' . $response->getContent());
                    $nodes = (new \DOMXPath($document))->query('//text()[not(ancestor::script) and not(ancestor::style)] | //@placeholder | //@aria-label | //@alt');
                    $messages = \App\Support\SiteText::catalog();
                    foreach ($nodes as $node) {
                        $text = preg_replace('/\s+/u', ' ', trim($node->nodeValue));
                        if (isset($messages[$text]) && $messages[$text] !== $text) {
                            $untranslated[] = $locale . ' ' . $path . ': ' . $text;
                        }
                    }
                }
            }
        }
        $this->assertSame([], array_values(array_unique($untranslated)), implode("\n", array_unique($untranslated)));
    }
}
