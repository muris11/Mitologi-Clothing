<?php

namespace Tests\Feature\Api;

use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    private Product $product;
    private ProductVariant $variant;
    private string $sessionId = 'test-session-abc123';

    protected function setUp(): void
    {
        parent::setUp();

        $this->product = Product::create([
            'title' => 'Test Product',
            'handle' => 'test-product',
            'description' => 'A test product',
            'is_hidden' => false,
        ]);

        $this->variant = $this->product->variants()->create([
            'title' => 'Default Title',
            'price' => 150000,
            'stock' => 10,
            'sku' => 'TST-001',
        ]);
    }

    public function test_can_get_empty_cart(): void
    {
        $response = $this->getJson('/api/cart');

        $response->assertOk();
    }

    public function test_can_add_item_to_cart(): void
    {
        Cart::create(['session_id' => $this->sessionId]);

        $response = $this->postJson('/api/cart/items', [
            'merchandiseId' => $this->variant->id,
            'quantity' => 2,
        ], ['X-Cart-Id' => $this->sessionId]);

        $response->assertOk()
            ->assertJsonPath('totalQuantity', 2);

        $this->assertDatabaseHas('cart_items', [
            'variant_id' => $this->variant->id,
            'quantity' => 2,
        ]);
    }

    public function test_add_item_increases_quantity_if_exists(): void
    {
        $cart = Cart::create(['session_id' => $this->sessionId]);
        $cart->items()->create([
            'variant_id' => $this->variant->id,
            'quantity' => 1,
        ]);

        $response = $this->postJson('/api/cart/items', [
            'merchandiseId' => $this->variant->id,
            'quantity' => 3,
        ], ['X-Cart-Id' => $this->sessionId]);

        $response->assertOk()
            ->assertJsonPath('totalQuantity', 4);

        $this->assertDatabaseHas('cart_items', [
            'variant_id' => $this->variant->id,
            'quantity' => 4,
        ]);
    }

    public function test_can_update_cart_item_quantity(): void
    {
        $cart = Cart::create(['session_id' => $this->sessionId]);
        $item = $cart->items()->create([
            'variant_id' => $this->variant->id,
            'quantity' => 2,
        ]);

        $response = $this->putJson("/api/cart/items/{$item->id}", [
            'quantity' => 5,
        ], ['X-Cart-Id' => $this->sessionId]);

        $response->assertOk()
            ->assertJsonPath('totalQuantity', 5);
    }

    public function test_setting_quantity_zero_removes_item(): void
    {
        $cart = Cart::create(['session_id' => $this->sessionId]);
        $item = $cart->items()->create([
            'variant_id' => $this->variant->id,
            'quantity' => 2,
        ]);

        $response = $this->putJson("/api/cart/items/{$item->id}", [
            'quantity' => 0,
        ], ['X-Cart-Id' => $this->sessionId]);

        $response->assertOk()
            ->assertJsonPath('totalQuantity', 0);

        $this->assertDatabaseMissing('cart_items', [
            'id' => $item->id,
        ]);
    }

    public function test_can_remove_cart_item(): void
    {
        $cart = Cart::create(['session_id' => $this->sessionId]);
        $item = $cart->items()->create([
            'variant_id' => $this->variant->id,
            'quantity' => 1,
        ]);

        $response = $this->deleteJson("/api/cart/items/{$item->id}", [], [
            'X-Cart-Id' => $this->sessionId,
        ]);

        $response->assertOk()
            ->assertJsonPath('totalQuantity', 0);
    }

    public function test_add_item_validation_rejects_invalid_data(): void
    {
        Cart::create(['session_id' => $this->sessionId]);

        $response = $this->postJson('/api/cart/items', [
            'merchandiseId' => '',
            'quantity' => -1,
        ], ['X-Cart-Id' => $this->sessionId]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['merchandiseId', 'quantity']);
    }

    public function test_authenticated_user_cart_persists(): void
    {
        $user = User::factory()->create(['role' => 'customer']);
        $cart = Cart::create(['user_id' => $user->id]);
        $cart->items()->create([
            'variant_id' => $this->variant->id,
            'quantity' => 3,
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/cart');

        $response->assertOk()
            ->assertJsonPath('totalQuantity', 3);
    }
}
