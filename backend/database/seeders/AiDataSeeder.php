<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\User;
use App\Models\UserInteraction;

class AiDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Enable all products
        Product::query()->update(['available_for_sale' => true]);
        $this->command->info('All products enabled.');

        // 2. Create interactions
        $user = User::first();
        $products = Product::take(3)->get();

        if ($user && $products->isNotEmpty()) {
            foreach ($products as $product) {
                UserInteraction::firstOrCreate([
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'type' => 'view',
                    'score' => 1,
                ]);
            }
            $this->command->info('Seeded ' . count($products) . ' interactions.');
        } else {
            $this->command->warn('No user or products found to seed interactions.');
        }
    }
}
