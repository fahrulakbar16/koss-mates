<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TenantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'phone' => $this->tenant?->phone,
            'gender' => $this->tenant?->gender,
            'room_name' => $this->rooms?->first()?->room?->name,
            'boarding_house_name' => $this->rooms->first()?->room?->boardingHouse?->name,
            'is_moved' => (bool) $this->tenant?->is_moved,
            'is_has_pending_transactions' => $this->pending_transactions_count > 0 ? true : false,
            'cluster' => $this->rooms->first()?->room?->boardingHouse?->cluster,
            'room_status' => $this->rooms->first()?->status,
        ];
    }
}
