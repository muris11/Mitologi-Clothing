<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReviewRequest;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function index(string $handle): JsonResponse
    {
        $product = Product::where('handle', $handle)->first();

        if (! $product) {
            return $this->notFoundResponse('Produk');
        }

        $paginator = ProductReview::with('user:id,name,avatar')
            ->where('product_id', $product->id)
            ->visible()
            ->latest()
            ->paginate(10);

        $paginator->getCollection()->transform(function ($review) {
            return [
                'id' => $review->id,
                'userId' => $review->user_id,
                'userName' => $review->user->name ?? 'Anonymous',
                'userAvatar' => $review->user ? $review->user->avatar_url : null,
                'rating' => $review->rating,
                'comment' => $review->comment,
                'adminReply' => $review->admin_reply,
                'adminRepliedAt' => $review->admin_replied_at,
                'createdAt' => $review->created_at,
            ];
        });

        $allReviews = ProductReview::where('product_id', $product->id)->visible()->get();
        $totalReviews = $allReviews->count();
        $averageRating = $totalReviews > 0 ? round($allReviews->avg('rating'), 1) : 0;

        $ratingBreakdown = [
            '5' => $allReviews->where('rating', 5)->count(),
            '4' => $allReviews->where('rating', 4)->count(),
            '3' => $allReviews->where('rating', 3)->count(),
            '2' => $allReviews->where('rating', 2)->count(),
            '1' => $allReviews->where('rating', 1)->count(),
        ];

        return $this->successResponse([
            'reviews' => $paginator->items(),
            'summary' => [
                'averageRating' => $averageRating,
                'totalReviews' => $totalReviews,
                'ratingBreakdown' => $ratingBreakdown,
            ],
        ]);
    }

    public function store(StoreReviewRequest $request, string $handle): JsonResponse
    {
        $validated = $request->validated();

        $product = Product::where('handle', $handle)->first();

        if (! $product) {
            return $this->notFoundResponse('Produk');
        }

        $user = Auth::user();

        $existingReview = ProductReview::where('product_id', $product->id)
            ->where('user_id', $user->id)
            ->first();

        if ($existingReview) {
            return $this->validationErrorResponse(
                'Anda sudah memberikan ulasan untuk produk ini.'
            );
        }

        $validOrder = Order::where('user_id', $user->id)
            ->whereIn('status', ['shipped', 'completed'])
            ->whereHas('items', function ($query) use ($product) {
                $query->where('product_id', $product->id);
            })
            ->first();

        if (! $validOrder) {
            return $this->validationErrorResponse(
                'Anda hanya bisa memberikan ulasan untuk produk yang sudah Anda beli dan terima.'
            );
        }

        $review = ProductReview::create([
            'product_id' => $product->id,
            'user_id' => $user->id,
            'order_id' => $validOrder->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'is_visible' => true,
        ]);

        return $this->successResponse(
            $review,
            'Ulasan berhasil dikirim.',
            201
        );
    }

    public function update(StoreReviewRequest $request, ProductReview $review): JsonResponse
    {
        if ($review->user_id !== Auth::id()) {
            return $this->forbiddenResponse('Unauthorized');
        }

        $validated = $request->validated();

        $review->update([
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        return $this->successResponse(
            $review,
            'Ulasan berhasil diperbarui.'
        );
    }

    public function destroy(ProductReview $review): JsonResponse
    {
        if ($review->user_id !== Auth::id()) {
            return $this->forbiddenResponse('Unauthorized');
        }

        $review->delete();

        return $this->successResponse(null, 'Ulasan berhasil dihapus.');
    }
}
