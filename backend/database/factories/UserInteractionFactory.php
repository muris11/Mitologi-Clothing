<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use App\Models\UserInteraction;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserInteractionFactory extends Factory
{
    protected $model = UserInteraction::class;

    public function definition(): array
    {
        $interactions = [
            'view' => 1,
            'cart' => 3,
            'purchase' => 5,
        ];

        $type = $this->faker->randomElement(array_keys($interactions));

        return [
            'user_id' => User::factory(),
            'product_id' => Product::factory(),
            'type' => $type,
            'score' => $interactions[$type],
        ];
    }
}
