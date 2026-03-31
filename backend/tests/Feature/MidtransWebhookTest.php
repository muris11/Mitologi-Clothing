<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Order;
use App\Models\User;
use App\Models\WebhookEvent;
use Illuminate\Support\Facades\Config;

class MidtransWebhookTest extends TestCase
{
    use RefreshDatabase;

    public function test_invalid_signature_is_rejected()
    {
        Config::set('midtrans.server_key', 'dummy_server_key');
        \Midtrans\Config::$serverKey = 'dummy_server_key';

        $user = User::factory()->create();
        $order = Order::create([
            'user_id' => $user->id,
            'order_number' => 'ORD-TEST-1',
            'midtrans_order_id' => 'MIT-TEST-1',
            'status' => 'pending',
            'subtotal' => 10000,
            'total' => 10000,
            'currency_code' => 'IDR'
        ]);

        $payload = [
            'order_id' => 'MIT-TEST-1',
            'status_code' => '200',
            'gross_amount' => '10000.00',
            'signature_key' => 'invalid_signature',
            'transaction_status' => 'settlement',
            'payment_type' => 'credit_card',
            'fraud_status' => 'accept'
        ];

        $response = $this->postJson('/api/checkout/notification', $payload);

        $response->assertStatus(500);
        $this->assertEquals('pending', $order->fresh()->status);
    }
}
