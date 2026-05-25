<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WhatsAppDeviceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Handle both array and object data
        $data = is_array($this->resource) ? $this->resource : (array) $this->resource;

        return [
            'device_name' => $data['device'] ?? $data['name'] ?? null,
            'phone_number' => $data['phone'] ?? $data['number'] ?? null,
            'status' => $data['status'] ?? 'unknown',
            'connected' => ($data['status'] ?? '') === 'connected',
            'qr_code' => $data['qr'] ?? $data['qr_code'] ?? null,
            'message' => $data['message'] ?? null,
            'raw_data' => $data,
        ];
    }
}
