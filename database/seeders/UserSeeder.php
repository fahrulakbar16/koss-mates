<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Superadmin user
        $superadmin = User::firstOrCreate(
            ['email' => 'superadmin@gmail.com'],
            [
                'name' => 'Superadmin',
                'username' => 'superadmin',
                'password' => Hash::make('123123')
            ]
        );
        $superadmin->syncRoles('Superadmin');

        // Create Penyewa user
        $penyewa = User::firstOrCreate(
            ['email' => 'penyewa@gmail.com'],
            [
                'name' => 'Penyewa',
                'username' => 'penyewa',
                'password' => Hash::make('123123'),
            ]
        );
        $penyewa->syncRoles('Penyewa');

        // Create Tenant data for Penyewa
        Tenant::firstOrCreate(
            ['user_id' => $penyewa->id],
            [
                'phone' => '081234567890',
                'address' => 'Jl. Contoh No. 123, Jakarta',
                'id_card_number' => '3201010101900001',
                'birth_date' => '1990-01-01',
                'gender' => 'male',
                'emergency_contact' => '081987654321',
            ]
        );

        // Create Pemilik user
        $pemilik = User::firstOrCreate(
            ['email' => 'pemilik@gmail.com'],
            [
                'name' => 'Pemilik',
                'username' => 'pemilik',
                'password' => Hash::make('123123'),
            ]
        );
        $pemilik->syncRoles('Pemilik');

        // Create Pengelola user
        $pengelola = User::firstOrCreate(
            ['email' => 'pengelola@gmail.com'],
            [
                'name' => 'Pengelola',
                'username' => 'pengelola',
                'password' => Hash::make('123123'),
            ]
        );
        $pengelola->syncRoles('Pengelola');
    }
}
