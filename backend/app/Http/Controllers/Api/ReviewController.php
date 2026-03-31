<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReviewRequest;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
  /**
   * Display a listing of reviews for a specific product.
   */
  public function index(string $handle)
  {
    $product = Product::where('handle', $handle)->firstOrFail();

    $paginator = ProductReview::with('user:id,name,avatar')
      ->where('product_id', $product->id)
      ->visible()
      ->latest()
      ->paginate(10);

    $paginator->getCollection()->transform(function ($review) {
      return [
        'id' => $review->id,
        'user_id' => $review->user_id,
        'user_name' => $review->user->name ?? 'Anonymous',
        'user_avatar' => $review->user ? $review->user->avatar_url : null,
        'rating' => $review->rating,
        'comment' => $review->comment,
        'admin_reply' => $review->admin_reply,
        'admin_replied_at' => $review->admin_replied_at,
        'created_at' => $review->created_at,
      ];
    });

    // Calculate summary
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

    return response()->json([
      'reviews' => $paginator,
      'summary' => [
        'average_rating' => $averageRating,
        'total_reviews' => $totalReviews,
        'rating_breakdown' => $ratingBreakdown,
      ]
    ]);
  }

  /**
   * Store a newly created review in storage.
   */
  public function store(StoreReviewRequest $request, string $handle)
  {
    $validated = $request->validated();

    $product = Product::where('handle', $handle)->firstOrFail();
    $user = Auth::user();

    // 1. Check if user already reviewed this product
    $existingReview = ProductReview::where('product_id', $product->id)
      ->where('user_id', $user->id)
      ->first();

    if ($existingReview) {
      return response()->json([
        'message' => 'Anda sudah memberikan ulasan untuk produk ini.'
      ], 403);
    }

    // 2. Check if user purchased this product and order status is delivered/completed
    $validOrder = Order::where('user_id', $user->id)
      ->whereIn('status', ['shipped', 'completed'])
      ->whereHas('items', function ($query) use ($product) {
        $query->where('product_id', $product->id);
      })
      ->first();

    if (!$validOrder) {
      return response()->json([
        'message' => 'Anda hanya bisa memberikan ulasan untuk produk yang sudah Anda beli dan terima.'
      ], 403);
    }

    $review = ProductReview::create([
      'product_id' => $product->id,
      'user_id' => $user->id,
      'order_id' => $validOrder->id,
      'rating' => $validated['rating'],
      'comment' => $validated['comment'],
      'is_visible' => true,
    ]);

    return response()->json([
      'message' => 'Ulasan berhasil dikirim.',
      'review' => $review
    ], 201);
  }

  /**
   * Update the specified review.
   */
  public function update(StoreReviewRequest $request, ProductReview $review)
  {
    if ($review->user_id !== Auth::id()) {
      return response()->json(['message' => 'Unauthorized'], 403);
    }

    $validated = $request->validated();

    $review->update([
      'rating' => $validated['rating'],
      'comment' => $validated['comment'],
    ]);

    return response()->json([
      'message' => 'Ulasan berhasil diperbarui.',
      'review' => $review
    ]);
  }

  /**
   * Remove the specified review.
   */
  public function destroy(ProductReview $review)
  {
    if ($review->user_id !== Auth::id()) {
      return response()->json(['message' => 'Unauthorized'], 403);
    }

    $review->delete();

    return response()->json([
      'message' => 'Ulasan berhasil dihapus.'
    ]);
  }
}
