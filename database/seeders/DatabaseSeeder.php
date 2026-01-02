<?php

namespace Database\Seeders;

use App\Enums\RoleName;
use App\Models\Branch;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            BranchSeeder::class,
            GoldLevelSeeder::class,
            GoldPriceSeeder::class,
            CustomerSeeder::class,
        ]);

        $branch = Branch::query()->first();
        $superAdminRole = Role::query()->where('name', RoleName::SuperAdmin->value)->first();
        $branchAdminRole = Role::query()->where('name', RoleName::BranchAdmin->value)->first();
        $cashierRole = Role::query()->where('name', RoleName::Cashier->value)->first();

        if ($superAdminRole) {
            User::factory()->create([
                'name' => 'Super Admin',
                'email' => 'superadmin@example.com',
                'role_id' => $superAdminRole->id,
                'branch_id' => $branch?->id,
            ]);
        }

        if ($branch && $branchAdminRole) {
            User::factory()->create([
                'name' => 'Admin Cabang',
                'email' => 'admincabang@example.com',
                'role_id' => $branchAdminRole->id,
                'branch_id' => $branch->id,
            ]);
        }

        if ($branch && $cashierRole) {
            User::factory()->create([
                'name' => 'Kasir',
                'email' => 'kasir@example.com',
                'role_id' => $cashierRole->id,
                'branch_id' => $branch->id,
            ]);
        }
    }
}
