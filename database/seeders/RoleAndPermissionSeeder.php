<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'manage institutions', 'manage services', 'manage slots', 'manage queues',
            'manage reports', 'manage report categories', 'manage sos',
            'manage news', 'manage news categories', 'manage emergency alerts',
            'manage users', 'view activity logs', 'manage settings',
        ];

        foreach ($permissions as $name) {
            Permission::firstOrCreate(['name' => $name]);
        }

        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $admin->givePermissionTo(Permission::all());

        Role::firstOrCreate(['name' => 'User']);
    }
}
