<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;

class TestController extends Controller
{
    public function fixOrders()
    {
        // Update all orders to be 'completed' and paid
        Order::query()->update([
            'status' => 'processing',
            'paid_at' => now(),
            'payment_method' => 'manual_test',
        ]);

        return response()->json([
            'message' => 'All orders updated to processing and paid.',
            'count' => Order::count(),
        ]);
    }
}
