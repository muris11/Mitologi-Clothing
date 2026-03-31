<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Material;
use Illuminate\Http\JsonResponse;

class MaterialController extends Controller
{
    /**
     * Get all materials ordered by sort_order
     */
    public function index(): JsonResponse
    {
        $materials = Material::orderBy('sort_order')->get();
        return response()->json($materials);
    }
}
