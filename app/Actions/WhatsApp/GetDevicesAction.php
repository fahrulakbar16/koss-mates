<?php

namespace App\Actions\WhatsApp;

use App\Services\FonnteApiService;

class GetDevicesAction
{
    public function __construct(
        protected FonnteApiService $fonnteService
    ) {}

    /**
     * Execute the action
     */
    public function execute(): array
    {
        return $this->fonnteService->getDevices();
    }
}
