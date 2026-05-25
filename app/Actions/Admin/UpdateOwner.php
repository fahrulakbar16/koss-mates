<?php

namespace App\Actions\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UpdateOwner
{
    /**
     * Update an existing owner.
     *
     * @param  \App\Models\User  $owner
     * @param  array  $data
     * @return \App\Models\User
     */
    public function execute(User $owner, array $data): User
    {
        return DB::transaction(function () use ($owner, $data) {
            $owner->update([
                'name' => $data['name'],
                'username' => $data['username'],
                'email' => $data['email'],
            ]);

            if (!empty($data['password'])) {
                $owner->update([
                    'password' => Hash::make($data['password']),
                ]);
            }

            $owner->owner()->updateOrCreate(
                ['user_id' => $owner->id],
                [
                    'bank_name' => $data['bank_name'] ?? null,
                    'bank_account_number' => $data['bank_account_number'] ?? null,
                    'bank_account_name' => $data['bank_account_name'] ?? null,
                ]
            );

            return $owner;
        });
    }
}
