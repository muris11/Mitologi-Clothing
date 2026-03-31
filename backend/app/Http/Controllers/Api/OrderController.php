<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
  public function index(Request $request): JsonResponse
  {
    $user = $request->user();
    $status = $request->input('status');

    $query = $user->orders()->with(['items', 'shippingAddress'])->latest();

    if ($status) {
      $query->where('status', $status);
    }

    $orders = $query->paginate(10);

    $formatted = collect($orders->items())->map(fn($order) => [
      'id' => $order->id,
      'order_number' => $order->order_number,
      'status' => $order->status,
      'status_label' => $order->status_label,
      'total' => (float) $order->total,
      'currency_code' => $order->currency_code,
      'items_count' => $order->items->count(),
      'payment_method' => $order->payment_method,
      'created_at' => $order->created_at?->toISOString(),
      'paid_at' => $order->paid_at?->toISOString(),
    ]);

    return response()->json([
      'orders' => $formatted,
      'pagination' => [
        'current_page' => $orders->currentPage(),
        'last_page' => $orders->lastPage(),
        'total' => $orders->total(),
      ],
    ]);
  }

  public function show(Request $request, string $orderNumber): JsonResponse
  {
    $order = $request->user()->orders()
      ->with(['items.product', 'items.variant', 'shippingAddress'])
      ->where('order_number', $orderNumber)
      ->firstOrFail();

    // Return snake_case payload matching frontend `Order` type
    return response()->json([
      'id' => $order->id,
      'order_number' => $order->order_number,
      'status' => $order->status,
      'status_label' => $order->status_label,
      'subtotal' => (float) $order->subtotal,
      'shipping_cost' => (float) $order->shipping_cost,
      'total' => (float) $order->total,
      'payment_method' => $order->payment_method,
      'notes' => $order->notes,
      'created_at' => $order->created_at->toISOString(),
      'paid_at' => $order->paid_at?->toISOString(),
      'items' => $order->items->map(fn($item) => [
        'id' => $item->id,
        'product_title' => $item->product_title,
        'variant_title' => $item->variant_title,
        'price' => (float) $item->price,
        'quantity' => $item->quantity,
        'total' => (float) $item->total,
        'product_handle' => $item->product?->handle ?? null,
        'product_image' => $item->product?->featured_image_url ?? null,
      ]),
      'shipping_address' => $order->shippingAddress ? [
        'id' => $order->shippingAddress->id,
        'recipient_name' => $order->shippingAddress->name,
        'phone' => $order->shippingAddress->phone,
        'address_line_1' => $order->shippingAddress->address,
        'address_line_2' => null,
        'city' => $order->shippingAddress->city,
        'province' => $order->shippingAddress->province,
        'postal_code' => $order->shippingAddress->postal_code,
        'country' => 'Indonesia',
        'is_primary' => true,
      ] : null,
      'refund_requested_at' => $order->refund_requested_at?->toISOString(),
      'refund_reason' => $order->refund_reason,
    ]);
  }

  public function confirmPayment(Request $request, string $orderNumber, OrderService $orderService): JsonResponse
  {
    try {
        $result = $orderService->syncPaymentStatus($request->user(), $orderNumber);
        return response()->json($result, 200);
    } catch (\Exception $e) {
        return response()->json(['message' => $e->getMessage()], 400);
    }
  }

  public function requestRefund(Request $request, string $orderNumber, OrderService $orderService): JsonResponse
  {
      $request->validate(['reason' => 'required|string|max:255']);

      try {
          $orderService->processRefundRequest($request->user(), $orderNumber, $request->reason);
          return response()->json(['message' => 'Pengajuan refund berhasil dikirim. Menunggu konfirmasi admin.']);
      } catch (\Exception $e) {
          return response()->json(['message' => $e->getMessage()], 400);
      }
  }
}

