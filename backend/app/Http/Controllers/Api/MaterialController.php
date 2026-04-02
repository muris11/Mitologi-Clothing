<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Material;
use Illuminate\Http\JsonResponse;

class MaterialController extends Controller
{
    public function index(): JsonResponse
    {
        $materials = Material::orderBy('sort_order')
            ->get()
            ->map(fn ($m) => [
                'id' => $m->id,
                'name' => $m->name,
                'description' => $m->description,
                'image' => $m->image,
                'sortOrder' => $m->sort_order,
                'createdAt' => $m->created_at?->toISOString(),
                'updatedAt' => $m->updated_at?->toISOString(),
            ]);

        return $this->successResponse($materials, 'Daftar material berhasil diambil');
    }
}
