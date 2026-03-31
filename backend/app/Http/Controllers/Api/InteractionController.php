<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserInteraction;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class InteractionController extends Controller
{
    public function storeBatch(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'interactions' => 'required|array|max:100',
            'interactions.*.product_id' => 'required|integer|exists:products,id',
            'interactions.*.action' => 'required|in:view,cart,purchase',
            'interactions.*.score' => 'nullable|integer',
        ]);

        $userId = auth()->id();

        // Skip if user is not authenticated (guest users)
        if (!$userId) {
            return response()->json([
                'message' => 'Interactions skipped for guest user',
                'count' => 0,
            ]);
        }

        $interactions = $validated['interactions'];
        $records = [];

        foreach ($interactions as $interaction) {
            $score = $interaction['score'] ?? match($interaction['action']) {
                'view' => 1,
                'cart' => 3,
                'purchase' => 5,
                default => 1,
            };

            $records[] = [
                'user_id' => $userId,
                'product_id' => $interaction['product_id'],
                'type' => $interaction['action'],
                'score' => $score,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::transaction(function () use ($records) {
            UserInteraction::insert($records);
        });

        return response()->json([
            'message' => 'Interactions recorded',
            'count' => count($records),
        ]);
    }
}
