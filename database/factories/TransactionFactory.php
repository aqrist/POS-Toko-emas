<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subtotal = fake()->randomFloat(2, 100000, 5000000);
        $additionalFee = fake()->optional()->randomFloat(2, 0, 100000);
        $total = $subtotal + ($additionalFee ?? 0);

        return [
            'branch_id' => Branch::factory(),
            'user_id' => User::factory(),
            'customer_id' => Customer::factory(),
            'type' => fake()->randomElement(['buy', 'sell']),
            'payment_method' => fake()->randomElement(['cash', 'transfer']),
            'occurred_at' => now(),
            'subtotal' => $subtotal,
            'additional_fee' => $additionalFee,
            'total' => $total,
            'notes' => fake()->optional()->sentence(),
            'is_synced' => true,
            'synced_at' => now(),
        ];
    }
}
