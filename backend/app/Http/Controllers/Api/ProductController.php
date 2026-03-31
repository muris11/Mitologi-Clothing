<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\UserInteraction;
use App\Services\RecommendationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
  use \App\Traits\FormatsProduct;

  public function index(Request $request): JsonResponse
  {
    // Build a cache key from all query parameters for smart caching
    $cacheKey = 'api.products.' . md5(json_encode($request->query()));
    $cacheTtl = 600; // 10 minutes

    $result = Cache::remember($cacheKey, $cacheTtl, function () use ($request) {
      $query = Product::with(['variants', 'options', 'images', 'categories'])
        ->withAvg(['reviews' => fn($q) => $q->where('is_visible', true)], 'rating')
        ->withCount(['reviews' => fn($q) => $q->where('is_visible', true)])
        ->where('is_hidden', false);

      // Filter by IDs (e.g. for recommendations)
      if ($ids = $request->input('ids')) {
        $query->whereIn('id', explode(',', $ids));
      }

      // Search
      if ($search = $request->input('q')) {
        $query->where(function ($q) use ($search) {
          $q->where('title', 'like', "%{$search}%")
            ->orWhere('description', 'like', "%{$search}%");
        });
      }

      // Filter by Category Handle
      if ($categoryHandle = $request->input('category')) {
        $query->whereHas('categories', function ($q) use ($categoryHandle) {
          $q->where('handle', $categoryHandle);
        });
      }

      // Filter by Price Range
      if ($minPrice = $request->input('minPrice')) {
        $query->whereHas('variants', function ($q) use ($minPrice) {
          $q->where('price', '>=', $minPrice);
        });
      }
      if ($maxPrice = $request->input('maxPrice')) {
        $query->whereHas('variants', function ($q) use ($maxPrice) {
          $q->where('price', '<=', $maxPrice);
        });
      }

      // Sort
      $sortKey = $request->input('sortKey', 'RELEVANCE');
      $reverse = filter_var($request->input('reverse', false), FILTER_VALIDATE_BOOLEAN);

      switch ($sortKey) {
        case 'PRICE':
          $query->orderBy(
            Product::selectRaw('MIN(price)')
              ->from('product_variants')
              ->whereColumn('product_variants.product_id', 'products.id'),
            $reverse ? 'desc' : 'asc'
          );
          break;
        case 'CREATED_AT':
          $query->orderBy('created_at', $reverse ? 'desc' : 'asc');
          break;
        case 'BEST_SELLING':
          $query->withCount('interactions')
            ->orderBy('interactions_count', 'desc');
          break;
        default:
          $query->orderBy('created_at', 'desc');
      }

      $limit = $request->input('limit', 20);
      $paginator = $query->paginate($limit);

      return [
        'products' => collect($paginator->items())->map(fn(Product $p) => $this->formatProduct($p)),
        'pagination' => [
          'total' => $paginator->total(),
          'per_page' => $paginator->perPage(),
          'current_page' => $paginator->currentPage(),
          'last_page' => $paginator->lastPage(),
        ]
      ];
    });

    return response()->json($result);
  }

  public function show(string $handle): JsonResponse
  {
    $product = Product::with(['variants.selectedOptions', 'options', 'images', 'categories'])
      ->withAvg(['reviews' => fn($q) => $q->where('is_visible', true)], 'rating')
      ->withCount(['reviews' => fn($q) => $q->where('is_visible', true)])
      ->where('handle', $handle)
      ->first();

    if (!$product) {
      return response()->json(['error' => 'Product not found'], 404);
    }

    // Track view interaction
    if ($user = auth('sanctum')->user()) {
      UserInteraction::create([
        'user_id' => $user->id,
        'product_id' => $product->id,
        'type' => 'view',
        'score' => 1,
      ]);
    }

    return response()->json($this->formatProduct($product));
  }

  public function recommendations(int $id, RecommendationService $service): JsonResponse
  {
    $product = Product::findOrFail($id);

    // Base query with necessary eager loads for product cards
    $baseQuery = Product::with(['variants', 'options', 'images', 'categories'])
      ->withAvg(['reviews' => fn($q) => $q->where('is_visible', true)], 'rating')
      ->withCount(['reviews' => fn($q) => $q->where('is_visible', true)])
      ->where('is_hidden', false);

    // Try AI recommendations first
    $aiRecs = $service->forProduct($id);

    if (!empty($aiRecs)) {
      $products = (clone $baseQuery)->whereIn('id', $aiRecs)->get();
    } else {
      // Fallback: same category products
      $categoryIds = $product->categories->pluck('id');
      $products = (clone $baseQuery)
        ->where('id', '!=', $id)
        ->whereHas('categories', fn($q) => $q->whereIn('categories.id', $categoryIds))
        ->limit(8)
        ->get();

      // If no category matches, get random products
      if ($products->isEmpty()) {
        $products = (clone $baseQuery)
          ->where('id', '!=', $id)
          ->inRandomOrder()
          ->limit(8)
          ->get();
      }
    }

    return response()->json($products->map(fn(Product $p) => $this->formatProduct($p)));
  }

  public function newArrivals(Request $request): JsonResponse
  {
    $limit = $request->input('limit', 10);
    
    // New Arrivals: Produk terbaru (max 60 hari) dengan interaksi tinggi
    $products = Product::with(['variants.selectedOptions', 'options', 'images', 'categories'])
      ->where('is_hidden', false)
      ->where('created_at', '>=', now()->subDays(60))
      ->withCount(['interactions' => function($query) {
        $query->selectRaw('SUM(CASE 
          WHEN type = "view" THEN 1 
          WHEN type = "cart_add" THEN 3 
          WHEN type = "purchase" THEN 5 
          ELSE 0 
        END) as interactions_weighted');
      }])
      ->orderByDesc('created_at')
      ->limit($limit)
      ->get();

    return response()->json($products->map(fn(Product $p) => $this->formatProduct($p)));
  }

  public function bestSellers(Request $request): JsonResponse
  {
    $limit = $request->input('limit', 10);
    
    // Best Sellers: Kombinasi interaksi dan penjualan
    $products = Product::with(['variants.selectedOptions', 'options', 'images', 'categories'])
      ->where('is_hidden', false)
      ->withCount(['interactions as interaction_score' => function($query) {
        $query->selectRaw('SUM(CASE 
          WHEN type = "view" THEN 1 
          WHEN type = "cart_add" THEN 2 
          WHEN type = "purchase" THEN 5 
          ELSE 0 
        END)');
      }])
      ->orderByDesc('interaction_score')
      ->limit($limit)
      ->get();

    return response()->json($products->map(fn(Product $p) => $this->formatProduct($p)));
  }

  // Traits used: FormatsProduct
}
