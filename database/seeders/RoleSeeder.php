<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create basic roles
        Role::firstOrCreate(['name' => 'Penyewa', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'Pemilik', 'guard_name' => 'web']);
        $pengelola = Role::firstOrCreate(['name' => 'Pengelola', 'guard_name' => 'web']);

        // Superadmin gets all permissions
        $superadmin = Role::firstOrCreate(['name' => 'Superadmin', 'guard_name' => 'web']);
        $superadmin->syncPermissions(Permission::all());
        $pengelola->syncPermissions(Permission::all());

    }
}
