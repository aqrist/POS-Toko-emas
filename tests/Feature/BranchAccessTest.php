<?php

use App\Enums\RoleName;
use App\Models\Branch;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('allows super admin to access branches', function () {
    $role = Role::factory()->create(['name' => RoleName::SuperAdmin->value]);
    $branch = Branch::factory()->create();
    $user = User::factory()->create([
        'role_id' => $role->id,
        'branch_id' => $branch->id,
    ]);

    $this->actingAs($user)
        ->get('/branches')
        ->assertSuccessful();
});

it('forbids branch admin from accessing branches', function () {
    $role = Role::factory()->create(['name' => RoleName::BranchAdmin->value]);
    $branch = Branch::factory()->create();
    $user = User::factory()->create([
        'role_id' => $role->id,
        'branch_id' => $branch->id,
    ]);

    $this->actingAs($user)
        ->get('/branches')
        ->assertForbidden();
});
