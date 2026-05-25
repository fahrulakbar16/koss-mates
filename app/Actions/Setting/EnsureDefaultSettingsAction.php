<?php

namespace App\Actions\Setting;

use App\Models\Setting;

class EnsureDefaultSettingsAction
{
    /**
     * Ensure default settings exist in the database.
     */
    public function execute(): void
    {
        $defaultSettings = [
            [
                'key' => 'logo_main',
                'value' => null,
                'type' => 'image',
                'group' => 'logo',
                'description' => 'Logo utama website',
            ],
            [
                'key' => 'logo_favicon',
                'value' => null,
                'type' => 'image',
                'group' => 'logo',
                'description' => 'Favicon website',
            ],
            [
                'key' => 'site_name',
                'value' => 'Tharahub',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Nama website',
            ],
            [
                'key' => 'site_description',
                'value' => '',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Deskripsi website',
            ],
            [
                'key' => 'contact_email',
                'value' => '',
                'type' => 'text',
                'group' => 'contact',
                'description' => 'Email kontak',
            ],
            [
                'key' => 'contact_phone',
                'value' => '',
                'type' => 'text',
                'group' => 'contact',
                'description' => 'Nomor telepon kontak',
            ],
            [
                'key' => 'contact_address',
                'value' => '',
                'type' => 'text',
                'group' => 'contact',
                'description' => 'Alamat kontak',
            ],
            [
                'key' => 'color_primary',
                'value' => '#3B82F6',
                'type' => 'text',
                'group' => 'color',
                'description' => 'Warna utama (Primary)',
            ],
            [
                'key' => 'color_secondary',
                'value' => '#8B5CF6',
                'type' => 'text',
                'group' => 'color',
                'description' => 'Warna sekunder (Secondary)',
            ],
            [
                'key' => 'color_accent',
                'value' => '#10B981',
                'type' => 'text',
                'group' => 'color',
                'description' => 'Warna aksen (Accent)',
            ],
            [
                'key' => 'color_success',
                'value' => '#10B981',
                'type' => 'text',
                'group' => 'color',
                'description' => 'Warna sukses',
            ],
            [
                'key' => 'color_warning',
                'value' => '#F59E0B',
                'type' => 'text',
                'group' => 'color',
                'description' => 'Warna peringatan',
            ],
            [
                'key' => 'color_error',
                'value' => '#EF4444',
                'type' => 'text',
                'group' => 'color',
                'description' => 'Warna error',
            ],
            [
                'key' => 'color_info',
                'value' => '#3B82F6',
                'type' => 'text',
                'group' => 'color',
                'description' => 'Warna informasi',
            ],
        ];

        foreach ($defaultSettings as $default) {
            Setting::firstOrCreate(
                ['key' => $default['key']],
                $default
            );
        }
    }
}
