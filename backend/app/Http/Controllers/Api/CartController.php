<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddCartItemRequest;
use App\Http\Requests\UpdateCartItemRequest;
use App\Models\UserInteraction;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
        
        if (!$sessionId) {
            $sessionId = \Illuminate\Support\Str::uuid()->toString();
        }

        $cart = $this->cartService->getOrCreateCart($userId, $sessionId);

        return response()->json($this->cartService->formatCartResponse($cart));
    }

    public function show(Request $request): JsonResponse
    {
        $userId = auth('sanctum')->id();
        $sessionId = $request->header('X-Cart-Id', $request->cookie('cartSessionId'));

        // Fallback: If no header/cookie, check query param 'id' (mitologi frontend convention)
        if (!$sessionId) {
            $sessionId = $request->query('id');
        }

        if (!$userId && !$sessionId) {
            return response()->json(null);
        }


        // If user is authenticated, always return their cart directly (ignore stale sessionId)
        if ($userId) {
            $cart = \App\Models\Cart::where('user_id', $userId)->first();
            if ($cart) {
                // Backfill session_id if missing
                if (!$cart->session_id) {
                    $cart->session_id = \Illuminate\Support\Str::uuid()->toString();
                    $cart->save();
                }
                return response()->json($this->cartService->formatCartResponse($cart));
            }
        }

        $cart = $this->cartService->getOrCreateCart($userId, $sessionId);

        return response()->json($this->cartService->formatCartResponse($cart));
    }

    public function addItem(AddCartItemRequest $request): JsonResponse
    {
        $validated = $request->validated();

        // VALIDATE STOCK BEFORE ADDING
        $variant = \App\Models\ProductVariant::find($validated['merchandiseId']);
        if (!$variant) {
            return response()->json(['error' => 'Varian produk tidak ditemukan.'], 404);
        }

        $userId = auth('sanctum')->id();
        $sessionId = $request->header('X-Cart-Id', $request->cookie('cartSessionId'));

        $cart = $this->cartService->getOrCreateCart($userId, $sessionId);

        // Ownership validation: ensure the cart belongs to this user/session
        if ($userId && $cart->user_id && $cart->user_id !== $userId) {
            return response()->json(['message' => 'Tidak diizinkan mengakses keranjang ini.'], 403);
        }

        $existingItem = $cart->items()->where('variant_id', $validated['merchandiseId'])->first();
        
        $currentQuantity = $existingItem ? $existingItem->quantity : 0;
        $newQuantity = $currentQuantity + $validated['quantity'];

        if ($newQuantity > $variant->available_stock) {
            return response()->json([
                'error' => "Stok produk tidak mencukupi (Tersedia: {$variant->available_stock})."
            ], 400);
        }

        if ($existingItem) {
            $existingItem->update([
                'quantity' => $newQuantity,
            ]);
        } else {
            $cart->items()->create([
                'variant_id' => $validated['merchandiseId'],
                'quantity' => $validated['quantity'],
            ]);
        }

        // Track cart interaction for AI
        if ($userId) {
            if ($variant) {
                UserInteraction::create([
                    'user_id' => $userId,
                    'product_id' => $variant->product_id,
                    'type' => 'cart',
                    'score' => 3,
                ]);
            }
        }

        $cart->refresh();
        return response()->json($this->cartService->formatCartResponse($cart));
    }

    public function updateItem(UpdateCartItemRequest $request, int $id): JsonResponse
    {
        $validated = $request->validated();

        $userId = auth('sanctum')->id();
        $sessionId = $request->header('X-Cart-Id', $request->cookie('cartSessionId'));
        $cart = $this->cartService->getOrCreateCart($userId, $sessionId);

        // Ownership validation
        if ($userId && $cart->user_id && $cart->user_id !== $userId) {
            return response()->json(['message' => 'Tidak diizinkan mengakses keranjang ini.'], 403);
        }

        // Use find instead of findOrFail to handle missing items gracefully
        $item = $cart->items()->find($id);

        // If item doesn't exist, just return the current cart
        if (!$item) {
            $cart->refresh();
            return response()->json($this->cartService->formatCartResponse($cart));
        }

        if ($validated['quantity'] > 0) {
            // VALIDATE STOCK BEFORE UPDATING
            $variant = \App\Models\ProductVariant::find($item->variant_id);
            if ($variant && $validated['quantity'] > $variant->available_stock) {
                return response()->json([
                    'error' => "Stok maksimal yang bisa dibeli adalah {$variant->available_stock}."
                ], 400);
            }
        }

        if ($validated['quantity'] === 0) {
            $item->delete();
        } else {
            $item->update(['quantity' => $validated['quantity']]);
        }

        $cart->refresh();
        return response()->json($this->cartService->formatCartResponse($cart));
    }

    public function removeItem(Request $request, int $id): JsonResponse
    {
        $userId = auth('sanctum')->id();
        $sessionId = $request->header('X-Cart-Id', $request->cookie('cartSessionId'));
        $cart = $this->cartService->getOrCreateCart($userId, $sessionId);

        // Ownership validation
        if ($userId && $cart->user_id && $cart->user_id !== $userId) {
            return response()->json(['message' => 'Tidak diizinkan mengakses keranjang ini.'], 403);
        }

        $cart->items()->where('id', $id)->delete();

        $cart->refresh();
        return response()->json($this->cartService->formatCartResponse($cart));
    }
}
