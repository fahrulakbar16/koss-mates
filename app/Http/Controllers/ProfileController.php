<?php

namespace App\Http\Controllers;

use App\Actions\Profile\GetUserProfileAction;
use App\Actions\Profile\UpdateTenantProfileAction;
use App\Actions\Profile\VerifyTenantPhoneOtpAction;
use App\Actions\Profile\SendTenantPhoneOtpAction;
use App\Http\Requests\Profile\UpdateTenantProfileRequest;
use App\Http\Requests\Profile\VerifyTenantPhoneOtpRequest;
use App\Http\Requests\Profile\SendTenantPhoneOtpRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Exceptions\CustomException;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public static function middleware(): array
    {
        return [
            'auth:sanctum',
        ];
    }

    public function showDetail()
    {
        $user = app(GetUserProfileAction::class)->execute();

        return Inertia::render('Profile/Show', [
            // Props expected by Profile/Show.vue
            'confirmsTwoFactorAuthentication' => false,
            'sessions' => [],
        ]);
    }

    public function edit(Request $request)
    {
        $user = app(GetUserProfileAction::class)->execute();

        return Inertia::render('Profile/Edit', [
            'user' => (new UserResource($user))->resolve(),
        ]);
    }

    public function apiShow(Request $request)
    {
        $user = app(GetUserProfileAction::class)->execute();

        return response()->json([
            'success' => true,
            'data' => (new UserResource($user))->resolve(),
        ]);
    }

    public function updateTenant(UpdateTenantProfileRequest $request)
    {
        $user = $request->user();
        $result = app(UpdateTenantProfileAction::class)->execute($user, $request->validated());

        if ($result['status'] === 'otp_sent') {
            return redirect()->route('profile.tenant.verify-otp.show')->with('success', $result['message']);
        }

        return back()->with('success', $result['message']);
    }

    public function showVerifyOtp()
    {
        return Inertia::render('Profile/VerifyOtp');
    }

    public function sendTenantPhoneOtp(SendTenantPhoneOtpRequest $request)
    {
        $user = $request->user();
        $phoneInput = $request->input('phone');

        try {
            app(SendTenantPhoneOtpAction::class)->execute($user, $phoneInput);
            return redirect()->route('profile.tenant.verify-otp.show')->with('success', 'Kode OTP telah dikirim ke nomor WhatsApp Anda.');
        } catch (CustomException $e) {
            return back()->withErrors(['phone' => $e->getMessage()]);
        } catch (\Exception $e) {
            return back()->withErrors(['phone' => 'Terjadi kesalahan sistem: ' . $e->getMessage()]);
        }
    }

    public function verifyTenantPhoneOtp(VerifyTenantPhoneOtpRequest $request)
    {
        app(VerifyTenantPhoneOtpAction::class)->execute($request->user(), $request->input('otp'));

        return redirect()->route('profile.show')->with('success', 'Nomor telepon berhasil diverifikasi dan biodata diperbarui.');
    }
}
