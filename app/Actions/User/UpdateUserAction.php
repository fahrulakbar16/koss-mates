<?php

namespace App\Actions\User;

use App\Models\User;
use App\Helpers\LogActivityHelper;

class UpdateUserAction
{
    /**
     * Update a user.
     */
    public function execute(User $user, array $data): User
    {
        $user->update([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'status' => $data['status'],
        ]);

        // Sync role
        $user->syncRoles([$data['role']]);

        LogActivityHelper::addToLog('Memperbarui pengguna: ' . $user->name, [
            'id' => $user->id,
            'name' => $user->name,
            'role' => $data['role'],
        ]);

        return $user->fresh();
    }
}
