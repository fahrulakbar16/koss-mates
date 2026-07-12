<?php

namespace App\Actions\Tenant;

use App\Models\User;
use App\Models\Tenant;
use Illuminate\Support\Facades\Hash;
use App\Helpers\LogActivityHelper;
use Illuminate\Support\Facades\DB;

class CreateTenantAccount
{
    public function execute(array $data): User
    {
        return DB::transaction(function () use ($data) {
            // Create user
            $user = User::create([
                'name' => $data['name'],
                'username' => $data['username'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'active_at' => now(),
            ]);

            // Assign "Penyewa" role
            $user->assignRole('Penyewa');

            if ($data['gender'] == 'L') {
                $data['gender'] = 'Male';
            } elseif ($data['gender'] == 'P') {
                $data['gender'] = 'Female';
            }
            // Create tenant details
            Tenant::create([
                'user_id' => $user->id,
                'phone' => $data['phone'],
                'address' => $data['address'] ?? null,
                'id_card_number' => $data['id_card_number'] ?? null,
                'birth_date' => $data['birth_date'] ?? null,
                'gender' => $data['gender'] ?? null,
                'emergency_contact' => $data['emergency_contact'] ?? null,
                'tempat_kuliah_kerja' => $data['tempat_kuliah_kerja'] ?? null,
            ]);

            LogActivityHelper::addToLog('Membuat akun penyewa: ' . $user->name, [
                'id' => $user->id,
                'name' => $user->name,
            ]);

            return $user->load('tenant');
        });
    }
}
