<?php

namespace App\Repositories;

use App\Models\Cart;

/**
 * Cart Repository
 *
 * Handles all database operations for Cart model.
 */
class CartRepository extends BaseRepository
{
    /**
     * Relations to eager load by default
     */
    protected array $defaultRelations = [
        'items.variant.product',
        'items.variant.selectedOptions',
    ];

    /**
     * Constructor
     */
    public function __construct(Cart $cart)
    {
        parent::__construct($cart);
    }

    /**
     * Find cart by user ID
     *
     * @param  int  $userId  User ID
     */
    public function findByUserId(int $userId): ?Cart
    {
        return $this->model
            ->with($this->defaultRelations)
            ->where('user_id', $userId)
            ->first();
    }

    /**
     * Find cart by session ID
     *
     * @param  string  $sessionId  Session ID
     */
    public function findBySessionId(string $sessionId): ?Cart
    {
        return $this->model
            ->with($this->defaultRelations)
            ->where('session_id', $sessionId)
            ->first();
    }

    /**
     * Find or create cart by user ID
     *
     * @param  int  $userId  User ID
     */
    public function findOrCreateByUserId(int $userId): Cart
    {
        $cart = $this->findByUserId($userId);

        if (! $cart) {
            $cart = $this->create(['user_id' => $userId]);
        }

        return $cart;
    }

    /**
     * Find or create cart by session ID
     *
     * @param  string  $sessionId  Session ID
     */
    public function findOrCreateBySessionId(string $sessionId): Cart
    {
        $cart = $this->findBySessionId($sessionId);

        if (! $cart) {
            $cart = $this->create(['session_id' => $sessionId]);
        }

        return $cart;
    }

    /**
     * Merge guest cart to user cart
     *
     * @param  string  $guestSessionId  Guest session ID
     * @param  int  $userId  User ID
     */
    public function mergeGuestCart(string $guestSessionId, int $userId): ?Cart
    {
        $guestCart = $this->findBySessionId($guestSessionId);
        $userCart = $this->findOrCreateByUserId($userId);

        if ($guestCart && $guestCart->items->count() > 0) {
            foreach ($guestCart->items as $item) {
                $existingItem = $userCart->items
                    ->where('variant_id', $item->variant_id)
                    ->first();

                if ($existingItem) {
                    $existingItem->quantity += $item->quantity;
                    $existingItem->save();
                } else {
                    $userCart->items()->create([
                        'variant_id' => $item->variant_id,
                        'quantity' => $item->quantity,
                    ]);
                }
            }

            // Delete guest cart after merging
            $guestCart->delete();
        }

        // Reload with relations
        return $this->findByUserId($userId);
    }

    /**
     * Clear cart by user ID
     *
     * @param  int  $userId  User ID
     */
    public function clearByUserId(int $userId): bool
    {
        $cart = $this->findByUserId($userId);

        if ($cart) {
            $cart->items()->delete();

            return true;
        }

        return false;
    }

    /**
     * Clear cart by session ID
     *
     * @param  string  $sessionId  Session ID
     */
    public function clearBySessionId(string $sessionId): bool
    {
        $cart = $this->findBySessionId($sessionId);

        if ($cart) {
            $cart->items()->delete();

            return true;
        }

        return false;
    }

    /**
     * Get cart total value
     *
     * @param  int  $cartId  Cart ID
     */
    public function getTotal(int $cartId): float
    {
        $cart = $this->find($cartId, $this->defaultRelations);

        if (! $cart) {
            return 0.0;
        }

        return $cart->items->sum(function ($item) {
            return $item->quantity * ($item->variant->price ?? 0);
        });
    }

    /**
     * Get cart items count
     *
     * @param  int  $cartId  Cart ID
     */
    public function getItemsCount(int $cartId): int
    {
        $cart = $this->find($cartId);

        if (! $cart) {
            return 0;
        }

        return $cart->items->sum('quantity');
    }
}
