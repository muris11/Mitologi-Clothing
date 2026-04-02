<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Cart;
use App\Models\Order;
use App\Models\User;
use App\Models\UserInteraction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutService
{
    private MidtransService $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    /**
     * @throws \Exception
     */
    public function processCheckout(User $user, array $shippingData): array
    {
        // Get user's cart
        $cart = Cart::where('user_id', $user->id)->first();

        if (! $cart || $cart->items->isEmpty()) {
            throw new \Exception('Keranjang belanja kosong');
        }

        $cart->load(['items.variant.product']);

        // Wrap in transaction
        return DB::transaction(function () use ($user, $shippingData, $cart) {
            $subtotal = 0;
            $orderItems = [];

            foreach ($cart->items as $item) {
                // Lock Variant for update to prevent race conditions during checkout
                $variant = \App\Models\ProductVariant::where('id', $item->variant_id)->lockForUpdate()->first();
                $product = $variant->product;

                // Stock Validation (Available stock mechanism)
                $availableStock = $variant->stock - $variant->reserved_stock;
                if ($availableStock < $item->quantity) {
                    throw new \Exception("Stok produk '{$product->title} - {$variant->title}' tidak mencukupi (Tersedia: {$availableStock}).");
                }

                $lineTotal = $variant->price * $item->quantity;
                $subtotal += $lineTotal;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'variant_id' => $variant->id,
                    'product_title' => $product->title,
                    'variant_title' => $variant->title,
                    'price' => $variant->price,
                    'quantity' => $item->quantity,
                    'total' => $lineTotal,
                ];

                // Increment Reserved Stock
                $variant->increment('reserved_stock', $item->quantity);

                // Track purchase interaction for AI
                UserInteraction::create([
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'type' => 'purchase',
                    'score' => 5,
                ]);
            }

            $total = $subtotal;

            // Create order
            $orderNumber = Order::generateOrderNumber();
            $midtransOrderId = 'MITOLOGI-'.uniqid().'-'.$user->id;

            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => $orderNumber,
                'status' => 'pending',
                'subtotal' => $subtotal,
                'tax' => 0,
                'shipping_cost' => 0,
                'total' => $total,
                'currency_code' => 'IDR',
                'midtrans_order_id' => $midtransOrderId,
                'notes' => $shippingData['notes'] ?? null,
            ]);

            // Create order items
            foreach ($orderItems as $item) {
                $order->items()->create($item);
            }

            // Create shipping address
            $order->shippingAddress()->create([
                'name' => $shippingData['shippingName'],
                'phone' => $shippingData['shippingPhone'],
                'address' => $shippingData['shippingAddress'],
                'city' => $shippingData['shippingCity'],
                'province' => $shippingData['shippingProvince'],
                'postal_code' => $shippingData['shippingPostalCode'],
            ]);

            $order->load(['items', 'shippingAddress', 'user']);

            // Midtrans Handling
            if (empty(config('midtrans.server_key'))) {
                Log::warning('Midtrans server key is not configured (MIDTRANS_SERVER_KEY).');

                if (app()->environment('local') || config('app.debug')) {
                    $order->update([
                        'status' => 'processing',
                        'paid_at' => now(),
                        'midtrans_payment_type' => 'mock',
                    ]);

                    $cart->items()->delete();

                    return [
                        'orderId' => $order->id,
                        'orderNumber' => $order->order_number,
                        'snapToken' => 'MOCK_SNAP_TOKEN',
                        'mock' => true,
                        'total' => $order->total,
                        'redirectUrl' => '/shop/account/orders/'.$order->order_number,
                    ];
                }
                throw new \Exception('Gateway pembayaran belum dikonfigurasi.');
            }

            $snapToken = $this->midtransService->createSnapToken($order);

            $cart->items()->delete();

            return [
                'orderId' => $order->id,
                'orderNumber' => $order->order_number,
                'snapToken' => $snapToken,
                'total' => $order->total,
                'redirectUrl' => '/shop/account/orders/'.$order->order_number,
            ];
        });
    }

    /**
     * @throws \Exception
     */
    public function processRepayment(User $user, string $orderNumber): array
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', $user->id)
            ->with(['items', 'shippingAddress', 'user'])
            ->firstOrFail();

        if ($order->status !== 'pending') {
            throw new \Exception('Hanya pesanan berstatus pending yang dapat dibayar ulang');
        }

        if (empty(config('midtrans.server_key'))) {
            $order->update([
                'status' => 'processing',
                'paid_at' => now(),
                'midtrans_payment_type' => 'mock',
            ]);

            return [
                'orderId' => $order->id,
                'orderNumber' => $order->order_number,
                'snapToken' => 'MOCK_SNAP_TOKEN',
                'mock' => true,
                'total' => $order->total,
                'redirectUrl' => '/shop/account/orders/'.$order->order_number,
            ];
        }

        // Generate a new Midtrans order ID to prevent "Transaction already processed" error in Midtrans
        $newMidtransOrderId = 'MITOLOGI-'.uniqid().'-'.$user->id;
        $order->update(['midtrans_order_id' => $newMidtransOrderId]);

        $snapToken = $this->midtransService->createSnapToken($order);

        return [
            'orderId' => $order->id,
            'orderNumber' => $order->order_number,
            'snapToken' => $snapToken,
            'total' => $order->total,
            'redirectUrl' => '/shop/account/orders/'.$order->order_number,
        ];
    }
}
