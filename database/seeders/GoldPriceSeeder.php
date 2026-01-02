<?php

namespace Database\Seeders;

use App\Models\GoldPrice;
use Illuminate\Database\Seeder;

class GoldPriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        GoldPrice::query()->firstOrCreate(
            [
                'branch_id' => null,
                'effective_date' => now()->toDateString(),
            ],
            [
                'market_price' => 1000000,
                'is_synced' => true,
                'synced_at' => now(),
            ]
        );
    }
}
