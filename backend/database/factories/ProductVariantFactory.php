<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductVariantFactory extends Factory
{
    protected $model = ProductVariant::class;

    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'title' => 'Default Variant',
            'price' => $this->faker->numberBetween(10000, 500000),
            'sku' => $this->faker->unique()->ean8,
            'stock' => 10,
            'currency_code' => 'IDR',
        ];
    }
}
