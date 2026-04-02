<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OrderStep;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderStepController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = OrderStep::orderBy('sort_order');

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        $steps = $query->get();

        $formatted = $steps->map(fn ($s) => [
            'id' => $s->id,
            'title' => $s->title,
            'description' => $s->description,
            'icon' => $s->icon,
            'type' => $s->type,
            'sortOrder' => $s->sort_order,
            'createdAt' => $s->created_at?->toISOString(),
            'updatedAt' => $s->updated_at?->toISOString(),
        ]);

        // Optional: Group by type if requested
        if ($request->has('grouped') && $request->grouped === 'true') {
            $data = $formatted->groupBy('type');

            return $this->successResponse($data, 'Langkah-langkah pemesanan berhasil diambil');
        }

        return $this->successResponse($formatted, 'Langkah-langkah pemesanan berhasil diambil');
    }
}
