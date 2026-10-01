<?php

namespace Tests\Feature;

use Tests\TestCase;

class DashboardLayoutTest extends TestCase
{
    public function test_client_dashboard_layout_has_responsive_sidebar_and_toggle(): void
    {
        $response = $this->withSession([
            'client_logged_in' => true,
            'client_profile' => ['username' => 'Client User', 'picture' => 'bi-person-circle'],
        ])->get('/client/dashboard');

        $response->assertStatus(200);
        $response->assertSee('aria-label="Toggle navigation"', false);
        $response->assertSee('sidebarOpen: window.innerWidth >= 1024', false);
    }

    public function test_parent_dashboard_layout_has_responsive_sidebar_and_toggle(): void
    {
        $response = $this->withSession([
            'parent_logged_in' => true,
            'parent_profile' => ['username' => 'Parent User', 'picture' => 'bi-person-heart'],
        ])->get('/parent/dashboard');

        $response->assertStatus(200);
        $response->assertSee('aria-label="Toggle navigation"', false);
        $response->assertSee('sidebarOpen: window.innerWidth >= 1024', false);
    }

    public function test_counselor_dashboard_layout_has_responsive_sidebar_and_toggle(): void
    {
        $response = $this->withSession([
            'counselor_logged_in' => true,
            'counselor_name' => 'Counselor User',
        ])->get('/counselor/dashboard');

        $response->assertStatus(200);
        $response->assertSee('aria-label="Toggle navigation"', false);
        $response->assertSee('sidebarOpen: window.innerWidth >= 1024', false);
    }

    public function test_admin_dashboard_layout_has_responsive_sidebar_and_toggle(): void
    {
        $response = $this->withSession([
            'staff_role' => 'admin',
        ])->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee('aria-label="Toggle navigation"', false);
        $response->assertSee('sidebarOpen: window.innerWidth >= 1024', false);
    }

    public function test_superadmin_dashboard_layout_has_responsive_sidebar_and_toggle(): void
    {
        $response = $this->withSession([
            'staff_role' => 'superadmin',
        ])->get('/superadmin/dashboard');

        $response->assertStatus(200);
        $response->assertSee('aria-label="Toggle navigation"', false);
        $response->assertSee('sidebarOpen: window.innerWidth >= 1024', false);
    }
}
