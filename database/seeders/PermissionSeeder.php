<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        Permission::firstOrCreate(['name' => 'manage users']);
        Permission::firstOrCreate(['name' => 'manage settings']);

        Role::firstOrCreate(['name' => 'admin'])
            ->syncPermissions(['manage users']);

        Role::firstOrCreate(['name' => 'super-admin'])
            ->syncPermissions(Permission::all());
    }
}
