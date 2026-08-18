<?php

namespace App\Http\Controllers\Api\v1;

use App\Actions\Profile\UpdateProfileAction;
use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Http\Requests\Profile\VerifyTenantPhoneOtpRequest;
use App\Http\Requests\Profile\SendTenantPhoneOtpRequest;
use App\Http\Requests\Profile\ApiUpdatePasswordRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Helpers\LogActivityHelper;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    use \App\Traits\ApiResponse;

    /**
     * Mengambil data profil user yang sedang login.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->hasRole('Penyewa')) {
            $user->load('tenant');
        }

        return $this->apiResponse(new UserResource($user), 'Profil data berhasil diambil');
    }

    public function show($id)
    {
        $user = User::findOrFail($id);

        if ($user->hasRole('Penyewa')) {
            $user->load('tenant');
        }

        return $this->apiResponse(new UserResource($user), 'Profil data berhasil diambil');
    }

    public function update(UpdateProfileRequest $request)
    {
        $user = $request->user();
        if (!$user) {
            throw new CustomException('Unauthenticated', 401);
        }

        $updatedUser = app(UpdateProfileAction::class)->execute($user, $request);

        return $this->apiResponse(new UserResource($updatedUser), 'Profil berhasil diperbarui');
    }

    public function sendTenantPhoneOtp(SendTenantPhoneOtpRequest $request)
    {
        $user = $request->user();
        $phoneInput = $request->input('phone');

        try {
            app(\App\Actions\Profile\SendTenantPhoneOtpAction::class)->execute($user, $phoneInput);
            return $this->apiResponse(null, 'Kode OTP telah dikirim ke nomor WhatsApp Anda.', 'success', 200);
        } catch (CustomException $e) {
            return $this->apiResponse(null, $e->getMessage(), 'error', $e->getCode() ?: 400);
        } catch (\Exception $e) {
            return $this->apiResponse(null, 'Terjadi kesalahan sistem: ' . $e->getMessage(), 'error', 500);
        }
    }

    public function verifyTenantPhoneOtp(VerifyTenantPhoneOtpRequest $request)
    {
        $user = $request->user();
        $otp = $request->input('otp');

        try {
            $updatedUser = app(\App\Actions\Profile\VerifyApiTenantPhoneOtpAction::class)->execute($user, $otp);
            return $this->apiResponse(new UserResource($updatedUser), 'Nomor telepon berhasil diverifikasi.', 'success', 200);
        } catch (ValidationException $e) {
            $message = collect($e->errors())->flatten()->first() ?: $e->getMessage();
            return $this->apiResponse(null, $message, 'error', 400);
        } catch (\Exception $e) {
            return $this->apiResponse(null, 'Terjadi kesalahan sistem: ' . $e->getMessage(), 'error', 500);
        }
    }

    public function updatePassword(ApiUpdatePasswordRequest $request)
    {
        $user = $request->user();

        $user->forceFill([
            'password' => Hash::make($request->input('password')),
        ])->save();

        LogActivityHelper::addToLog('Memperbarui kata sandi: ' . $user->name, [
            'id' => $user->id,
            'name' => $user->name,
        ]);

        return $this->apiResponse(null, 'Kata sandi berhasil diperbarui.', 'success', 200);
    }
}
