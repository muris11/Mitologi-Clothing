<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutPaymentFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_stock_reservation_lifecycle()
    {
        $user = User::factory()->create();

        $product = Product::create(['title' => 'Test', 'description' => 'Test', 'handle' => 'test-auth', 'available_for_sale' => true]);
        $variant = ProductVariant::create(['product_id' => $product->id, 'title' => 'Red', 'price' => 10000, 'stock' => 10, 'reserved_stock' => 2, 'sku' => 'red']);

        // Create pending order (simulating checkout process)
        $order = Order::create([
            'user_id' => $user->id,
            'order_number' => 'ORD-001',
            'status' => 'pending',
            'total' => 10000,
            'subtotal' => 10000,
            'currency_code' => 'IDR',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'variant_id' => $variant->id,
            'quantity' => 1,
            'price' => 10000,
            'total' => 10000,
            'product_title' => 'Test',
            'variant_title' => 'Red',
        ]);

        // Simulating processing payment
        $order->status = 'processing';
        $order->save();

        $variant->refresh();

        // Stock and reserved_stock both are decremented when paid
        $this->assertEquals(9, $variant->stock);
        $this->assertEquals(1, $variant->reserved_stock);

        // Simulating cancelled after processing
        $order->status = 'cancelled';
        $order->save();

        $variant->refresh();

        // Actual stock is restored
        $this->assertEquals(10, $variant->stock);
        $this->assertEquals(1, $variant->reserved_stock);
    }
}
