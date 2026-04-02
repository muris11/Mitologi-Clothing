<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $title = $this->faker->unique()->sentence(3);

        return [
            'title' => $title,
            'handle' => Str::slug($title),
            'description' => $this->faker->paragraph,
            'description_html' => '<p>'.$this->faker->paragraph.'</p>',
            'available_for_sale' => true,
            'is_hidden' => false,
            'seo_title' => $title,
            'seo_description' => $this->faker->sentence,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Product $product) {
            ProductVariant::factory()->count(1)->create([
                'product_id' => $product->id,
            ]);
        });
    }
}
