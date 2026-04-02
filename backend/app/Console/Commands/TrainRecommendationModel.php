<?php

namespace App\Console\Commands;

use App\Models\UserInteraction;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class TrainRecommendationModel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ai:train';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Train the recommendation model using user interactions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Fetching user interactions...');

        $interactions = UserInteraction::select('user_id', 'product_id', 'type', 'score')
            ->get()
            ->map(function ($interaction) {
                return [
                    'user_id' => (int) $interaction->user_id,
                    'product_id' => (int) $interaction->product_id,
                    'type' => $interaction->type,
                    'score' => (float) $interaction->score,
                ];
            })
            ->toArray();

        $this->info('Fetching products for content-based filtering...');

        $products = \App\Models\Product::where('available_for_sale', true)
            ->get()
            ->map(function ($product) {
                // Get category name safely
                $category = $product->category ? $product->category->name : '';

                return [
                    'id' => (int) $product->id,
                    'title' => (string) $product->title,
                    'description' => (string) strip_tags($product->description),
                    'category' => (string) $category,
                ];
            })
            ->toArray();

        if (empty($interactions) && empty($products)) {
            $this->warn('No data found to train model.');

            return;
        }

        $this->info('Sending '.count($interactions).' interactions and '.count($products).' products to AI service...');

        $baseUrl = config('services.ai.url');
        $url = rtrim($baseUrl, '/').'/train';

        try {
            $response = Http::post($url, [
                'interactions' => $interactions,
                'products' => $products,
            ]);

            if ($response->successful()) {
                $this->info('Model trained successfully!');
            } else {
                $this->error('Failed to train model: '.$response->body());
            }
        } catch (\Exception $e) {
            $this->error('Could not connect to AI service at '.$url.': '.$e->getMessage());
        }
    }
}
