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
            ->get()
            ->map(fn ($p) => [
                'id' => $p->id,
                'title' => $p->title,
                'slug' => $p->slug,
                'description' => $p->description,
                'category' => $p->category,
                'imageUrl' => $p->image_url,
                'sortOrder' => $p->sort_order,
                'isActive' => (bool) $p->is_active,
                'createdAt' => $p->created_at?->toISOString(),
                'updatedAt' => $p->updated_at?->toISOString(),
            ]);

        return $this->successResponse($portfolios, 'Daftar portfolio berhasil diambil');
    }

    public function show($slug): JsonResponse
    {
        $portfolio = PortfolioItem::where('slug', $slug)
            ->where('is_active', true)
            ->first();

        if (! $portfolio) {
            return $this->notFoundResponse('Portfolio tidak ditemukan');
        }

        $data = [
            'id' => $portfolio->id,
            'title' => $portfolio->title,
            'slug' => $portfolio->slug,
            'description' => $portfolio->description,
            'category' => $portfolio->category,
            'imageUrl' => $portfolio->image_url,
            'sortOrder' => $portfolio->sort_order,
            'isActive' => (bool) $portfolio->is_active,
            'createdAt' => $portfolio->created_at?->toISOString(),
            'updatedAt' => $portfolio->updated_at?->toISOString(),
        ];

        return $this->successResponse($data, 'Detail portfolio berhasil diambil');
    }
}
