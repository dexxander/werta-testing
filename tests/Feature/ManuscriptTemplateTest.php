<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Submissions\Database\Seeders\SubmissionSeeder;
use Submissions\Models\Submission;
use Submissions\Support\ContentRenderer;
use Submissions\Support\ManuscriptTemplates;
use Tests\TestCase;

/** Manuscript templates on the write form and how structured text is rendered. */
class ManuscriptTemplateTest extends TestCase
{
    use RefreshDatabase;

    private array $client = ['client_logged_in' => true, 'client_profile' => ['username' => 'Client User', 'picture' => 'bi-person-circle']];

    public function test_write_form_offers_templates_guidelines_and_word_template(): void
    {
        $this->withSession($this->client)->get('/submissions/create')
            ->assertOk()
            ->assertSee('Start from a template')
            ->assertSee('Research paper')
            ->assertSee('Practical guide')
            ->assertSee('Personal reflection')
            ->assertSee('id="tpl-research"', false)
            ->assertSee('## 1. Introduction')
            ->assertSee('Guide for authors')
            ->assertSee('downloads/werta-manuscript-template.docx', false);

        $this->assertFileExists(public_path('downloads/werta-manuscript-template.docx'));
    }

    public function test_untouched_template_guidance_is_rejected(): void
    {
        $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class);

        $this->withSession($this->client)->post('/submissions', [
            'title' => 'Template Left As Is', 'category' => 'education', 'visibility' => 'public', 'access_type' => 'free',
            'content' => ManuscriptTemplates::text('guide'),
        ])->assertSessionHasErrors();
        $this->assertDatabaseMissing('submissions', ['title' => 'Template Left As Is']);

        $this->post('/submissions', [
            'title' => 'Template Filled In', 'category' => 'education', 'visibility' => 'public', 'access_type' => 'free',
            'content' => "## Who this guide is for\n\nParents of Form 5 students.\n\n## Key points\n\n- Sleep matters\n- Plan revision",
        ])->assertRedirect(route('submissions.index'));
        $this->assertDatabaseHas('submissions', ['title' => 'Template Filled In']);
    }

    public function test_renderer_escapes_text_and_formats_headings_and_lists(): void
    {
        $html = (string) ContentRenderer::html("## Intro <b>\n\nFirst <script>x</script>\n\n- one\n- two");

        $this->assertStringContainsString('<h2>Intro &lt;b&gt;</h2>', $html);
        $this->assertStringContainsString('<p>First &lt;script&gt;x&lt;/script&gt;</p>', $html);
        $this->assertStringContainsString('<ul><li>one</li><li>two</li></ul>', $html);
    }

    public function test_structured_paper_preview_shows_headings_and_hides_later_sections(): void
    {
        $this->seed(SubmissionSeeder::class);
        $paper = Submission::where('slug', 'study-skills-that-actually-stick')->firstOrFail();

        $this->get('/articles/' . $paper->slug)
            ->assertOk()
            ->assertSee('<h2>Who this guide is for</h2>', false)
            ->assertSee('<h2>Key points</h2>', false)
            ->assertDontSee('Step 4: Ask your child')
            ->assertSee('End of free preview');
    }
}
