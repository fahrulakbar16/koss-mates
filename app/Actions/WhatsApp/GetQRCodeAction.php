<?php

namespace App\Actions\WhatsApp;

use App\Services\FonnteApiService;

class GetQRCodeAction
{
    public function __construct(
        protected FonnteApiService $fonnteService
    ) {}

    /**
     * Execute the action
     */
    public function execute(?string $deviceToken = null): array
    {
        return $this->fonnteService->getQRCode($deviceToken);
    }
}
