<?php

namespace App\Actions\Tenant;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Helpers\LogActivityHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UpdateTenantAccount
{
    public function execute(User $user, array $data): User
    {
        return DB::transaction(function () use ($user, $data) {
            // Update user
            $userData = [
                'name' => $data['name'],
                'username' => $data['username'],
                'email' => $data['email'],
            ];

            // Only update password if provided
            if (!empty($data['password'])) {
                $userData['password'] = Hash::make($data['password']);
            }

            $user->update($userData);


            if ($data['gender'] == "P"){
                $data['gender'] = "female";
            } else {
                $data['gender'] = "male";
            }
            // Update tenant details
            $user->tenant()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'phone' => $data['phone'],
                    'address' => $data['address'] ?? null,
                    'id_card_number' => $data['id_card_number'] ?? null,
                    'birth_date' => $data['birth_date'] ?? null,
                    'gender' => $data['gender'] ?? null,
                    'emergency_contact' => $data['emergency_contact'] ?? null,
                    'is_moved' => $data['is_moved'] ?? false,
                ]
            );

            if (isset($data['file_ktp'])) {
                if ($user->tenant->file_ktp) {
                    Storage::disk('public')->delete($user->tenant->file_ktp);
                }
                $user->tenant->update([
                    'file_ktp' => $data['file_ktp']->store('ktp', 'public')
                ]);
            }

            LogActivityHelper::addToLog('Memperbarui akun penyewa: ' . $user->name, [
                'id' => $user->id,
                'name' => $user->name,
            ]);

            return $user->fresh(['tenant']);
        });
    }
}
