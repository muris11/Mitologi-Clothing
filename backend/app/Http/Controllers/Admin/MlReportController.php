<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserInteraction;
use App\Models\Product;
use App\Services\RecommendationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MlReportController extends Controller
{
  protected $recommendationService;

  public function __construct(RecommendationService $recommendationService)
  {
    $this->recommendationService = $recommendationService;
  }

  public function index(Request $request)
  {
    // 1. ML Status
    $mlStatus = [
      'healthy' => $this->recommendationService->healthCheck(),
      'last_trained_at' => cache('ml_last_trained_at', 'Belum pernah ditraining'),
    ];

    $days = $request->input('days', 30);

    // 2. SPK: Top Products (based on purchase count and interaction scores)
    $topProducts = UserInteraction::select(
      'products.id',
      'products.title',
      'products.handle',
      DB::raw('COUNT(*) as purchase_count'),
      DB::raw('SUM(user_interactions.score) as total_score')
    )
      ->join('products', 'user_interactions.product_id', '=', 'products.id')
      ->where('user_interactions.type', 'purchase')
      ->where('user_interactions.created_at', '>=', Carbon::now()->subDays($days))
      ->groupBy('products.id', 'products.title', 'products.handle')
      ->orderByDesc('purchase_count')
      ->limit(10)
      ->get();

    // 3. Trending Products (recent 7 days velocity)
    $trendingProducts = UserInteraction::select(
      'products.id',
      'products.title',
      'products.handle',
      DB::raw('SUM(user_interactions.score) as trend_score'),
      DB::raw('COUNT(DISTINCT user_interactions.user_id) as unique_users')
    )
      ->join('products', 'user_interactions.product_id', '=', 'products.id')
      ->where('user_interactions.created_at', '>=', Carbon::now()->subDays(7))
      ->groupBy('products.id', 'products.title', 'products.handle')
      ->orderByDesc('trend_score')
      ->limit(10)
      ->get();

    // 4. Stock Recommendations (Low stock variants with purchase activity)
    // Stock is per-variant, so we check individual variants with low stock
    $stockRecommendations = DB::table('product_variants')
      ->select(
        'products.id as product_id',
        'products.title',
        'product_variants.title as variant_title',
        'product_variants.stock',
        DB::raw('COALESCE(purchase_stats.purchase_count, 0) as recent_purchases'),
        DB::raw('COALESCE(purchase_stats.purchase_count / 7.0, 0) as velocity_score')
      )
      ->join('products', 'product_variants.product_id', '=', 'products.id')
      ->leftJoinSub(
        UserInteraction::select('product_id', DB::raw('COUNT(*) as purchase_count'))
          ->where('type', 'purchase')
          ->where('created_at', '>=', Carbon::now()->subDays(7))
          ->groupBy('product_id'),
        'purchase_stats',
        'products.id',
        '=',
        'purchase_stats.product_id'
      )
      ->where('product_variants.stock', '<=', 10)
      ->orderBy('product_variants.stock')
      ->orderByDesc('velocity_score')
      ->limit(10)
      ->get();

    // 5. Customer Analysis: Top Spenders
    $topSpenders = \App\Models\User::select(
      'users.id',
      'users.name',
      'users.email',
      'users.avatar',
      DB::raw('COUNT(orders.id) as total_orders'),
      DB::raw('SUM(orders.total) as total_spent')
    )
      ->join('orders', 'users.id', '=', 'orders.user_id')
      ->where('orders.status', 'completed')
      ->orWhere('orders.status', 'shipped')
      ->groupBy('users.id', 'users.name', 'users.email', 'users.avatar')
      ->orderByDesc('total_spent')
      ->limit(10)
      ->get();

    // 6. Customer Analysis: Frequent Shoppers (Loyalty)
    $frequentShoppers = \App\Models\User::select(
      'users.id',
      'users.name',
      'users.email',
      'users.avatar',
      DB::raw('COUNT(orders.id) as total_orders'),
      DB::raw('SUM(orders.total) as total_spent')
    )
      ->join('orders', 'users.id', '=', 'orders.user_id')
      ->whereIn('orders.status', ['processing', 'shipped', 'completed'])
      ->groupBy('users.id', 'users.name', 'users.email', 'users.avatar')
      ->orderByDesc('total_orders')
      ->limit(10)
      ->get();

    return view('admin.ml-reports.index', compact(
      'mlStatus',
      'topProducts',
      'trendingProducts',
      'stockRecommendations',
      'topSpenders',
      'frequentShoppers'
    ));
  }

  public function retrain()
  {
    try {
      $result = $this->recommendationService->train();

      if ($result) {
        cache(['ml_last_trained_at' => now()], now()->addDay());
        return back()->with('success', 'Model AI (Naive Bayes) berhasil dilatih ulang dengan data terbaru!');
      }

      return back()->with('error', 'Gagal melatih ulang model. AI Service mungkin sedang offline.');
    } catch (\Exception $e) {
      return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
  }
}
