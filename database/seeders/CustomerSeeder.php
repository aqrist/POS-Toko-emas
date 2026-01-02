<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $branch = Branch::query()->first();

        if (! $branch) {
            return;
        }

        Customer::query()->firstOrCreate(
            ['name' => 'Customer Walk-in', 'branch_id' => $branch->id],
            [
                'phone' => null,
                'address' => null,
                'is_synced' => true,
                'synced_at' => now(),
            ]
        );
    }
}
