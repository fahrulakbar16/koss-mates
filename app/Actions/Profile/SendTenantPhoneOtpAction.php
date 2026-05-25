<?php

namespace App\Actions\Profile;

use App\Models\User;
use App\Services\FonnteApiService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use App\Exceptions\CustomException;

class SendTenantPhoneOtpAction
{
    protected $fonnteService;

    public function __construct(FonnteApiService $fonnteService)
    {
        $this->fonnteService = $fonnteService;
    }

    public function execute(User $user, string $phoneInput): void
    {
        if (!$user->hasRole('Penyewa')) {
            throw new CustomException('Hanya penyewa yang dapat melakukan aksi ini.', 403);
        }

        $tenant = $user->tenant;

        // if ($tenant && $tenant->phone === $phoneInput && $tenant->phone_verified_at) {
        //     throw new CustomException('Nomor telepon ini sudah diverifikasi sebelumnya.', 400);
        // }

        $throttleKey = 'send-otp-' . $user->id;

        if (RateLimiter::tooManyAttempts($throttleKey, 1)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            throw new CustomException('Harap tunggu ' . $seconds . ' detik sebelum meminta OTP baru.', 429);
        }

        $otp = rand(100000, 999999);
        $cacheKey = 'otp_verify_phone_' . $user->id;

        // dd($cacheKey);
        Cache::put($cacheKey, [
            'otp' => $otp,
            'phone' => $phoneInput,
        ], now()->addMinutes(5));

        $message = "Kode OTP untuk verifikasi nomor telepon Anda adalah: *{$otp}*.\n\nJangan berikan kode ini kepada siapapun.";

        try {
            $this->fonnteService->sendMessage($phoneInput, $message);
        } catch (\Exception $e) {
            throw new CustomException('Gagal mengirim OTP: ' . $e->getMessage(), 500);
        }

        RateLimiter::hit($throttleKey, 300);
    }
}
