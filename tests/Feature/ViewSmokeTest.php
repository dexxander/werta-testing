<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;

class ViewSmokeTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate');
    }
    public function test_public_routes_return_ok()
    {
        $routes = [
            '/',
            '/about',
            '/counselors',
            '/assessment',
            '/assessment/questions',
            '/assessment/processing',
            '/assessment/email',
            '/assessment/results',
        ];

        foreach ($routes as $route) {
            $response = $this->get($route);
            $response->assertStatus(200);
        }
    }

    public function test_client_dashboard_routes_with_session()
    {
        $session = [
            'client_logged_in' => true,
            'client_profile' => ['username' => 'Client User', 'picture' => 'bi-person-circle'],
        ];

        $routes = [
            '/client/dashboard',
            '/client/appointments',
            '/client/assessments',
            '/client/learning',
            '/client/messages',
        ];

        foreach ($routes as $route) {
            $response = $this->withSession($session)->get($route);
            $response->assertStatus(200);
        }
    }

    public function test_parent_dashboard_routes_with_session()
    {
        $session = [
            'parent_logged_in' => true,
            'parent_profile' => ['username' => 'Parent User', 'picture' => 'bi-person-heart'],
        ];

        $routes = [
            '/parent/dashboard',
            '/parent/appointments',
            '/parent/progress',
            '/parent/messages',
        ];

        foreach ($routes as $route) {
            $response = $this->withSession($session)->get($route);
            $response->assertStatus(200);
        }
    }

    public function test_counselor_dashboard_routes_with_session()
    {
        $session = [
            'counselor_logged_in' => true,
            'counselor_name' => 'Counselor User',
        ];

        $routes = [
            '/counselor/dashboard',
            '/counselor/clients',
            '/counselor/schedule',
            '/counselor/profile',
            '/counselor/articles',
        ];

        foreach ($routes as $route) {
            $response = $this->withSession($session)->get($route);
            $response->assertStatus(200);
        }
    }

    public function test_admin_dashboard_routes_with_session()
    {
        $session = [
            'staff_role' => 'admin',
        ];

        $routes = [
            '/admin/dashboard',
            '/admin/counselor-approvals',
            '/admin/user-management',
            '/admin/content',
            '/admin/analytics',
            '/admin/settings',
        ];

        foreach ($routes as $route) {
            $response = $this->withSession($session)->get($route);
            $response->assertStatus(200);
        }
    }

    public function test_superadmin_dashboard_routes_with_session()
    {
        $session = [
            'staff_role' => 'superadmin',
        ];

        $routes = [
            '/superadmin/dashboard',
            '/superadmin/administrators',
            '/superadmin/counselor-approvals',
            '/superadmin/user-management',
            '/superadmin/content',
            '/superadmin/analytics',
            '/superadmin/settings',
        ];

        foreach ($routes as $route) {
            $response = $this->withSession($session)->get($route);
            $response->assertStatus(200);
        }
    }

    public function test_navbar_renders_across_all_roles_on_public_pages()
    {
        $roleSessions = [
            'anonymous'  => [],
            'client'     => ['client_logged_in' => true, 'client_profile' => ['username' => 'Client User', 'picture' => 'bi-person-circle']],
            'parent'     => ['parent_logged_in' => true, 'parent_profile' => ['username' => 'Parent User', 'picture' => 'bi-person-heart']],
            'counselor'  => ['counselor_logged_in' => true, 'counselor_name' => 'Counselor User'],
            'admin'      => ['staff_role' => 'admin'],
            'superadmin' => ['staff_role' => 'superadmin'],
        ];

        foreach ($roleSessions as $role => $session) {
            $response = $this->withSession($session)->get('/');
            $response->assertStatus(200);
            $response->assertSee('E-Learning Modules');
            $response->assertSee('btn-account-label');
        }
    }

    public function test_mobile_navbar_renders_accessible_toggle_and_destinations()
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        // Accessible mobile toggle button
        $response->assertSee('data-bs-toggle="collapse"', false);
        $response->assertSee('data-bs-target="#mobileNav"', false);
        $response->assertSee('aria-controls="mobileNav"', false);
        $response->assertSee('aria-expanded="false"', false);
        $response->assertSee('aria-label="Toggle navigation"', false);

        // Collapsed mobile container and destinations
        $response->assertSee('id="mobileNav"', false);
        $response->assertSee('href="' . url('/counselors') . '"', false);
        $response->assertSee('href="' . url('/assessment') . '"', false);
        $response->assertSee('href="' . route('public.articles') . '"', false);
        $response->assertSee('data-bs-target="#mobileElearningMenu"', false);
        $response->assertSee('href="' . route('elearning.courses') . '"', false);
        $response->assertSee('href="' . url('/about') . '"', false);

        // Anonymous account destinations
        $response->assertSee('data-bs-target="#mobileAccountMenu"', false);
        $response->assertSee('href="' . url('/client/login') . '"', false);
        $response->assertSee('href="' . url('/parent/login') . '"', false);
        $response->assertSee('href="' . route('counselor.login') . '"', false);
        $response->assertSee('href="' . url('/auth/register') . '"', false);

        // Authenticated client sees dashboard and logout in mobile menu
        $authResponse = $this->withSession([
            'client_logged_in' => true,
            'client_profile' => ['username' => 'Test Client', 'picture' => 'bi-person-circle'],
        ])->get('/');
        $authResponse->assertStatus(200);
        $authResponse->assertSee('href="/client/dashboard"', false);
        $authResponse->assertSee('href="/client/logout"', false);
    }
}
