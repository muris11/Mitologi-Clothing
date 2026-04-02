<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    private User $customer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->customer = User::factory()->create(['role' => 'customer']);
    }

    public function test_checkout_requires_authentication(): void
    {
        $response = $this->postJson('/api/checkout', [
            'shipping_name' => 'John Doe',
            'shipping_phone' => '081234567890',
            'shipping_address' => 'Jl. Test No. 1',
            'shipping_city' => 'Bandung',
            'shipping_province' => 'Jawa Barat',
            'shipping_postal_code' => '40132',
        ]);

        $response->assertUnauthorized();
    }

    public function test_checkout_validates_shipping_data(): void
    {
        $response = $this->actingAs($this->customer, 'sanctum')
            ->postJson('/api/checkout', []);

        $response->assertUnprocessable()
            ->assertJsonPath('error.details.shipping_name', fn ($errors) => is_array($errors) && count($errors) > 0)
            ->assertJsonPath('error.details.shipping_phone', fn ($errors) => is_array($errors) && count($errors) > 0)
            ->assertJsonPath('error.details.shipping_address', fn ($errors) => is_array($errors) && count($errors) > 0)
            ->assertJsonPath('error.details.shipping_city', fn ($errors) => is_array($errors) && count($errors) > 0)
            ->assertJsonPath('error.details.shipping_province', fn ($errors) => is_array($errors) && count($errors) > 0)
            ->assertJsonPath('error.details.shipping_postal_code', fn ($errors) => is_array($errors) && count($errors) > 0);
    }

    public function test_checkout_rejects_empty_cart(): void
    {
        $response = $this->actingAs($this->customer, 'sanctum')
            ->postJson('/api/checkout', [
                'shipping_name' => 'Test Customer',
                'shipping_phone' => '081234567890',
                'shipping_address' => 'Jl. Test No. 1',
                'shipping_city' => 'Bandung',
                'shipping_province' => 'Jawa Barat',
                'shipping_postal_code' => '40132',
            ]);

        // Empty cart should return error
        $response->assertStatus(422);
    }
}
