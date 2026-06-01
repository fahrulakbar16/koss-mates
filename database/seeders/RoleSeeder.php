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
        Role::firstOrCreate(['name' => 'Penyewa', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'Pemilik', 'guard_name' => 'web']);

        // Superadmin has all permissions
        $superadmin = Role::firstOrCreate(['name' => 'Superadmin', 'guard_name' => 'web']);
        $superadmin->syncPermissions(Permission::all());

        // Pengelola cannot manage roles, global settings, or whatsapp config
        $superadminOnlyPermissions = [
            'roles.view', 'roles.create', 'roles.edit', 'roles.delete',
            'settings.view', 'settings.edit',
            'whatsapp_config.view', 'whatsapp_config.edit',
        ];
        $pengelola = Role::firstOrCreate(['name' => 'Pengelola', 'guard_name' => 'web']);
        $pengelola->syncPermissions(
            Permission::whereNotIn('name', $superadminOnlyPermissions)->get()
        );
    }
}
