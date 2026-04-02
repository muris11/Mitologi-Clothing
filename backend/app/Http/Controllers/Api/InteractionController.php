<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserInteraction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InteractionController extends Controller
{
    public function storeBatch(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'interactions' => 'required|array|max:100',
            'interactions.*.productId' => 'required|integer|exists:products,id',
            'interactions.*.action' => 'required|in:view,cart,purchase',
            'interactions.*.score' => 'nullable|integer',
        ]);

        $user = auth()->user();

        // Skip if user is not authenticated (guest users)
        if (! $user) {
            return $this->successResponse([
                'count' => 0,
            ], 'Interaksi dilewati untuk pengguna guest');
        }

        $interactions = $validated['interactions'];
        $records = [];

        foreach ($interactions as $interaction) {
            $score = $interaction['score'] ?? match ($interaction['action']) {
                'view' => 1,
                'cart' => 3,
                'purchase' => 5,
                default => 1,
            };

            $records[] = [
                'user_id' => $user->id,
                'product_id' => $interaction['productId'],
                'type' => $interaction['action'],
                'score' => $score,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        try {
            DB::transaction(function () use ($records) {
                UserInteraction::insert($records);
            });

            return $this->successResponse([
                'count' => count($records),
            ], 'Interaksi berhasil dicatat');
        } catch (\Exception $e) {
            return $this->errorResponse('failed_to_record', 'Gagal mencatat interaksi', 500);
        }
    }
}
