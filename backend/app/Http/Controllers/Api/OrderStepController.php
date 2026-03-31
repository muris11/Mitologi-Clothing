<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OrderStep;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderStepController extends Controller
{
    /**
     * Get all order steps ordered by sort_order
     * Can be filtered by type using ?type=langsung|ecommerce
     */
    public function index(Request $request): JsonResponse
    {
        $query = OrderStep::orderBy('sort_order');
        
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }
        
        $steps = $query->get();
        
        // Optional: Group by type if requested
        if ($request->has('grouped') && $request->grouped === 'true') {
            return response()->json($steps->groupBy('type'));
        }
        
        return response()->json($steps);
    }
}
