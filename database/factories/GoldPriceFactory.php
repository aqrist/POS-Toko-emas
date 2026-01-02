<?php

namespace Database\Factories;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GoldPrice>
 */
class GoldPriceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'branch_id' => Branch::factory(),
            'market_price' => fake()->randomFloat(2, 800000, 1500000),
            'effective_date' => now()->toDateString(),
            'is_synced' => true,
            'synced_at' => now(),
        ];
    }
}
