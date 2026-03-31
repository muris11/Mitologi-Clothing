<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\RecommendationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RecommendationController extends Controller
{
  use \App\Traits\FormatsProduct;

  public function forUser(Request $request, RecommendationService $service): JsonResponse
  {
    $user = $request->user();
    $limit = $request->input('limit', 10);

    $recommendedIds = $service->forUser($user->id, $limit);

    // Base query with all necessary eager loads for product cards
    $baseQuery = Product::with(['variants.selectedOptions', 'options', 'images', 'categories'])
      ->withAvg(['reviews' => fn($q) => $q->where('is_visible', true)], 'rating')
      ->withCount(['reviews' => fn($q) => $q->where('is_visible', true)])
      ->where('is_hidden', false);

    if (!empty($recommendedIds)) {
      $orderMap = array_flip($recommendedIds);
      $products = (clone $baseQuery)
        ->whereIn('id', $recommendedIds)
        ->get()
        ->sortBy(fn($product) => $orderMap[$product->id] ?? PHP_INT_MAX)
        ->values();
    } else {
      // Fallback: trending products (most interactions)
      $products = (clone $baseQuery)
        ->withCount('interactions')
        ->orderByDesc('interactions_count')
        ->limit($limit)
        ->get();
    }

    $formatted = $products->map(fn(Product $p) => $this->formatProduct($p));

    return response()->json(['recommendations' => $formatted]);
  }
}
