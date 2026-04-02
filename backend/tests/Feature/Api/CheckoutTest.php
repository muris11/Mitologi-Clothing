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
            'shippingName' => 'John Doe',
            'shippingPhone' => '081234567890',
            'shippingAddress' => 'Jl. Test No. 1',
            'shippingCity' => 'Bandung',
            'shippingProvince' => 'Jawa Barat',
            'shippingPostalCode' => '40132',
        ]);

        $response->assertUnauthorized();
    }

    public function test_checkout_validates_shipping_data(): void
    {
        $response = $this->actingAs($this->customer, 'sanctum')
            ->postJson('/api/checkout', []);

        $response->assertUnprocessable()
            ->assertJsonPath('error.details.shippingName', fn ($errors) => is_array($errors) && count($errors) > 0)
            ->assertJsonPath('error.details.shippingPhone', fn ($errors) => is_array($errors) && count($errors) > 0)
            ->assertJsonPath('error.details.shippingAddress', fn ($errors) => is_array($errors) && count($errors) > 0)
            ->assertJsonPath('error.details.shippingCity', fn ($errors) => is_array($errors) && count($errors) > 0)
            ->assertJsonPath('error.details.shippingProvince', fn ($errors) => is_array($errors) && count($errors) > 0)
            ->assertJsonPath('error.details.shippingPostalCode', fn ($errors) => is_array($errors) && count($errors) > 0);
    }

    public function test_checkout_rejects_empty_cart(): void
    {
        $response = $this->actingAs($this->customer, 'sanctum')
            ->postJson('/api/checkout', [
                'shippingName' => 'Test Customer',
                'shippingPhone' => '081234567890',
                'shippingAddress' => 'Jl. Test No. 1',
                'shippingCity' => 'Bandung',
                'shippingProvince' => 'Jawa Barat',
                'shippingPostalCode' => '40132',
            ]);

        // Empty cart should return error
        $response->assertStatus(422);
    }
}
