<?php

namespace App\Http\Controllers\Api\v1\Penyewa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Notifications\PenyewaVerifyEmail;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\RateLimiter;
use App\Traits\ApiResponse;

class VerificationController extends Controller
{
    use ApiResponse;

    /**
     * Resend the email verification notification for penyewa API.
     */
    public function resend(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return $this->apiResponse(null, 'Email telah terverifikasi.', 'error', 400);
        }

        $throttleKey = 'resend-verification-' . $user->id;

        if (RateLimiter::tooManyAttempts($throttleKey, 1)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            return $this->apiResponse(null, 'Harap tunggu ' . $seconds . ' detik sebelum meminta tautan verifikasi baru.', 'error', 429);
        }

        $user->notify(new PenyewaVerifyEmail());

        RateLimiter::hit($throttleKey, 300);

        return $this->apiResponse(null, 'Tautan verifikasi telah dikirim ulang ke alamat email Anda.', 'success', 200);
    }
}
