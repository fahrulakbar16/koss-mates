<?php

namespace App\Actions\Profile;

use App\Models\User;
use App\Services\FonnteApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class UpdateTenantProfileAction
{
    protected $fonnteService;

    public function __construct(FonnteApiService $fonnteService)
    {
        $this->fonnteService = $fonnteService;
    }

    /**
     * Update tenant profile.
     *
     * @param User $user
     * @param array $data Validated data from request
     * @return array Result of the action ['status' => 'otp_sent'|'success', 'message' => string]
     */
    public function execute(User $user, array $data): array
    {
        $currentPhone = $user->tenant?->phone;
        $newPhone = $data['phone'] ?? null;

        if (isset($data['file_ktp']) && $data['file_ktp'] instanceof UploadedFile) {
            if ($user->tenant?->file_ktp) {
                Storage::disk('public')->delete($user->tenant->file_ktp);
            }
            $data['file_ktp'] = $data['file_ktp']->store('ktp', 'public');
        }

        // If phone number is changing, require OTP
        if ($newPhone && $newPhone !== $currentPhone) {
            $otp = rand(100000, 999999);
            $cacheKey = 'otp_verify_phone_' . $user->id;

            // Cache the validated data and OTP for 5 minutes
            Cache::put($cacheKey, [
                'otp' => $otp,
                'phone' => $newPhone,
                'data' => $data
            ], now()->addMinutes(5));

            // Send OTP via WhatsApp
            $message = "Kode OTP untuk perubahan nomor telepon Anda adalah: *{$otp}*.\n\nJangan berikan kode ini kepada siapapun.";
            $this->fonnteService->sendMessage($newPhone, $message);

            return [
                'status' => 'otp_sent',
                'message' => 'Kode OTP telah dikirim ke nomor WhatsApp baru Anda.'
            ];
        }

        $user->tenant()->updateOrCreate(
            ['user_id' => $user->id],
            $data
        );

        return [
            'status' => 'success',
            'message' => 'Biodata penyewa berhasil diperbarui.'
        ];
    }
}
