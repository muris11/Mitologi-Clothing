<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;

class CartService
{
    public function getOrCreateCart(?int $userId, ?string $sessionId): Cart
    {
        \Illuminate\Support\Facades\Log::info("getOrCreateCart Called", ['userId' => $userId, 'sessionId' => $sessionId]);
        
        // 1. Check for Guest Cart FIRST to handle merging
        if ($sessionId) {
            $guestCart = Cart::where('session_id', $sessionId)->first();
            
            // FAILSAFE: If not found by session_id, and sessionId is numeric (Cart ID), try finding by ID
            // This handles cases where frontend stores Cart ID as the session key
            if (!$guestCart && is_numeric($sessionId)) {
                $guestCart = Cart::where('id', $sessionId)->whereNull('user_id')->first();
            }

            if ($guestCart) {
                // If we also have a User ID (Logged In)
                if ($userId) {
                    // Valid Guest Cart that needs claiming/merging
                    if (!$guestCart->user_id) {
                         // Check if user has ANOTHER cart
                         $existingUserCart = Cart::where('user_id', $userId)
                            ->where('id', '!=', $guestCart->id)
                            ->first();
                         
                         if ($existingUserCart) {
                             // Merge Guest -> Existing User Cart
                             $this->mergeGuestCart($guestCart, $userId); 
                             return $existingUserCart;
                         } else {
                             // No other cart, claim this one
                             $guestCart->user_id = $userId;
                             $guestCart->save();
                             return $guestCart;
                         }
                    }
                    
                    // If cart belongs to THIS user, return it
                    if ($guestCart->user_id === $userId) {
                        return $guestCart;
                    }
                    // If cart belongs to someone else, ignore it and fall through
                } else {
                    // No User ID, return Guest Cart
                    return $guestCart;
                }
            }
        }

        // 2. If no valid Guest Cart interactions, look for existing User Cart
        if ($userId) {
            $cart = Cart::where('user_id', $userId)->first();
            if ($cart) {
                // Backfill missing session_id so frontend can track cart via UUID cookie
                if (!$cart->session_id) {
                    $cart->session_id = \Illuminate\Support\Str::uuid()->toString();
                    $cart->save();
                }
                return $cart;
            }
        }

        // 3. Create New — always assign a UUID session_id for stable frontend tracking
        return Cart::create([
            'user_id' => $userId,
            'session_id' => $sessionId ?? \Illuminate\Support\Str::uuid()->toString(),
        ]);
    }

    private function mergeGuestCart(Cart $guestCart, int $userId): void
    {
        // Reload guest cart items to ensure fresh data
        $guestCart->load('items');

        // If user has another cart, merge items
        $userCart = Cart::where('user_id', $userId)
            ->where('id', '!=', $guestCart->id)
            ->first();

        if ($userCart) {
            foreach ($guestCart->items as $item) {
                // Skip if item no longer exists (might have been deleted)
                if (!$item->exists) {
                    continue;
                }

                $existing = $userCart->items()->where('variant_id', $item->variant_id)->first();
                if ($existing) {
                    $existing->update(['quantity' => $existing->quantity + $item->quantity]);
                    // Delete the guest cart item after merging
                    $item->delete();
                } else {
                    $item->update(['cart_id' => $userCart->id]);
                }
            }
            // Only delete guest cart if it still exists
            if ($guestCart->exists) {
                $guestCart->delete();
            }
        }
    }

    /**
     * Format variant options safely
     */
    private function formatSelectedOptions($options): array
    {
        if (!is_array($options)) {
            return [];
        }

        $result = [];
        foreach ($options as $key => $value) {
            // Format 1: {"name": "Size", "value": "M"}
            if (is_array($value) && isset($value['name'])) {
                $result[] = [
                    'name' => $value['name'],
                    'value' => $value['value'] ?? '',
                ];
            }
            // Format 2: {"Size": "M", "Color": "Red"}
            elseif (is_string($key) && is_string($value)) {
                $result[] = [
                    'name' => $key,
                    'value' => $value,
                ];
            }
        }

        return $result;
    }

    public function formatCartResponse(Cart $cart): array
    {
        $cart->load(['items.variant.product', 'items.variant.selectedOptions']);

        $lines = $cart->items->map(function (CartItem $item) {
            $variant = $item->variant;
            $product = $variant ? $variant->product : null;
            
            
            if (!$variant || !$product) {
                return null;
            }

            return [
                'id' => (string) $item->id,
                'quantity' => $item->quantity,
                'cost' => [
                    'totalAmount' => [
                        'amount' => (string) ($variant->price * $item->quantity),
                        'currencyCode' => $variant->currency_code,
                    ],
                ],
                'merchandise' => [
                    'id' => (string) $variant->id,
                    'title' => $variant->title,
                    'selectedOptions' => $this->formatSelectedOptions($variant->options),
                    'product' => [
                        'id' => (string) $product->id,
                        'handle' => $product->handle,
                        'title' => $product->title,
                        'featuredImage' => [
                            'url' => $product->featured_image_url ?? '',
                            'altText' => $product->title,
                            'width' => 800,
                            'height' => 800,
                        ],
                    ],
                ],
            ];
        })->filter()->values()->toArray();


        return [
            'id' => (string) $cart->id,
            'sessionId' => $cart->session_id,
            'checkoutUrl' => '/checkout',
            'cost' => $cart->cost,
            'lines' => $lines,
            'totalQuantity' => $cart->total_quantity,
        ];
    }
}
