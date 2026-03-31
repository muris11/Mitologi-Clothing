<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductReview;
use App\Services\FrontendCacheService;

class ReviewController extends Controller
{
    /**
     * Reply to a product review.
     */
    public function reply(Request $request, Product $product, ProductReview $review)
    {
        $request->validate([
            'admin_reply' => 'required|string|max:1000'
        ]);

        $review->update([
            'admin_reply' => $request->admin_reply,
            'admin_replied_at' => now(),
        ]);

        FrontendCacheService::revalidate(['reviews', 'products']);

        return back()->with('success', 'Balasan ulasan berhasil dikirim.');
    }

    /**
     * Toggle the visibility of a product review.
     */
    public function toggleVisibility(Product $product, ProductReview $review)
    {
        $review->update([
            'is_visible' => !$review->is_visible
        ]);

        FrontendCacheService::revalidate(['reviews', 'products']);

        $status = $review->is_visible ? 'ditampilkan' : 'disembunyikan';
        return back()->with('success', "Ulasan berhasil $status.");
    }

    /**
     * Delete a product review.
     */
    public function destroy(Product $product, ProductReview $review)
    {
        FrontendCacheService::revalidate(['reviews', 'products']);

        $review->delete();

        return back()->with('success', 'Ulasan berhasil dihapus.');
    }
}
