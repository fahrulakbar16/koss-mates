<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $modules = [
            // Menu Utama
            'Dashboard' => [
                'key' => 'dashboard',
                'actions' => ['view'],
            ],

            // Properti
            'Cluster' => [
                'key' => 'clusters',
                'actions' => ['view', 'create', 'edit', 'delete'],
            ],
            'Boarding House' => [
                'key' => 'boarding_houses',
                'actions' => ['view', 'create', 'edit', 'delete'],
            ],
            'Room' => [
                'key' => 'rooms',
                'actions' => ['view', 'create', 'edit', 'delete'],
            ],

            // Konfigurasi
            'Daftar Pengguna' => [
                'key' => 'users',
                'actions' => ['view', 'create', 'edit', 'delete'],
            ],
            'Jabatan & Akses' => [
                'key' => 'roles',
                'actions' => ['view', 'create', 'edit', 'delete'],
            ],
            'Pengaturan Website' => [
                'key' => 'settings',
                'actions' => ['view', 'edit'],
            ],
            'WhatsApp' => [
                'key' => 'whatsapp_config',
                'actions' => ['view', 'edit'],
            ],
            'Checkin Requests' => [
                'key' => 'checkin_requests',
                'actions' => ['view', 'edit'],
            ],
        ];

        $labels = [
            'view'    => 'Lihat Data',
            'create'  => 'Tambah Data',
            'edit'    => 'Edit Data',
            'delete'  => 'Hapus Data',
        ];

        foreach ($modules as $groupName => $module) {
            foreach ($module['actions'] as $action) {
                Permission::firstOrCreate(
                    [
                        'name' => "{$module['key']}.{$action}",
                        'guard_name' => 'web',
                    ],
                    [
                        'group_name'   => $groupName,
                        'display_name' => "{$labels[$action]}",
                    ]
                );
            }
        }
    }
}
