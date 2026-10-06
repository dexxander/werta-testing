<?php

namespace Tests\Feature;

use AdminDashboard\Models\Counselor;
use App\Support\SiteText;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Submissions\Models\Submission;
use Tests\TestCase;

class SiteLocalizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_page_families_render_translated_body_copy(): void
    {
        $counselor = Counselor::create([
            'name' => 'Aisyah Ahmad', 'qualification' => 'M.Couns', 'email' => 'aisyah@example.com',
            'status' => 'approved', 'display_title' => 'Counselor', 'specialties' => ['Academic Anxiety'],
            'languages' => ['English', 'Bahasa Melayu'], 'session_modes' => ['Online', 'In person'],
            'state' => 'Sabah', 'years_experience' => 5, 'bio' => 'Original author biography.',
            'rate_individual' => 100, 'is_sample' => true,
        ]);
        $article = Submission::create([
            'title' => 'Original Author Title', 'slug' => 'original-author-title',
            'abstract' => 'Original author abstract.', 'content' => 'Original author content.',
            'author_role' => 'client', 'author_name' => 'Client User', 'status' => 'published',
            'visibility' => 'public', 'access_type' => 'free', 'submitted_at' => now(),
        ]);
        $groups = [
            [[], ['/', '/about', '/counselors', '/assessment', '/assessment/questions', '/assessment/processing', '/assessment/email', '/assessment/results', '/articles', '/guidance-center', '/client/login', '/parent/login', '/counselor/login', '/admin/login', '/superadmin/login', '/auth/register', '/submissions/create', '/elearning', '/elearning/overview', '/elearning/courses', '/elearning/paths', '/elearning/dashboard', '/elearning/pricing', '/elearning/faq', '/elearning/checkout', '/elearning/course/1', '/elearning/category/web-development']],
            [['client_logged_in' => true], ['/client/dashboard', '/client/appointments', '/client/assessments', '/client/learning', '/client/messages', '/elearning/my-courses', '/elearning/course/1/content', '/submissions/create', '/submissions']],
            [['parent_logged_in' => true], ['/parent/dashboard', '/parent/appointments', '/parent/progress', '/parent/messages']],
            [['counselor_logged_in' => true], ['/counselor/dashboard', '/counselor/clients', '/counselor/schedule', '/counselor/profile', '/counselor/articles']],
            [['staff_role' => 'admin'], ['/admin/dashboard', '/admin/counselor-approvals', '/admin/user-management', '/admin/content', '/admin/analytics', '/admin/settings']],
            [['staff_role' => 'superadmin'], ['/superadmin/dashboard', '/superadmin/administrators', '/superadmin/counselor-approvals', '/superadmin/user-management', '/superadmin/content', '/superadmin/analytics', '/superadmin/settings']],
        ];
        $groups[0][1][] = '/counselors/'.$counselor->id;
        $groups[0][1][] = '/articles/'.$article->slug;
        $groups[0][1][] = '/articles/'.$article->slug.'/subscribe';
        $groups[1][1][] = '/submissions/'.$article->id;
        $untranslated = [];
        $remaining = [];
        $scripts = [];
        foreach (['en', 'ms', 'zh'] as $locale) {
            foreach ($groups as [$session, $paths]) {
                $this->flushSession();
                foreach ($paths as $path) {
                    $response = $this->withSession($session + ['locale' => $locale])->get($path);
                    $response->assertOk();
                    $response->assertSee('lang="'.$locale.'"', false);

                    if ($locale === 'en') {
                        continue;
                    }

                    $document = new \DOMDocument;
                    @$document->loadHTML('<?xml encoding="UTF-8">'.$response->getContent());
                    $xpath = new \DOMXPath($document);
                    $nodes = $xpath->query('//text()[not(ancestor::script) and not(ancestor::style)] | //@placeholder | //@aria-label | //@alt');
                    if (getenv('LOCALE_JS_AUDIT')) {
                        foreach ($xpath->query('//script[not(@src) and (not(@type) or @type="text/javascript")]') as $script) {
                            $scripts[] = ['page' => $locale.' '.$path, 'code' => $script->textContent];
                        }
                    }
                    $messages = SiteText::catalog();
                    foreach ($nodes as $node) {
                        $text = preg_replace('/\s+/u', ' ', trim($node->nodeValue));
                        if (isset($messages[$text]) && $messages[$text] !== $text) {
                            $untranslated[] = $locale.' '.$path.': '.$text;
                        }
                        if ($locale === 'zh' && preg_match('/[A-Za-z]{3,}/', $text) && ! preg_match('/[\x{4e00}-\x{9fff}]/u', $text)) {
                            $remaining[$text] = $path;
                        }
                    }
                }
            }
        }
        if (getenv('LOCALE_AUDIT')) {
            fwrite(STDOUT, json_encode($remaining, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)."\n");
        }
        if (getenv('LOCALE_JS_AUDIT')) {
            file_put_contents(storage_path('framework/testing/localization-scripts.json'), json_encode($scripts, JSON_THROW_ON_ERROR));
        }
        $this->assertSame([], array_unique($untranslated), implode("\n", array_unique($untranslated)));
    }

    public function test_translated_questionnaire_keeps_answer_values_and_form_contracts(): void
    {
        $response = $this->withSession(['locale' => 'zh'])->get('/assessment/questions');
        $response->assertSee('我保持工作空间整齐有序。');
        $response->assertSee('非常不同意');
        $response->assertSee('name="q1_1" value="1"', false);
        $response->assertSee('name="q6_5" value="5"', false);
        $document = new \DOMDocument;
        @$document->loadHTML($response->getContent());
        $this->assertSame(150, (new \DOMXPath($document))->query('//input[@type="radio"]')->length);
    }

    public function test_catalogs_have_matching_keys_and_placeholders(): void
    {
        $english = json_decode(file_get_contents(lang_path('en.json')), true, 512, JSON_THROW_ON_ERROR);
        foreach (['ms', 'zh'] as $locale) {
            $messages = json_decode(file_get_contents(lang_path($locale.'.json')), true, 512, JSON_THROW_ON_ERROR);
            $this->assertSame(array_keys($english), array_keys($messages));
            foreach ($english as $key => $text) {
                $this->assertNotSame('', trim($messages[$key]), $key);
                preg_match_all('/:[a-z_]+/', $text, $source);
                preg_match_all('/:[a-z_]+/', $messages[$key], $target);
                sort($source[0]);
                sort($target[0]);
                $this->assertSame($source[0], $target[0], $locale.': '.$key);
            }
        }
    }

    public function test_server_validation_uses_the_selected_language(): void
    {
        $this->withSession(['locale' => 'ms', 'staff_role' => 'superadmin'])
            ->post('/superadmin/administrators', ['name' => '', 'email' => 'invalid'])
            ->assertSessionHasErrors(['name' => 'Medan nama diperlukan.', 'email' => 'Medan e-mel mestilah alamat e-mel yang sah.']);
    }
}
