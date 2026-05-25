<?php

namespace App\Http\Resources\Owner;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OwnerResource extends JsonResource
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
            'boarding_house_count' => $this->boarding_houses_count,
            'owner_details' => $this->whenLoaded('owner', function () {
                return [
                    'bank_name' => $this->owner->bank_name,
                    'bank_account_number' => $this->owner->bank_account_number,
                    'bank_account_name' => $this->owner->bank_account_name,
                ];
            })
        ];
    }
}
