<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\JsonResponse;

class MenuController extends Controller
{
    public function show(string $handle): JsonResponse
    {
        $menus = Menu::where('handle', $handle)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        if ($menus->isEmpty()) {
            return $this->notFoundResponse('Menu tidak ditemukan');
        }

        $data = $menus->map(fn ($m) => [
            'title' => $m->title,
            'path' => $m->path,
        ]);

        return $this->successResponse($data, 'Menu berhasil diambil');
    }
}
