<?php

use App\Enums\RoleName;
use App\Models\Branch;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('allows cashier to access transactions', function () {
    $role = Role::factory()->create(['name' => RoleName::Cashier->value]);
    $branch = Branch::factory()->create();
    $user = User::factory()->create([
        'role_id' => $role->id,
        'branch_id' => $branch->id,
    ]);

    $this->actingAs($user)
        ->get('/transactions')
        ->assertSuccessful();
});

it('allows branch admin to access transactions', function () {
    $role = Role::factory()->create(['name' => RoleName::BranchAdmin->value]);
    $branch = Branch::factory()->create();
    $user = User::factory()->create([
        'role_id' => $role->id,
        'branch_id' => $branch->id,
    ]);

    $this->actingAs($user)
        ->get('/transactions')
        ->assertSuccessful();
});
