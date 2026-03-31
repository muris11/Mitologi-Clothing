<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserInteraction;
use App\Models\Product;
use App\Services\RecommendationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
  protected $recommendationService;

  public function __construct(RecommendationService $recommendationService)
  {
    $this->recommendationService = $recommendationService;
  }

  public function topProducts(Request $request)
  {
    $days = $request->input('days', 30);
    $limit = $request->input('limit', 10);

    $products = UserInteraction::select(
      'products.id',
      'products.title',
      'products.handle',
      DB::raw('COUNT(*) as purchase_count'),
      DB::raw('SUM(user_interactions.score) as total_score')
    )
      ->join('products', 'user_interactions.product_id', '=', 'products.id')
      ->where('user_interactions.action', 'purchase')
      ->where('user_interactions.created_at', '>=', Carbon::now()->subDays($days))
      ->groupBy('products.id', 'products.title', 'products.handle')
      ->orderByDesc('purchase_count')
      ->limit($limit)
      ->get();

    return response()->json($products);
  }

  public function trendingProducts(Request $request)
  {
    $days = $request->input('days', 7);
    $limit = $request->input('limit', 10);

    $products = UserInteraction::select(
      'products.id',
      'products.title',
      'products.handle',
      DB::raw('SUM(user_interactions.score) as trend_score'),
      DB::raw('COUNT(DISTINCT user_interactions.user_id) as unique_users')
    )
      ->join('products', 'user_interactions.product_id', '=', 'products.id')
      ->where('user_interactions.created_at', '>=', Carbon::now()->subDays($days))
      ->groupBy('products.id', 'products.title', 'products.handle')
      ->orderByDesc('trend_score')
      ->limit($limit)
      ->get();

    return response()->json($products);
  }

  public function stockRecommendations(Request $request)
  {
    $limit = $request->input('limit', 10);
    $stockThreshold = $request->input('threshold', 10);

    // Products with low stock but high purchase velocity
    $products = Product::select(
      'products.id',
      'products.title',
      'products.handle',
      'products.stock',
      DB::raw('COALESCE(purchase_stats.purchase_count, 0) as recent_purchases'),
      DB::raw('COALESCE(purchase_stats.velocity, 0) as velocity_score')
    )
      ->leftJoinSub(
        UserInteraction::select('product_id', DB::raw('COUNT(*) as purchase_count'))
          ->selectRaw('COUNT(*) / 7.0 as velocity') // per day
          ->where('action', 'purchase')
          ->where('created_at', '>=', Carbon::now()->subDays(7))
          ->groupBy('product_id'),
        'purchase_stats',
        'products.id',
        '=',
        'purchase_stats.product_id'
      )
      ->where('products.stock', '<=', $stockThreshold)
      ->whereRaw('COALESCE(purchase_stats.purchase_count, 0) > 0')
      ->orderByDesc('velocity_score')
      ->limit($limit)
      ->get();

    return response()->json($products);
  }

  public function mlStatus()
  {
    $health = $this->recommendationService->healthCheck();
    $lastTrained = cache('ml_last_trained_at');

    return response()->json([
      'service_healthy' => $health,
      'last_trained_at' => $lastTrained,
      'model_path' => storage_path('app/ml/model.pkl'),
      'model_exists' => file_exists(storage_path('app/ml/model.pkl')),
    ]);
  }

  public function retrainModel()
  {
    try {
      $result = $this->recommendationService->train();
      cache(['ml_last_trained_at' => now()], now()->addDay());

      return response()->json([
        'message' => 'Model retrained successfully',
        'result' => $result,
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'message' => 'Failed to retrain model',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  public function exportTrainingData(Request $request)
  {
    // Only allow extraction of recent interactions to keep payloads fast and memory usage low.
    $months = $request->input('months', 6);

    $products = Product::with('categories')->select('id', 'title', 'handle', 'description', 'created_at')
      ->get()
      ->map(function ($product) {
        return [
          'id' => $product->id,
          'title' => $product->title,
          'description' => strip_tags($product->description ?? ''),
          'category' => $product->categories->first()?->title ?? '',
        ];
      });

    $interactions = UserInteraction::select('user_id', 'product_id', 'type as action', 'score')
      ->where('created_at', '>=', Carbon::now()->subMonths($months))
      ->get();

    return response()->json([
      'products' => $products,
      'interactions' => $interactions,
    ]);
  }
}
