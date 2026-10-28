<?php

namespace Tests\Feature;

use Articles\Support\MockBilling as MB;
use Articles\Support\Referrals;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Submissions\Database\Seeders\SubmissionSeeder;
use Submissions\Models\Submission;
use Tests\TestCase;

/** DEMO: token wallet, fixed price, packs, promos, referrals, gifts, subscription and previews. */
class MonetisationMockTest extends TestCase
{
    use RefreshDatabase;

    private array $client = ['client_logged_in' => true, 'client_profile' => ['username' => 'Client User', 'picture' => 'bi-person-circle']];
    private array $parent = ['parent_logged_in' => true, 'parent_profile' => ['username' => 'Parent User', 'picture' => 'bi-person-heart']];
    private array $counselor = ['counselor_logged_in' => true, 'counselor_name' => 'Counselor User'];

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(SubmissionSeeder::class);
        $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class);
    }

    /** Switch role like the real login does: role keys cleared, wallet data kept. */
    private function switchTo(array $session): static
    {
        \App\Support\SessionRoles::clearAllRoles();
        session()->save();   // persist the cleared keys, as the end of a real request would

        return $this->withSession($session);
    }

    private function paper(string $slug): Submission
    {
        return Submission::where('slug', $slug)->firstOrFail();
    }

    public function test_every_premium_paper_has_the_same_price(): void
    {
        $this->assertSame(500, MB::articlePrice());
        $this->assertSame(['5.00'], Submission::where('access_type', 'paid')->pluck('price')->map(fn ($p) => number_format($p, 2))->unique()->values()->all());
        $this->assertSame(80, MB::breakEvenCopies());
    }

    public function test_top_up_buy_and_author_earns_one_ringgit_per_copy(): void
    {
        $paper = $this->paper('choosing-a-career-path-after-spm');
        $before = MB::totalSales($paper);

        $this->withSession($this->client)->post('/wallet/topup', ['pack' => 'single'])->assertSessionHas('mock_ok');
        $this->assertSame(50, MB::state()['paid']);

        $this->post('/articles/' . $paper->slug . '/buy')->assertRedirect('/articles/' . $paper->slug);
        $this->assertSame(0, MB::balance());
        $this->assertSame('purchased', MB::accessVia($paper));
        $this->assertSame($before + 1, MB::totalSales($paper));
        $this->assertSame(MB::totalSales($paper) * 100, MB::authorEarning($paper));

        // Buying twice is refused.
        $this->post('/wallet/topup', ['pack' => 'single']);
        $this->post('/articles/' . $paper->slug . '/buy')->assertSessionHas('mock_error');
    }

    public function test_five_pack_gives_a_free_article_as_bonus_tokens_spent_first(): void
    {
        $this->withSession($this->client)->post('/wallet/topup', ['pack' => 'five']);
        $s = MB::state();
        $this->assertSame(250, $s['paid']);
        $this->assertSame(50, $s['bonus']);
        $this->assertNotNull($s['bonus_expires']);

        $this->post('/articles/choosing-a-career-path-after-spm/buy');
        $s = MB::state();
        $this->assertSame(0, $s['bonus'], 'Bonus tokens are spent first.');
        $this->assertSame(250, $s['paid']);
    }

    public function test_locked_preview_shows_abstract_and_first_paragraphs_only(): void
    {
        $paper = $this->paper('choosing-a-career-path-after-spm');
        $paragraphs = preg_split('/\n\s*\n/', $paper->content);

        $response = $this->get('/articles/' . $paper->slug)->assertOk();
        $response->assertSee($paper->abstract);
        $response->assertSee($paragraphs[0]);
        $response->assertDontSee($paragraphs[3]);  // never sent to the browser while locked
        $response->assertSee('End of free preview');
    }

    public function test_pdf_paper_preview_uses_the_pdf_reader(): void
    {
        $this->get('/articles/mindfulness-for-exam-season')
            ->assertOk()
            ->assertSee('data-mode="preview"', false)
            ->assertSee('pdf.min.js', false);
    }

    public function test_gift_unlocks_for_recipient_and_counts_as_a_sale(): void
    {
        $paper = $this->paper('choosing-a-career-path-after-spm');
        $before = MB::totalSales($paper);

        $this->withSession($this->client)->post('/wallet/topup', ['pack' => 'single']);
        $this->post('/articles/' . $paper->slug . '/gift', ['recipient' => 'parent:Parent User', 'note' => 'For you'])
            ->assertRedirect(route('mock.wallet'));

        $this->assertSame($before + 1, MB::totalSales($paper));
        $this->assertSame('gift', MB::stateFor('parent:Parent User')['purchases'][$paper->slug]['via']);

        // The recipient now has access.
        $this->switchTo($this->parent)->get('/articles/' . $paper->slug)->assertOk()->assertSee('a gift from Client User', false);
    }

    public function test_cannot_gift_to_the_author(): void
    {
        $this->withSession($this->client)->post('/wallet/topup', ['pack' => 'single']);
        $this->post('/articles/choosing-a-career-path-after-spm/gift', ['recipient' => 'counselor:Counselor User'])
            ->assertSessionHas('mock_error');
    }

    public function test_promo_codes(): void
    {
        $this->withSession($this->client)->post('/wallet/promo', ['code' => 'welcome50']);
        $this->assertSame(50, MB::state()['bonus']);
        $this->post('/wallet/promo', ['code' => 'WELCOME50'])->assertSessionHas('mock_error');

        $this->post('/wallet/promo', ['code' => 'EXAM20']);
        $this->post('/wallet/topup', ['pack' => 'double']);   // 100 paid → +20 bonus
        $this->assertSame(70, MB::state()['bonus']);
        $this->post('/wallet/promo', ['code' => 'NOPE'])->assertSessionHas('mock_error');
    }

    public function test_referral_rewards_both_after_first_paid_top_up(): void
    {
        $code = Referrals::codeFor('client:Client User');

        $this->withSession($this->parent)->post('/wallet/referral', ['code' => $code])->assertSessionHas('mock_ok');
        $this->assertSame(0, MB::state()['bonus'], 'Nothing is paid before a top-up.');

        $this->post('/wallet/topup', ['pack' => 'single']);
        $this->assertSame(Referrals::RULES['referee_tokens'], MB::state()['bonus']);
        $this->assertSame(Referrals::RULES['referrer_tokens'], MB::stateFor('client:Client User')['bonus']);

        // Own code is refused.
        $this->switchTo($this->client)->post('/wallet/referral', ['code' => $code])->assertSessionHas('mock_error');
    }

    public function test_subscription_reads_lock_again_after_expiry_and_pay_the_author_nothing(): void
    {
        $paper = $this->paper('choosing-a-career-path-after-spm');
        $before = MB::totalSales($paper);

        $this->withSession($this->client)->post('/wallet/topup', ['pack' => 'five']);
        $this->post('/subscription/start');
        $this->assertTrue(MB::state()['sub']['active']);
        $this->post('/articles/' . $paper->slug . '/use-allowance');
        $this->assertSame('subscription', MB::accessVia($paper));
        $this->assertSame($before, MB::totalSales($paper));

        $this->post('/subscription/expire');
        $this->assertSame('locked', MB::accessVia($paper));
    }

    public function test_author_pays_publish_fee_and_paper_goes_live(): void
    {
        $paper = $this->paper('supporting-a-child-through-school-transitions');
        $this->assertSame('accepted', $paper->status);

        $this->withSession($this->counselor)->post('/submissions/' . $paper->id . '/pay-fee')->assertSessionHas('mock_error');
        $this->switchTo($this->parent)->post('/submissions/' . $paper->id . '/pay-fee')->assertRedirect();
        $this->assertSame('published', $paper->fresh()->status);
    }

    public function test_reader_pages_render(): void
    {
        $this->get('/wallet')->assertOk()->assertSee('Sign in to use the wallet.');
        $this->get('/articles')->assertOk()->assertSee('Discovery Map')->assertSee('All Articles');
        $this->get('/articles?category=science')->assertOk();
        $this->get('/articles?access=paid&sort=popular&q=sleep')->assertOk();
        $this->get('/articles/choosing-a-career-path-after-spm/subscribe')->assertOk()->assertSee('Sign in to continue');
        $this->get('/articles/peer-support-circles-in-secondary-schools/subscribe')->assertOk()->assertSee('This article is free');
        $this->get('/authors/counselor/counselor-user')->assertOk()->assertSee('Readership by paper');
        $this->get('/authors/counselor/nobody')->assertNotFound();
        $this->get('/reviewers/dr-aisyah-rahman')->assertOk()->assertSee('Papers reviewed');
        $this->get('/reviewers/tan-mei-ling')->assertNotFound();   // chose to stay anonymous

        $this->withSession($this->client)->get('/wallet')->assertOk()->assertSee('WT-');
        $this->get('/articles/choosing-a-career-path-after-spm/subscribe')->assertOk()->assertSee('Option 3');
        $this->switchTo($this->counselor)->get('/submissions')->assertOk()->assertSee('Author studio');
        $this->get('/submissions?tab=published')->assertOk();
        $this->get('/submissions/earnings')->assertOk()->assertSee('Copies sold per month');
        $this->post('/submissions/earnings/withdraw')->assertSessionHas('mock_ok');
    }

    public function test_article_page_shows_named_reviewers_and_hides_anonymous_ones(): void
    {
        $this->get('/articles/coping-with-social-media-pressure')
            ->assertOk()
            ->assertSee('Dr. Aisyah Rahman')
            ->assertDontSee('Tan Mei Ling')
            ->assertSee('chose to stay anonymous');
    }

    public function test_new_submission_needs_a_category_and_stores_the_fixed_price(): void
    {
        $this->withSession($this->client)->post('/submissions', [
            'title' => 'A New Premium Paper', 'content' => 'Body', 'visibility' => 'public', 'access_type' => 'paid',
        ])->assertSessionHasErrors('category');

        $this->post('/submissions', [
            'title' => 'A New Premium Paper', 'category' => 'education', 'content' => 'Body',
            'visibility' => 'public', 'access_type' => 'paid', 'price' => 99,
        ])->assertRedirect(route('submissions.index'));

        $paper = Submission::where('slug', 'a-new-premium-paper')->firstOrFail();
        $this->assertSame('education', $paper->category);
        $this->assertSame('5.00', number_format($paper->price, 2));
    }
}
