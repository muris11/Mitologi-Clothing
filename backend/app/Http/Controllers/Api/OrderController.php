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

        $formatted = collect($orders->items())->map(fn ($order) => [
            'id' => $order->id,
            'orderNumber' => $order->order_number,
            'status' => $order->status,
            'statusLabel' => $order->status_label,
            'total' => (float) $order->total,
            'currencyCode' => $order->currency_code,
            'itemsCount' => $order->items->count(),
            'paymentMethod' => $order->payment_method,
            'createdAt' => $order->created_at?->toISOString(),
            'paidAt' => $order->paid_at?->toISOString(),
        ]);

        return $this->successResponse([
            'orders' => $formatted,
            'pagination' => [
                'total' => $orders->total(),
                'perPage' => $orders->perPage(),
                'currentPage' => $orders->currentPage(),
                'lastPage' => $orders->lastPage(),
            ],
        ]);
    }

    public function show(Request $request, string $orderNumber): JsonResponse
    {
        $order = $request->user()->orders()
            ->with(['items.product', 'items.variant', 'shippingAddress'])
            ->where('order_number', $orderNumber)
            ->first();

        if (! $order) {
            return $this->notFoundResponse('Order');
        }

        $data = [
            'id' => $order->id,
            'orderNumber' => $order->order_number,
            'status' => $order->status,
            'statusLabel' => $order->status_label,
            'subtotal' => (float) $order->subtotal,
            'shippingCost' => (float) $order->shipping_cost,
            'total' => (float) $order->total,
            'paymentMethod' => $order->payment_method,
            'notes' => $order->notes,
            'createdAt' => $order->created_at->toISOString(),
            'paidAt' => $order->paid_at?->toISOString(),
            'items' => $order->items->map(fn ($item) => [
                'id' => $item->id,
                'productTitle' => $item->product_title,
                'variantTitle' => $item->variant_title,
                'price' => (float) $item->price,
                'quantity' => $item->quantity,
                'total' => (float) $item->total,
                'productHandle' => $item->product?->handle ?? null,
                'productImage' => $item->product?->featured_image_url ?? null,
            ]),
            'shippingAddress' => $order->shippingAddress ? [
                'name' => $order->shippingAddress->name,
                'phone' => $order->shippingAddress->phone,
                'address' => $order->shippingAddress->address,
                'city' => $order->shippingAddress->city,
                'province' => $order->shippingAddress->province,
                'postalCode' => $order->shippingAddress->postal_code,
            ] : null,
            'refundRequestedAt' => $order->refund_requested_at?->toISOString(),
            'refundReason' => $order->refund_reason,
        ];

        return $this->successResponse($data);
    }

    public function confirmPayment(Request $request, string $orderNumber, OrderService $orderService): JsonResponse
    {
        try {
            $result = $orderService->syncPaymentStatus($request->user(), $orderNumber);

            if (! $result['success']) {
                return $this->errorResponse(
                    $result['message'] ?? 'Gagal mengkonfirmasi pembayaran',
                    'confirm_payment_failed',
                    422
                );
            }

            return $this->successResponse(
                ['order' => $result['order'] ?? null],
                'Pembayaran berhasil dikonfirmasi'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Terjadi kesalahan saat mengkonfirmasi pembayaran',
                'confirm_payment_error',
                500
            );
        }
    }

    public function requestRefund(Request $request, string $orderNumber, OrderService $orderService): JsonResponse
    {
        try {
            $result = $orderService->requestRefund(
                $request->user(),
                $orderNumber,
                $request->input('reason')
            );

            if (! $result['success']) {
                return $this->errorResponse(
                    $result['message'] ?? 'Gagal mengajukan refund',
                    'refund_request_failed',
                    422
                );
            }

            return $this->successResponse(
                null,
                $result['message'] ?? 'Pengajuan refund berhasil'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Terjadi kesalahan saat mengajukan refund',
                'refund_request_error',
                500
            );
        }
    }
}
