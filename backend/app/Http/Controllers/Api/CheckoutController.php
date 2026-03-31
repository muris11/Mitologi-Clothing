<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckoutRequest;
use App\Services\CartService;
use App\Services\MidtransService;
use App\Services\CheckoutService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
  public function process(CheckoutRequest $request, CheckoutService $checkoutService): JsonResponse
  {
    $validated = $request->validated();

    try {
        $result = $checkoutService->processCheckout($request->user(), $validated);
        return response()->json($result, 200);
    } catch (\Exception $e) {
        $status = $e->getMessage() === 'Keranjang belanja kosong' ? 422 : 400;
        return response()->json(['error' => $e->getMessage()], $status);
    }
  }

  public function pay(Request $request, string $orderNumber, CheckoutService $checkoutService): JsonResponse
  {
      try {
          $result = $checkoutService->processRepayment($request->user(), $orderNumber);
          return response()->json($result, 200);
      } catch (\Exception $e) {
          $status = $e->getMessage() === 'Hanya pesanan berstatus pending yang dapat dibayar ulang' ? 422 : 500;
          return response()->json(['error' => $e->getMessage()], $status);
      }
  }

  public function notification(MidtransService $midtransService): JsonResponse
  {
    try {
      $result = $midtransService->handleNotification();
      return response()->json(['status' => 'ok', 'order_status' => $result['status']]);
    } catch (\Exception $e) {
      return response()->json(['error' => $e->getMessage()], 500);
    }
  }
}

