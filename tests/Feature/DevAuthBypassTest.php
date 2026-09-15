<?php

namespace Tests\Feature;

use App\Support\DevAuth;
use Tests\TestCase;

class DevAuthBypassTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Default to safe state for each test
        config(['dev.bypass_auth' => false]);
        config(['app.debug' => true]);
    }

    public function test_dev_auth_is_disabled_by_default()
    {
        $this->assertFalse(DevAuth::isBypassActive());
    }

    public function test_dev_auth_ignores_legacy_app_dev_bypass_auth_key()
    {
        // Even if someone puts dev_bypass_auth in app.php, it must be completely ignored
        config(['app.dev_bypass_auth' => true]);
        config(['dev.bypass_auth' => false]);

        $this->assertFalse(
            DevAuth::isBypassActive(),
            'DevAuth must rely solely on config("dev.bypass_auth") and ignore any legacy app config.'
        );
    }

    public function test_triple_lock_requires_local_env_and_debug_mode()
    {
        $this->app->detectEnvironment(fn () => 'local');
        config(['dev.bypass_auth' => true]);
        config(['app.debug' => true]);

        // Local + debug + flag => active
        $this->assertTrue(DevAuth::isBypassActive());

        // When debug is false => inactive
        config(['app.debug' => false]);
        $this->assertFalse(DevAuth::isBypassActive());

        // When environment is production => inactive
        config(['app.debug' => true]);
        $this->app->detectEnvironment(fn () => 'production');
        $this->assertFalse(DevAuth::isBypassActive());
    }

    public function test_forged_dev_bypass_post_is_rejected_when_flag_is_off()
    {
        $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class);
        config(['dev.bypass_auth' => false]);

        $portals = [
            ['/client/login', 'client_logged_in'],
            ['/parent/login', 'parent_logged_in'],
            ['/counselor/login', 'counselor_logged_in'],
            ['/admin/login', 'staff_role'],
            ['/superadmin/login', 'staff_role'],
        ];

        foreach ($portals as [$url, $sessionKey]) {
            $response = $this->post($url, [
                'dev_bypass' => '1',
            ]);

            $response->assertSessionMissing($sessionKey);
            $response->assertSessionMissing('dev_bypass_session');
            $response->assertSessionHas('error');
        }
    }

    public function test_dev_bypass_works_across_all_roles_when_enabled()
    {
        $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class);
        $this->app->detectEnvironment(fn () => 'local');
        config(['dev.bypass_auth' => true]);

        // Client
        $response = $this->post('/client/login', ['dev_bypass' => '1']);
        $response->assertRedirect('/client/dashboard');
        $response->assertSessionHas('client_logged_in', true);
        $response->assertSessionHas('dev_bypass_session', true);

        // Parent
        $this->flushSession();
        $response = $this->post('/parent/login', ['dev_bypass' => '1']);
        $response->assertRedirect('/parent/dashboard');
        $response->assertSessionHas('parent_logged_in', true);
        $response->assertSessionHas('dev_bypass_session', true);

        // Counselor
        $this->flushSession();
        $response = $this->post('/counselor/login', ['dev_bypass' => '1']);
        $response->assertRedirect('/counselor/dashboard');
        $response->assertSessionHas('counselor_logged_in', true);
        $response->assertSessionHas('dev_bypass_session', true);

        // Admin
        $this->flushSession();
        $response = $this->post('/admin/login', ['dev_bypass' => '1']);
        $response->assertRedirect('/admin/dashboard');
        $response->assertSessionHas('staff_role', 'admin');
        $response->assertSessionHas('dev_bypass_session', true);

        // Superadmin
        $this->flushSession();
        $response = $this->post('/superadmin/login', ['dev_bypass' => '1']);
        $response->assertRedirect('/superadmin/dashboard');
        $response->assertSessionHas('staff_role', 'superadmin');
        $response->assertSessionHas('dev_bypass_session', true);
    }

    public function test_login_ui_omits_dev_bypass_button_when_disabled()
    {
        config(['dev.bypass_auth' => false]);

        $response = $this->get('/client/login');
        $response->assertStatus(200);
        $response->assertDontSee('Dev: Skip Login');
        $response->assertDontSee('DEV_BYPASS_AUTH active');
    }

    public function test_login_ui_shows_dev_bypass_button_when_enabled()
    {
        $this->app->detectEnvironment(fn () => 'local');
        config(['dev.bypass_auth' => true]);

        $response = $this->get('/client/login');
        $response->assertStatus(200);
        $response->assertSee('Dev: Skip Login');
        $response->assertSee('DEV_BYPASS_AUTH active');
    }
}
