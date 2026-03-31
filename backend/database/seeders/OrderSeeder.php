<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ShippingAddress;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $allProducts = Product::with('variants')->get();
        if ($allProducts->isEmpty()) {
            return;
        }

        $customers = User::where('role', 'customer')->get();
        if ($customers->isEmpty()) {
            return;
        }

        $ordersData = [
            [
                'customer' => $customers->firstWhere('email', 'customer@demo.com') ?? $customers->first(),
                'status' => 'completed',
                'items_count' => 2,
                'days_ago' => 25,
                'paid' => true,
            ],
            [
                'customer' => $customers->firstWhere('email', 'anisa@demo.com') ?? $customers->random(),
                'status' => 'completed',
                'items_count' => 1,
                'days_ago' => 20,
                'paid' => true,
            ],
            [
                'customer' => $customers->firstWhere('email', 'budi@demo.com') ?? $customers->random(),
                'status' => 'shipped',
                'items_count' => 3,
                'days_ago' => 10,
                'paid' => true,
            ],
            [
                'customer' => $customers->firstWhere('email', 'denny@demo.com') ?? $customers->random(),
                'status' => 'processing',
                'items_count' => 1,
                'days_ago' => 5,
                'paid' => true,
            ],
            [
                'customer' => $customers->firstWhere('email', 'siti@demo.com') ?? $customers->random(),
                'status' => 'pending',
                'items_count' => 2,
                'days_ago' => 2,
                'paid' => false,
            ],
            [
                'customer' => $customers->firstWhere('email', 'customer@demo.com') ?? $customers->random(),
                'status' => 'processing',
                'items_count' => 1,
                'days_ago' => 3,
                'paid' => true,
            ],
        ];

        foreach ($ordersData as $orderData) {
            $customer = $orderData['customer'];
            if (!$customer) continue;

            $subtotal = 0;
            $orderItems = [];

            $pickedProducts = $allProducts->random(min($orderData['items_count'], $allProducts->count()));
            foreach ($pickedProducts as $product) {
                if ($product->variants->isEmpty()) continue;
                $variant = $product->variants->random();
                
                $qty = rand(1, 3);
                $price = $variant->price;
                $total = $price * $qty;
                $subtotal += $total;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'variant_id' => $variant->id,
                    'product_title' => $product->title,
                    'variant_title' => $variant->title,
                    'price' => $price,
                    'quantity' => $qty,
                    'total' => $total,
                ];
            }

            if (empty($orderItems)) continue;

            $shippingCost = 15000;
            $tax = 0;
            $grandTotal = $subtotal + $shippingCost + $tax;

            $order = new Order();
            $order->user_id = $customer->id;
            $order->order_number = Order::generateOrderNumber();
            $order->status = $orderData['status'];
            $order->subtotal = $subtotal;
            $order->tax = $tax;
            $order->shipping_cost = $shippingCost;
            $order->total = $grandTotal;
            $order->currency_code = 'IDR';
            $order->payment_method = 'midtrans';
            $order->paid_at = $orderData['paid'] ? now()->subDays($orderData['days_ago']) : null;
            $order->created_at = now()->subDays($orderData['days_ago']);
            $order->updated_at = now()->subDays($orderData['days_ago']);
            $order->saveQuietly();

            foreach ($orderItems as $item) {
                OrderItem::create(array_merge($item, ['order_id' => $order->id]));
            }

            ShippingAddress::create([
                'order_id' => $order->id,
                'name' => $customer->name,
                'phone' => $customer->phone,
                'address' => $customer->address,
                'city' => $customer->city,
                'province' => $customer->province,
                'postal_code' => $customer->postal_code,
            ]);
        }
    }
}
