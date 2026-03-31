<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistics
        $totalProducts = \App\Models\Product::count();
        $revenue = \App\Models\Order::whereNotIn('status', ['cancelled', 'refunded'])
            ->whereNotNull('paid_at')
            ->sum('total');
        $newCustomers = \App\Models\User::where('role', 'customer')
            ->where('created_at', '>=', now()->subDays(30))
            ->count();
        $newOrders = \App\Models\Order::where('status', 'pending')->count();

        // Recent Orders
        $recentOrders = \App\Models\Order::with('user')
            ->latest()
            ->take(5)
            ->get();

        // Sales Chart (Last 30 days) - Using manual grouping if Trend package issues persist, 
        // but let's try to use the installed flowframe/laravel-trend
        try {
            $salesChart = \Flowframe\Trend\Trend::model(\App\Models\Order::class)
                ->between(
                    start: now()->subDays(29),
                    end: now(),
                )
                ->perDay()
                ->sum('total');
        } catch (\Exception $e) {
            // Fallback if package not configured or issues
            $salesChart = collect([]);
        }

        // Top Selling Products
        $topProducts = \App\Models\OrderItem::select('product_id', 
                \Illuminate\Support\Facades\DB::raw('sum(quantity) as total_sold'),
                \Illuminate\Support\Facades\DB::raw('sum(total) as total_revenue')
            )
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->take(5)
            ->with('product')
            ->get();

        // Recent Testimonials
        $recentTestimonials = \App\Models\Testimonial::latest()->take(3)->get();

        // Low Stock Alerts
        $lowStockProducts = \App\Models\ProductVariant::where('stock', '<=', 5)
            ->with('product')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalProducts',
            'revenue',
            'newCustomers',
            'newOrders',
            'recentOrders',
            'salesChart',
            'topProducts',
            'recentTestimonials',
            'lowStockProducts'
        ));
    }

    public function getNotifications()
    {
        $notifications = collect();

        // Recent orders (last 7 days)
        $recentOrders = \App\Models\Order::where('created_at', '>=', now()->subDays(7))
            ->latest()
            ->take(10)
            ->get();

        foreach ($recentOrders as $order) {
            $notifications->push([
                'type' => 'order',
                'icon' => 'shopping-bag',
                'color' => 'blue',
                'title' => "Pesanan Baru #{$order->order_number}",
                'subtitle' => 'Rp ' . number_format($order->total, 0, ',', '.'),
                'time' => $order->created_at->diffForHumans(),
                'timestamp' => $order->created_at,
            ]);
        }

        // Low stock variants (stock <= 5)
        $lowStockVariants = \App\Models\ProductVariant::where('stock', '<=', 5)
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
        $outOfStock = \App\Models\ProductVariant::where('stock', '<=', 0)
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
        $newCustomers = \App\Models\User::where('created_at', '>=', now()->subDays(7))
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
        $refundRequests = \App\Models\Order::whereNotNull('refund_requested_at')
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
        $count = $notifications->count();

        $html = view('layouts.partials.notification-list', ['adminNotifications' => $notifications])->render();

        return response()->json([
            'count' => $count,
            'html' => $html
        ]);
    }
}
