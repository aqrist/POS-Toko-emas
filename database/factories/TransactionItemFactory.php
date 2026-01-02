<?php

namespace Database\Factories;

use App\Models\GoldLevel;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TransactionItem>
 */
class TransactionItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $weight = fake()->randomFloat(3, 0.5, 50);
        $pricePerGram = fake()->randomFloat(2, 700000, 1500000);

        return [
            'transaction_id' => Transaction::factory(),
            'gold_level_id' => GoldLevel::factory(),
            'product_type' => fake()->randomElement(['bullion', 'jewelry']),
            'weight' => $weight,
            'price_per_gram' => $pricePerGram,
            'total' => $weight * $pricePerGram,
            'is_synced' => true,
            'synced_at' => now(),
        ];
    }
}
