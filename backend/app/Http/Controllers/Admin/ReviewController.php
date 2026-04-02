<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductReview;
use App\Services\FrontendCacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ReviewController extends Controller
{
    /**
     * Reply to a product review.
     */
    public function reply(Request $request, Product $product, ProductReview $review)
    {
        $request->validate([
            'admin_reply' => 'required|string|max:1000',
        ]);

        $review->update([
            'admin_reply' => $request->admin_reply,
            'admin_replied_at' => now(),
        ]);

        Cache::forget('api.landing_page_data_v2');
        FrontendCacheService::revalidate(['reviews', 'products', 'landing-page']);

        return back()->with('success', 'Balasan ulasan berhasil dikirim.');
    }

    /**
     * Toggle the visibility of a product review.
     */
    public function toggleVisibility(Product $product, ProductReview $review)
    {
        $review->update([
            'is_visible' => ! $review->is_visible,
        ]);

        Cache::forget('api.landing_page_data_v2');
        FrontendCacheService::revalidate(['reviews', 'products', 'landing-page']);

        $status = $review->is_visible ? 'ditampilkan' : 'disembunyikan';

        return back()->with('success', "Ulasan berhasil $status.");
    }

    /**
     * Delete a product review.
     */
    public function destroy(Product $product, ProductReview $review)
    {
        Cache::forget('api.landing_page_data_v2');
        FrontendCacheService::revalidate(['reviews', 'products', 'landing-page']);

        $review->delete();

        return back()->with('success', 'Ulasan berhasil dihapus.');
    }
}
