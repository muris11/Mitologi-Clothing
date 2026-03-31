<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\JsonResponse;

class PageController extends Controller
{
    public function index(): JsonResponse
    {
        $pages = Page::all()->map(fn($p) => [
            'id' => (string) $p->id,
            'title' => $p->title,
            'handle' => $p->handle,
            'body' => $p->body,
            'bodySummary' => $p->body_summary,
            'seo' => [
                'title' => $p->seo_title ?? $p->title,
                'description' => $p->seo_description ?? '',
            ],
            'createdAt' => $p->created_at?->toISOString(),
            'updatedAt' => $p->updated_at?->toISOString(),
        ]);

        return response()->json($pages);
    }

    public function show(string $handle): JsonResponse
    {
        $page = Page::where('handle', $handle)->firstOrFail();

        return response()->json([
            'id' => (string) $page->id,
            'title' => $page->title,
            'handle' => $page->handle,
            'body' => $page->body,
            'bodySummary' => $page->body_summary,
            'seo' => [
                'title' => $page->seo_title ?? $page->title,
                'description' => $page->seo_description ?? '',
            ],
            'createdAt' => $page->created_at?->toISOString(),
            'updatedAt' => $page->updated_at?->toISOString(),
        ]);
    }
}
