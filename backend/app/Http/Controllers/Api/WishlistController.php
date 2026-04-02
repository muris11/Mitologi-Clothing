<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\UserInteraction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    use \App\Traits\FormatsProduct;

    /**
     * Get all wishlisted products for the authenticated user.
     */
    public function index(Request $request): JsonResponse
    {
        $userId = $request->user()->id;

        $wishlistItems = UserInteraction::where('user_id', $userId)
            ->where('type', 'wishlist')
            ->with(['product.variants', 'product.options', 'product.images', 'product.categories'])
            ->orderBy('created_at', 'desc')
            ->get();

        $products = $wishlistItems->map(function ($item) {
            $product = $item->product;
            if (! $product) {
                return null;
            }

            $formatted = $this->formatProduct($product);
            $formatted['isWishlisted'] = true;

            return $formatted;
        })->filter()->values();

        return $this->successResponse($products);
    }

    /**
     * Add a product to the wishlist.
     */
    public function store(Request $request, $productId): JsonResponse
    {
        $userId = $request->user()->id;

        $product = Product::find($productId);

        if (! $product) {
            return $this->notFoundResponse('Product');
        }

        UserInteraction::firstOrCreate(
            [
                'user_id' => $userId,
                'product_id' => $productId,
                'type' => 'wishlist',
            ],
            [
                'score' => 3,
            ]
        );

        return $this->successResponse(
            ['isWishlisted' => true],
            'Product added to wishlist'
        );
    }

    /**
     * Remove a product from the wishlist.
     */
    public function destroy(Request $request, $productId): JsonResponse
    {
        $userId = $request->user()->id;

        UserInteraction::where('user_id', $userId)
            ->where('product_id', $productId)
            ->where('type', 'wishlist')
            ->delete();

        return $this->successResponse(
            ['isWishlisted' => false],
            'Product removed from wishlist'
        );
    }

    /**
     * Check if a specific product is in the wishlist.
     */
    public function check(Request $request, $productId): JsonResponse
    {
        $userId = $request->user()->id;

        $exists = UserInteraction::where('user_id', $userId)
            ->where('product_id', $productId)
            ->where('type', 'wishlist')
            ->exists();

        return $this->successResponse(['isWishlisted' => $exists]);
    }
}
