<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserInteraction;
use App\Models\Product; // Ensure Product model is imported
use Illuminate\Support\Facades\DB;

class WishlistController extends Controller
{
    use \App\Traits\FormatsProduct;

    /**
     * Get all wishlisted products for the authenticated user.
     */
    public function index(Request $request)
    {
        $userId = $request->user()->id;

        $wishlistItems = UserInteraction::where('user_id', $userId)
            ->where('type', 'wishlist')
            ->with(['product.variants', 'product.options', 'product.images', 'product.categories']) // Eager load all necessary relations
            ->orderBy('created_at', 'desc')
            ->get();

        // Transform the data to return a clean product list
        $products = $wishlistItems->map(function ($item) {
             $product = $item->product;
             if (!$product) return null;
             
             $formatted = $this->formatProduct($product);
             $formatted['is_wishlisted'] = true;
             return $formatted;
        })->filter()->values();

        return response()->json($products);
    }

    /**
     * Add a product to the wishlist.
     */
    public function store(Request $request, $productId)
    {
        $userId = $request->user()->id;

        // Verify product exists
        $product = Product::findOrFail($productId);

        $interaction = UserInteraction::firstOrCreate(
            [
                'user_id' => $userId,
                'product_id' => $productId,
                'type' => 'wishlist'
            ],
            [
                'score' => 3 // Higher score for wishlist
            ]
        );

        return response()->json([
            'message' => 'Product added to wishlist',
            'is_wishlisted' => true
        ]);
    }

    /**
     * Remove a product from the wishlist.
     */
    public function destroy(Request $request, $productId)
    {
        $userId = $request->user()->id;

        UserInteraction::where('user_id', $userId)
            ->where('product_id', $productId)
            ->where('type', 'wishlist')
            ->delete();

        return response()->json([
            'message' => 'Product removed from wishlist',
            'is_wishlisted' => false
        ]);
    }

    /**
     * Check if a specific product is in the wishlist.
     */
    public function check(Request $request, $productId)
    {
        $userId = $request->user()->id;

        $exists = UserInteraction::where('user_id', $userId)
            ->where('product_id', $productId)
            ->where('type', 'wishlist')
            ->exists();

        return response()->json(['is_wishlisted' => $exists]);
    }

}
