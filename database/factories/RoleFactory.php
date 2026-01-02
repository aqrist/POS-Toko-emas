<?php

namespace Database\Factories;

use App\Enums\RoleName;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Role>
 */
class RoleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $roleNames = array_map(
            fn (RoleName $role) => $role->value,
            RoleName::cases()
        );

        return [
            'name' => fake()->unique()->randomElement($roleNames),
            'description' => fake()->sentence(),
            'is_synced' => true,
            'synced_at' => now(),
        ];
    }
}
