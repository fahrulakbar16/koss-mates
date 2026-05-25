<?php

namespace App\Actions\Profile;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;

class VerifyApiTenantPhoneOtpAction
{
    public function execute(User $user, string $otp): User
    {
        $cacheKeyUpdate = 'otp_update_phone_' . $user->id;
        $cacheKeyVerify = 'otp_verify_phone_' . $user->id;

        if (Cache::has($cacheKeyUpdate)) {
            app(VerifyTenantPhoneOtpAction::class)->execute($user, $otp);
            return $user->load('tenant');
        } elseif (Cache::has($cacheKeyVerify)) {
            $cachedData = Cache::get($cacheKeyVerify);

            // Check if cached data is array (new format) or just OTP string (old format fallback)
            $cachedOtp = is_array($cachedData) ? $cachedData['otp'] : $cachedData;
            $newPhone = is_array($cachedData) ? $cachedData['phone'] : $user->tenant?->phone;

            if ($cachedOtp != $otp) {
                throw ValidationException::withMessages([
                    'otp' => 'Kode OTP tidak valid atau sudah kadaluarsa.'
                ]);
            }

            $user->tenant()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'phone' => $newPhone,
                    'phone_verified_at' => now()
                ]
            );

            Cache::forget($cacheKeyVerify);

            return $user->load('tenant');
        }

        throw ValidationException::withMessages([
            'otp' => 'Kode OTP tidak valid atau sudah kadaluarsa.'
        ]);
    }
}
