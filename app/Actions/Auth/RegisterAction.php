<?php

namespace App\Actions\Auth;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterAction
{
    /**
     * Register a new user and assign Penyewa role.
     *
     * @param Request $request
     * @return User
     */
    public function execute(Request $request): User
    {
        $user = User::create([
            'name' => $request->input('name'),
            'username' => $request->input('username'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'status' => 'active',
        ]);

        $user->tenant()->create([
            'phone' => $request->input('phone'),
            'address' => $request->input('address'),
            'id_card_number' => $request->input('id_card_number'),
            'birth_date' => $request->input('birth_date'),
            'gender' => $request->input('gender'),
            'emergency_contact' => $request->input('emergency_contact'),
        ]);

        // Assign Penyewa role
        $user->assignRole('Penyewa');

        if ($request->has('device_token') && $request->has('platform')) {
            $user->deviceTokens()->create([
                'device_token' => $request->input('device_token'),
                'platform' => $request->input('platform'),
            ]);
        }

        return $user;
    }
}
