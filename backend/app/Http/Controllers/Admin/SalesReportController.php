<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SalesReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::query()->with(['user', 'items']);
        $statsQuery = Order::query();

        // Apply Filters
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();

            $query->whereBetween('created_at', [$startDate, $endDate]);
            $statsQuery->whereBetween('created_at', [$startDate, $endDate]);
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
            $statsQuery->where('status', $request->status);
        }

        // Get paginated results
        $orders = $query->latest()->paginate(20)->withQueryString();

        // Calculate Stats
        // Count any order that is effectively paid or confirmed
        $paidStatuses = ['paid', 'processing', 'shipped', 'completed'];

        $totalRevenue = $statsQuery->clone()
            ->whereIn('status', $paidStatuses)
            ->sum('total');

        $totalOrders = $statsQuery->clone()->count();

        $paidOrdersCount = $statsQuery->clone()
            ->whereIn('status', $paidStatuses)
            ->count();

        $avgOrderValue = $paidOrdersCount > 0 ? $totalRevenue / $paidOrdersCount : 0;

        return view('admin.sales-report.index', compact('orders', 'totalRevenue', 'totalOrders', 'avgOrderValue'));
    }

    public function export(Request $request)
    {
        $query = Order::query()->with(['user', 'items']);

        // Apply Filters (Same as index)
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $filename = 'sales-report-'.date('Y-m-d-His').'.csv';

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');

            // Add BOM for Excel compatibility
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // Headers
            fputcsv($handle, [
                'No',
                'Order Number',
                'Date',
                'Customer',
                'Items Count',
                'Subtotal',
                'Shipping',
                'Tax',
                'Total',
                'Status',
                'Payment Method',
            ]);

            $counter = 1;
            $query->latest()->chunk(100, function ($orders) use ($handle, &$counter) {
                foreach ($orders as $order) {
                    fputcsv($handle, [
                        $counter++,
                        $order->order_number,
                        $order->created_at->format('Y-m-d H:i'),
                        $order->user ? $order->user->name : ($order->shippingAddress->name ?? 'Guest'),
                        $order->items->count(),
                        $order->subtotal,
                        $order->shipping_cost,
                        $order->tax,
                        $order->total,
                        ucfirst($order->status),
                        ucfirst($order->payment_method ?? '-'),
                    ]);
                }
            });

            fclose($handle);
        }, $filename);
    }
}
