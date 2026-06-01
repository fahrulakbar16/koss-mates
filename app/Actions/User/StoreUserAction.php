<?php

namespace App\Actions\User;

use App\Models\User;
use App\Helpers\LogActivityHelper;
use Illuminate\Support\Facades\Hash;

class StoreUserAction
{
    /**
     * Store a new user.
     */
    public function execute(array $data): User
    {
        $user = User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make('password'),
        ]);

        // Assign role
        $user->assignRole($data['role']);

        LogActivityHelper::addToLog('Menambah pengguna: ' . $user->name, [
            'id' => $user->id,
            'name' => $user->name,
            'role' => $data['role'],
        ]);

        return $user;
    }
}
