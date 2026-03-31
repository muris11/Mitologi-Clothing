<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminFlowTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Create admin user directly instead of running full seeder (which is MySQL-oriented)
        $this->admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);
    }

    /**
     * Test that guests are redirected to login when accessing admin area.
     */
    public function test_guest_cannot_access_admin_dashboard(): void
    {
        $response = $this->get('/admin');

        // Auth middleware redirects guests to login
        $response->assertRedirect(route('login'));
    }

    /**
     * Test that admin can login and see dashboard.
     */
    public function test_admin_can_access_dashboard(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin');

        $response->assertStatus(200);
    }

    /**
     * Test that admin can view product list.
     */
    public function test_admin_can_list_products(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/products');

        $response->assertStatus(200);
    }

    /**
     * Test that admin can view order list.
     */
    public function test_admin_can_list_orders(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/orders');

        $response->assertStatus(200);
    }
}
