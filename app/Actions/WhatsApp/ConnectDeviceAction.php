<?php

namespace App\Actions\WhatsApp;

use App\Services\FonnteApiService;
use App\Helpers\LogActivityHelper;

class ConnectDeviceAction
{
    public function __construct(
        protected FonnteApiService $fonnteService
    ) {}

    /**
     * Execute the action
     */
    public function execute(?string $deviceToken = null): array
    {
        $result = $this->fonnteService->connectDevice($deviceToken);

        if ($result['status'] ?? false) {
            LogActivityHelper::addToLog('Menghubungkan perangkat WhatsApp', [
                'device_token' => $deviceToken,
            ]);
        }

        return $result;
    }
}
