<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Branch::query()->firstOrCreate(
            ['code' => 'HQ-001'],
            [
                'name' => 'Cabang Utama',
                'phone' => '021-000000',
                'address' => 'Alamat cabang utama',
                'is_active' => true,
                'is_synced' => true,
                'synced_at' => now(),
            ]
        );
    }
}
