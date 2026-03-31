<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Http\Requests\Admin\OrderUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"));
            });
        }

        $orders = $query->paginate(10)->withQueryString();
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product', 'shippingAddress']);
        return view('admin.orders.show', compact('order'));
    }

    public function update(OrderUpdateRequest $request, Order $order)
    {
        $previousStatus = $order->status;

        $order->update([
            'status' => $request->status,
            'tracking_number' => $request->tracking_number,
        ]);

        return redirect()->route('admin.orders.show', $order)->with('success', 'Status pesanan berhasil diperbarui.');
    }

    public function approveRefund(Request $request, Order $order)
    {
        if (is_null($order->refund_requested_at) || $order->status !== 'processing') {
            return redirect()->back()->with('error', 'Pesanan ini tidak memiliki pengajuan refund yang valid.');
        }

        $request->validate([
            'refund_admin_note' => 'nullable|string|max:255',
        ]);

        $order->update([
            'status' => 'refunded',
            'refund_admin_note' => $request->input('refund_admin_note'),
        ]);

        Log::info("Admin approved refund for order #{$order->order_number}.");
        return redirect()->route('admin.orders.show', $order)->with('success', 'Refund berhasil disetujui.');
    }

    public function rejectRefund(Request $request, Order $order)
    {
        if (is_null($order->refund_requested_at) || $order->status !== 'processing') {
            return redirect()->back()->with('error', 'Pesanan ini tidak memiliki pengajuan refund yang valid.');
        }

        $request->validate([
            'refund_admin_note' => 'nullable|string|max:255',
        ]);

        $order->update([
            'refund_requested_at' => null,
            'refund_reason' => null,
            'refund_admin_note' => $request->input('refund_admin_note'),
        ]);

        Log::info("Admin rejected refund for order #{$order->order_number}.");
        return redirect()->route('admin.orders.show', $order)->with('success', 'Pengajuan refund ditolak, pesanan kembali diproses.');
    }
}
