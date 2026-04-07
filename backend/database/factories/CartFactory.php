<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cart>
 */
class CartFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => null,
            'session_id' => Str::uuid()->toString(),
        ];
    }

    /**
     * Indicate that the cart belongs to a user.
     */
    public function forUser(?int $userId = null): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $userId ?? \App\Models\User::factory()->create()->id,
            'session_id' => null,
        ]);
    }

    /**
     * Indicate that the cart is a guest cart.
     */
    public function guest(): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => null,
            'session_id' => Str::uuid()->toString(),
        ]);
    }
}
