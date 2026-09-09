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
}
