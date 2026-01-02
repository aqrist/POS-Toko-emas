<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GoldLevel>
 */
class GoldLevelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['24K', '23K', '22K', '18K']),
            'percentage' => fake()->randomFloat(2, 70, 99.99),
            'is_active' => true,
            'is_synced' => true,
            'synced_at' => now(),
        ];
    }
}
