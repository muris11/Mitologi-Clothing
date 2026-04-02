<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddCartItemRequest;
use App\Http\Requests\UpdateCartItemRequest;
use App\Models\UserInteraction;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CartController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function create(Request $request): JsonResponse
    {
        $userId = auth('sanctum')->id();
        $sessionId = $request->header('X-Session-Id', $request->cookie('cartSessionId'));

        if (! $sessionId) {
            $sessionId = Str::uuid()->toString();
        }

        $cart = $this->cartService->getOrCreateCart($userId, $sessionId);

        return $this->successResponse($this->cartService->formatCartResponse($cart));
    }

    public function show(Request $request): JsonResponse
    {
        $userId = auth('sanctum')->id();
        $sessionId = $request->header('X-Cart-Id', $request->cookie('cartSessionId'));

        if (! $sessionId) {
            $sessionId = $request->query('id');
        }

        if (! $userId && ! $sessionId) {
            return $this->successResponse(null);
        }

        if ($userId) {
            $cart = \App\Models\Cart::where('user_id', $userId)->first();
            if ($cart) {
                if (! $cart->session_id) {
                    $cart->session_id = Str::uuid()->toString();
                    $cart->save();
                }

                return $this->successResponse($this->cartService->formatCartResponse($cart));
            }
        }

        $cart = $this->cartService->getOrCreateCart($userId, $sessionId);

        return $this->successResponse($this->cartService->formatCartResponse($cart));
    }

    public function addItem(AddCartItemRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $variant = \App\Models\ProductVariant::find($validated['merchandiseId']);
        if (! $variant) {
            return $this->notFoundResponse('Varian produk');
        }

        $userId = auth('sanctum')->id();
        $sessionId = $request->header('X-Cart-Id', $request->cookie('cartSessionId'));

        $cart = $this->cartService->getOrCreateCart($userId, $sessionId);

        if ($userId && $cart->user_id && $cart->user_id !== $userId) {
            return $this->forbiddenResponse('Tidak diizinkan mengakses keranjang ini.');
        }

        $existingItem = $cart->items()->where('variant_id', $validated['merchandiseId'])->first();
        $currentQuantity = $existingItem ? $existingItem->quantity : 0;
        $newQuantity = $currentQuantity + $validated['quantity'];

        if ($newQuantity > $variant->available_stock) {
            return $this->validationErrorResponse(
                "Stok produk tidak mencukupi (Tersedia: {$variant->available_stock})."
            );
        }

        $cartItem = $cart->items()->updateOrCreate(
            ['variant_id' => $validated['merchandiseId']],
            ['quantity' => $newQuantity]
        );

        if ($userId && $variant) {
            UserInteraction::create([
                'user_id' => $userId,
                'product_id' => $variant->product_id,
                'type' => 'cart',
                'score' => 3,
            ]);
        }

        $cart->refresh();

        return $this->successResponse(
            $this->cartService->formatCartResponse($cart),
            'Item ditambahkan ke keranjang'
        );
    }

    public function updateItem(UpdateCartItemRequest $request, int $id): JsonResponse
    {
        $validated = $request->validated();

        $userId = auth('sanctum')->id();
        $sessionId = $request->header('X-Cart-Id', $request->cookie('cartSessionId'));
        $cart = $this->cartService->getOrCreateCart($userId, $sessionId);

        if ($userId && $cart->user_id && $cart->user_id !== $userId) {
            return $this->forbiddenResponse('Tidak diizinkan mengakses keranjang ini.');
        }

        $item = $cart->items()->find($id);

        if (! $item) {
            $cart->refresh();

            return $this->successResponse($this->cartService->formatCartResponse($cart));
        }

        if ($validated['quantity'] > 0) {
            $variant = \App\Models\ProductVariant::find($item->variant_id);
            if ($variant && $validated['quantity'] > $variant->available_stock) {
                return $this->validationErrorResponse(
                    "Stok maksimal yang bisa dibeli adalah {$variant->available_stock}."
                );
            }
        }

        if ($validated['quantity'] === 0) {
            $item->delete();
        } else {
            $item->update(['quantity' => $validated['quantity']]);
        }

        $cart->refresh();

        return $this->successResponse(
            $this->cartService->formatCartResponse($cart),
            'Keranjang diperbarui'
        );
    }

    public function removeItem(Request $request, int $id): JsonResponse
    {
        $userId = auth('sanctum')->id();
        $sessionId = $request->header('X-Cart-Id', $request->cookie('cartSessionId'));
        $cart = $this->cartService->getOrCreateCart($userId, $sessionId);

        if ($userId && $cart->user_id && $cart->user_id !== $userId) {
            return $this->forbiddenResponse('Tidak diizinkan mengakses keranjang ini.');
        }

        $cart->items()->where('id', $id)->delete();

        $cart->refresh();

        return $this->successResponse(
            $this->cartService->formatCartResponse($cart),
            'Item dihapus dari keranjang'
        );
    }
}
