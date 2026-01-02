<?php

namespace Database\Seeders;

use App\Models\GoldLevel;
use Illuminate\Database\Seeder;

class GoldLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $levels = [
            ['name' => '24K', 'percentage' => 99.99],
            ['name' => '23K', 'percentage' => 95.83],
            ['name' => '22K', 'percentage' => 91.67],
            ['name' => '18K', 'percentage' => 75.00],
        ];

        foreach ($levels as $level) {
            GoldLevel::query()->firstOrCreate(
                ['name' => $level['name']],
                [
                    'percentage' => $level['percentage'],
                    'is_active' => true,
                    'is_synced' => true,
                    'synced_at' => now(),
                ]
            );
        }
    }
}
