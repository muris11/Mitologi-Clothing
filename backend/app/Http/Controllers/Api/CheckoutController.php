<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckoutRequest;
use App\Services\CheckoutService;
use App\Services\MidtransService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function process(CheckoutRequest $request, CheckoutService $checkoutService): JsonResponse
    {
        $validated = $request->validated();

        try {
            $result = $checkoutService->processCheckout($request->user(), $validated);

            return $this->successResponse($result);
        } catch (\Exception $e) {
            if ($e->getMessage() === 'Keranjang belanja kosong') {
                return $this->validationErrorResponse('Keranjang belanja kosong');
            }

            return $this->errorResponse($e->getMessage(), 'checkout_error', 400);
        }
    }

    public function pay(Request $request, string $orderNumber, CheckoutService $checkoutService): JsonResponse
    {
        try {
            $result = $checkoutService->processRepayment($request->user(), $orderNumber);

            return $this->successResponse($result);
        } catch (\Exception $e) {
            if ($e->getMessage() === 'Hanya pesanan berstatus pending yang dapat dibayar ulang') {
                return $this->validationErrorResponse('Hanya pesanan berstatus pending yang dapat dibayar ulang');
            }

            return $this->errorResponse($e->getMessage(), 'repayment_error', 500);
        }
    }

    public function notification(MidtransService $midtransService): JsonResponse
    {
        try {
            $result = $midtransService->handleNotification();

            return $this->successResponse(['orderStatus' => $result['status']]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 'notification_error', 500);
        }
    }
}
