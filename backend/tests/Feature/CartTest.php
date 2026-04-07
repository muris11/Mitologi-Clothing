<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_clear_cart(): void
    {
        $user = User::factory()->create();
        $cart = Cart::factory()->create(['user_id' => $user->id]);
        
        // Create 3 cart items with different variants
        for ($i = 0; $i < 3; $i++) {
            $product = Product::factory()->create();
            $variant = ProductVariant::factory()->create(['product_id' => $product->id]);
            CartItem::factory()->create([
                'cart_id' => $cart->id,
                'variant_id' => $variant->id,
            ]);
        }

        $response = $this->actingAs($user)
            ->deleteJson('/api/v1/cart/clear');

        $response->assertStatus(200)
            ->assertJson(['message' => 'Cart cleared successfully']);

        $this->assertDatabaseMissing('cart_items', ['cart_id' => $cart->id]);
    }

    public function test_can_clear_cart_with_session_id(): void
    {
        $sessionId = Str::uuid()->toString();
        $cart = Cart::factory()->create([
            'user_id' => null,
            'session_id' => $sessionId,
        ]);
        
        // Create 2 cart items with different variants
        for ($i = 0; $i < 2; $i++) {
            $product = Product::factory()->create();
            $variant = ProductVariant::factory()->create(['product_id' => $product->id]);
            CartItem::factory()->create([
                'cart_id' => $cart->id,
                'variant_id' => $variant->id,
            ]);
        }

        $response = $this->withHeaders([
                'X-Cart-Id' => $sessionId,
            ])
            ->deleteJson('/api/v1/cart/clear');

        $response->assertStatus(200)
            ->assertJson(['message' => 'Cart cleared successfully']);

        $this->assertDatabaseMissing('cart_items', ['cart_id' => $cart->id]);
    }

    public function test_clear_empty_cart_returns_success(): void
    {
        $user = User::factory()->create();
        $cart = Cart::factory()->create(['user_id' => $user->id]);
        
        // No items in cart

        $response = $this->actingAs($user)
            ->deleteJson('/api/v1/cart/clear');

        $response->assertStatus(200)
            ->assertJson(['message' => 'Cart cleared successfully']);

        $this->assertDatabaseHas('carts', ['id' => $cart->id]);
    }
}
