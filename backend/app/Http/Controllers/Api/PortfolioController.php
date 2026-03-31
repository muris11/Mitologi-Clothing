<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PortfolioItem;
use Illuminate\Http\JsonResponse;

class PortfolioController extends Controller
{
    public function index(): JsonResponse
    {
        $portfolios = PortfolioItem::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($portfolios);
    }

    public function show($slug): JsonResponse
    {
        $portfolio = PortfolioItem::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return response()->json($portfolio);
    }
}
