<?php

namespace Database\Seeders;

use App\Enums\RoleName;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            RoleName::SuperAdmin->value => 'Akses penuh untuk seluruh cabang.',
            RoleName::BranchAdmin->value => 'Akses admin untuk cabang tertentu.',
            RoleName::Cashier->value => 'Akses kasir untuk transaksi harian.',
        ];

        foreach ($roles as $name => $description) {
            Role::query()->firstOrCreate(
                ['name' => $name],
                [
                    'description' => $description,
                    'is_synced' => true,
                    'synced_at' => now(),
                ]
            );
        }
    }
}
