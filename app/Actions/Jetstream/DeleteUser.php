<?php

namespace App\Actions\Jetstream;

use App\Models\User;
use App\Helpers\LogActivityHelper;
use Laravel\Jetstream\Contracts\DeletesUsers;

class DeleteUser implements DeletesUsers
{
    /**
     * Delete the given user.
     */
    public function delete(User $user): void
    {
        $user->deleteProfilePhoto();
        $user->tokens->each->delete();
        $userName = $user->name;
        $userId = $user->id;
        $user->delete();

        LogActivityHelper::addToLog('Menghapus akun pengguna (via Jetstream): ' . $userName, [
            'id' => $userId,
            'name' => $userName,
        ]);
    }
}
