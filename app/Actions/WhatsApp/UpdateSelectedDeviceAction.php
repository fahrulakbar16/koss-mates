<?php

namespace App\Actions\WhatsApp;

use App\Models\Setting;
use App\Helpers\LogActivityHelper;
use Illuminate\Support\Facades\Log;

class UpdateSelectedDeviceAction
{
    /**
     * Execute the action to update selected device
     */
    public function execute(string $deviceToken, string $deviceNumber): array
    {
        try {
            // Update or create the selected device setting
            Setting::updateOrCreate(
                ['key' => 'whatsapp_device_token'],
                ['value' => $deviceToken]
            );

            Setting::updateOrCreate(
                ['key' => 'whatsapp_device_number'],
                ['value' => $deviceNumber]
            );

            LogActivityHelper::addToLog('Memperbarui perangkat WhatsApp: ' . $deviceNumber, [
                'device_token' => $deviceToken,
                'device_number' => $deviceNumber,
            ]);

            return [
                'success' => true,
                'message' => 'Perangkat berhasil dipilih',
            ];
        } catch (\Exception $e) {
            \Log::error('Failed to update selected device: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Gagal memilih perangkat',
                'error' => $e->getMessage(),
            ];
        }
    }
}
