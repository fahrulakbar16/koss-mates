<?php

namespace App\Actions\Auth;

use App\Exceptions\CustomException;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class LoginGoogle
{
    public function execute($data)
    {
        $apiKey = config('services.firebase.api_key');

        // Kirim ID token ke Firebase REST API untuk verifikasi
        $response = Http::post("https://identitytoolkit.googleapis.com/v1/accounts:lookup?key={$apiKey}", [
            'idToken' => $data['token']
        ]);

        if ($response->failed()) {
            Log::info($response->body());
            throw new CustomException('Token Firebase tidak valid', 401);
        }

        $firebaseUser = $response->json()['users'][0];

        $uid = $firebaseUser['localId'];
        $email = $firebaseUser['email'] ?? null;
        $name = $firebaseUser['displayName'] ?? 'User';
        $photoUrl = $firebaseUser['photoUrl'] ?? null;

        $user = User::where('email', $email)
            ->first();

        if (!$user) {
            $baseUsername = Str::slug($name);
            if (empty($baseUsername)) {
                $baseUsername = $email ? explode('@', $email)[0] : 'user';
                $baseUsername = Str::slug($baseUsername) ?: 'user';
            }

            $username = $baseUsername;
            $counter = 1;
            
            while (User::where('username', $username)->exists()) {
                $username = $baseUsername . $counter;
                $counter++;
            }

            $user = User::create([
                'name' => $name,
                'username' => $username,
                'email' => $email,
                'google_id' => $uid,
                'photo_url' => $photoUrl,
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
                'active_at' => now()
            ]);

            $user->assignRole('Penyewa');


            if (!empty($data['device_token'])) {
                $user->deviceTokens()->create([
                    'device_token' => $data['device_token'],
                    'platform' => $data['platform'] ?? 'web',
                ]);
            }
        }

        if (!$user->email_verified_at) {
            $user->email_verified_at = now();
            $user->save();
        }


        if ($user->hasRole('Penyewa')) {
            $user->load('tenant');
        }

        return $user;
    }
}
