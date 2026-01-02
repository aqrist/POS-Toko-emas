<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SyncLog>
 */
class SyncLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'table_name' => fake()->word(),
            'record_id' => fake()->uuid(),
            'status' => fake()->randomElement(['pending', 'synced', 'failed']),
            'message' => fake()->optional()->sentence(),
            'payload' => ['source' => 'factory'],
            'synced_at' => now(),
        ];
    }
}
