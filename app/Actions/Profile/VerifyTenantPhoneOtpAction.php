<?php

namespace App\Actions\Profile;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;

class VerifyTenantPhoneOtpAction
{
    /**
     * Verify OTP and update tenant profile.
     *
     * @param User $user
     * @param string $otp
     * @return void
     * @throws ValidationException
     */
    public function execute(User $user, string $otp): void
    {
        $cacheKey = 'otp_verify_phone_' . $user->id;
        $cachedData = Cache::get($cacheKey);

        $data = $cachedData;

        if (!$cachedData || $cachedData['otp'] != $otp) {
            throw ValidationException::withMessages([
                'otp' => 'Kode OTP tidak valid atau sudah kadaluarsa.'
            ]);
        }

        // OTP Valid, proceed with update
        $user->tenant()->updateOrCreate(
            ['user_id' => $user->id],
            $data + ['phone_verified_at' => now()]
        );

        // Clear cache
        Cache::forget($cacheKey);
    }
}
