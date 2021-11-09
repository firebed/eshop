<?php

namespace Eshop\Database\Seeders;

use Firebed\Permission\Models\Role;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = Role::create(['name' => 'Super Admin']);
        $superAdmin->permissions()->create(['name' => 'Edit configuration']);

        $admin = Role::create(['name' => 'Admin']);
        $admin->permissions()->createMany([
            ['name' => 'Manage products'],
            ['name' => 'Manage categories'],
            ['name' => 'Manage manufacturers'],
            ['name' => 'Manage collections'],
            ['name' => 'Manage countries'],
            ['name' => 'Manage translations'],
            ['name' => 'Manage shipping methods'],
            ['name' => 'Manage country shipping methods'],
            ['name' => 'Manage payment methods'],
            ['name' => 'Manage country payment methods'],
            ['name' => 'Manage users'],
            ['name' => 'Manage permissions'],
            ['name' => 'Manage slides'],
            ['name' => 'View analytics'],
            ['name' => 'Manage orders'],
            ['name' => 'Manage POS'],
        ]);

        $employee = Role::create(['name' => 'Employee']);
        $employee->permissions()->createMany([
            ['name' => 'View dashboard'],
            ['name' => 'Create POS order'],
            ['name' => 'Manage assigned orders'],
        ]);
    }
}
