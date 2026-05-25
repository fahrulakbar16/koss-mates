<?php

namespace App\Actions\WhatsApp;

use App\Services\FonnteApiService;
use App\Helpers\LogActivityHelper;

class DisconnectDeviceAction
{
    public function __construct(
        protected FonnteApiService $fonnteService
    ) {}

    /**
     * Execute the action
     */
    public function execute(?string $deviceToken = null): array
    {
        $result = $this->fonnteService->disconnectDevice($deviceToken);

        if ($result['status'] ?? false) {
            LogActivityHelper::addToLog('Memutuskan perangkat WhatsApp', [
                'device_token' => $deviceToken,
            ]);
        }

        return $result;
    }
}
