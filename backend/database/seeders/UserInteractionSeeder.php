<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use App\Models\UserInteraction;
use Illuminate\Database\Seeder;

class UserInteractionSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $products = Product::all();

        if ($users->isEmpty() || $products->isEmpty()) {
            return;
        }

        foreach ($users as $user) {
            // Each user interacts with 3-8 random products
            $randomProducts = $products->random(rand(3, 8));

            foreach ($randomProducts as $product) {
                // Determine interaction type
                $rand = rand(1, 100);
                if ($rand <= 60) {
                    $type = 'view';
                    $score = 1;
                } elseif ($rand <= 85) {
                    $type = 'cart';
                    $score = 3;
                } else {
                    $type = 'purchase';
                    $score = 5;
                }

                UserInteraction::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'product_id' => $product->id,
                    ],
                    [
                        'type' => $type,
                        'score' => $score,
                    ]
                );
            }
        }
    }
}
