<?php

namespace App\View\Composers;

use App\Models\Order;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\View\View;

class AdminNotificationComposer
{
    public function compose(View $view): void
    {
        $notifications = collect();

        // Recent orders (last 7 days - widened window)
        $recentOrders = Order::where('created_at', '>=', now()->subDays(7))
            ->latest()
            ->take(10)
            ->get();

        foreach ($recentOrders as $order) {
            $notifications->push([
                'type' => 'order',
                'icon' => 'shopping-bag',
                'color' => 'blue',
                'title' => "Pesanan Baru #{$order->order_number}",
                'subtitle' => 'Rp '.number_format($order->total, 0, ',', '.'),
                'time' => $order->created_at->diffForHumans(),
                'timestamp' => $order->created_at,
            ]);
        }

        // Low stock variants (stock <= 5)
        $lowStockVariants = ProductVariant::where('stock', '<=', 5)
            ->where('stock', '>', 0)
            ->orderBy('updated_at', 'desc')
            ->with('product')
            ->take(10)
            ->get();

        foreach ($lowStockVariants as $variant) {
            $productName = $variant->product->title ?? 'Produk';
            $variantName = $variant->title ? " ({$variant->title})" : '';
            $notifications->push([
                'type' => 'low_stock',
                'icon' => 'exclamation',
                'color' => 'yellow',
                'title' => "Stok Rendah: {$productName}{$variantName}",
                'subtitle' => "Sisa {$variant->stock} unit",
                'time' => $variant->updated_at->diffForHumans(),
                'timestamp' => $variant->updated_at,
            ]);
        }

        // Out of stock
        $outOfStock = ProductVariant::where('stock', '<=', 0)
            ->orderBy('updated_at', 'desc')
            ->with('product')
            ->take(5)
            ->get();

        foreach ($outOfStock as $variant) {
            $productName = $variant->product->title ?? 'Produk';
            $variantName = $variant->title ? " ({$variant->title})" : '';
            $notifications->push([
                'type' => 'out_of_stock',
                'icon' => 'x-circle',
                'color' => 'red',
                'title' => "Habis: {$productName}{$variantName}",
                'subtitle' => 'Stok 0 — perlu restock',
                'time' => $variant->updated_at->diffForHumans(),
                'timestamp' => $variant->updated_at,
            ]);
        }

        // New customers (last 7 days)
        $newCustomers = User::where('created_at', '>=', now()->subDays(7))
            ->latest()
            ->take(10)
            ->get();

        foreach ($newCustomers as $customer) {
            $notifications->push([
                'type' => 'customer',
                'icon' => 'user-plus',
                'color' => 'green',
                'title' => "Pelanggan Baru: {$customer->name}",
                'subtitle' => $customer->email,
                'time' => $customer->created_at->diffForHumans(),
                'timestamp' => $customer->created_at,
            ]);
        }

        // Refund requests (pending)
        $refundRequests = Order::whereNotNull('refund_requested_at')
            ->where('status', 'processing')
            ->latest('refund_requested_at')
            ->take(10)
            ->get();

        foreach ($refundRequests as $request) {
            $notifications->push([
                'type' => 'refund',
                'icon' => 'receipt-refund',
                'color' => 'amber',
                'title' => "Pengajuan Refund #{$request->order_number}",
                'subtitle' => str("Alasan: {$request->refund_reason}")->limit(40),
                'time' => $request->refund_requested_at->diffForHumans(),
                'timestamp' => $request->refund_requested_at,
            ]);
        }

        // Sort by most recent
        $notifications = $notifications->sortByDesc('timestamp')->take(10)->values();

        $view->with('adminNotifications', $notifications);
        $view->with('adminNotificationCount', $notifications->count());
    }
}
