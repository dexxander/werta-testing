<?php

namespace Tests\Feature;

use App\Support\SessionRoles;
use Tests\TestCase;

class SessionExclusivityTest extends TestCase
{
    public function test_logging_in_as_client_clears_superadmin_session_and_logout_clears_all_keys()
    {
        $superadminLoginResponse = $this->post('/superadmin/login', [
            'username' => 'superadmin',
            'password' => 'superadmin',
        ]);
        $superadminLoginResponse->assertRedirect('/superadmin/dashboard');
        $this->assertEquals('superadmin', session('staff_role'));
        $this->assertNull(session('client_logged_in'));

        $clientLoginResponse = $this->post('/client/login', [
            'username' => 'client',
            'password' => 'client',
        ]);
        $clientLoginResponse->assertRedirect('/');
        $this->assertTrue(session('client_logged_in'));
        $this->assertNull(session('staff_role'));

        $logoutResponse = $this->get('/client/logout');
        $logoutResponse->assertRedirect('/');

        foreach (SessionRoles::ALL_ROLE_KEYS as $key) {
            $this->assertNull(session($key), "Session key [{$key}] was not cleared on logout.");
        }
    }

    public function test_dev_bypass_clears_prior_role_session_when_switching_roles()
    {
        $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class);
        $this->app->detectEnvironment(fn () => 'local');
        config(['app.debug' => true]);
        config(['dev.bypass_auth' => true]);

        $this->post('/client/login', ['dev_bypass' => '1'])
            ->assertRedirect('/client/dashboard');
        $this->assertTrue(session('client_logged_in'));
        $this->assertNull(session('staff_role'));

        $this->post('/admin/login', ['dev_bypass' => '1'])
            ->assertRedirect('/admin/dashboard');
        $this->assertEquals('admin', session('staff_role'));
        $this->assertNull(session('client_logged_in'));
        $this->assertNull(session('client_profile'));

        $this->get('/admin/logout')
            ->assertRedirect('/');

        foreach (SessionRoles::ALL_ROLE_KEYS as $key) {
            $this->assertNull(session($key), "Session key [{$key}] was not cleared on logout.");
        }
    }

    public function test_counselor_login_and_logout_maintain_exclusivity()
    {
        $this->post('/parent/login', [
            'username' => 'parent',
            'password' => 'parent',
        ])->assertRedirect('/');
        $this->assertTrue(session('parent_logged_in'));

        $this->post('/counselor/login', [
            'username' => 'counselor',
            'password' => 'counselor',
        ])->assertRedirect('/counselor/dashboard');
        $this->assertTrue(session('counselor_logged_in'));
        $this->assertNull(session('parent_logged_in'));
        $this->assertNull(session('parent_profile'));

        $this->get('/counselor/logout')
            ->assertRedirect('/');

        foreach (SessionRoles::ALL_ROLE_KEYS as $key) {
            $this->assertNull(session($key), "Session key [{$key}] was not cleared on counselor logout.");
        }
    }

    public function test_elearning_dashboard_is_public_while_course_routes_remain_client_gated()
    {
        $publicDashboardResponse = $this->get('/elearning/dashboard');
        $publicDashboardResponse->assertStatus(200);

        $gatedCoursesResponse = $this->get('/elearning/my-courses');
        $gatedCoursesResponse->assertRedirect('/client/login');

        $gatedContentResponse = $this->get('/elearning/course/1/content');
        $gatedContentResponse->assertRedirect('/client/login');
    }

    public function test_dashboard_guards_do_not_grant_access_to_different_role()
    {
        $this->flushSession();
        $this->withSession(['parent_logged_in' => true])
            ->get('/client/dashboard')
            ->assertRedirect('/client/login');

        $this->flushSession();
        $this->withSession(['client_logged_in' => true])
            ->get('/parent/dashboard')
            ->assertRedirect('/parent/login');

        $this->flushSession();
        $this->withSession(['staff_role' => 'admin'])
            ->get('/superadmin/dashboard')
            ->assertRedirect('/superadmin/login');

        $this->flushSession();
        $this->withSession(['client_logged_in' => true])
            ->get('/counselor/dashboard')
            ->assertRedirect('/counselor/login');
    }
}
