<?php

namespace App\Actions\Auth;

use App\Exceptions\CustomException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginAction
{
    /**
     * Attempt to login user.
     *
     * @throws \Exception
     */
    public function execute(Request $request): User
    {
        $userField = filter_var($request->input('user'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $credentials = [
            $userField => $request->input('user'),
            'password' => $request->input('password')
        ];

        $remember = $request->boolean('remember');

        if (!Auth::attempt($credentials, $remember)) {
            throw new CustomException('Username atau Password salah', 401);
        }

        $user = Auth::user();

        if ($request->filled('device_token')) {
            $user->deviceTokens()->create([
                'device_token' => $request->input('device_token'),
                'platform' => $request->input('platform'),
            ]);
        }

        if ($user->hasRole('Penyewa')) {
            $user->load('tenant');
        }

        return $user;
    }
}
