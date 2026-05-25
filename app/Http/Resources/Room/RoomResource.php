<?php

namespace App\Http\Resources\Room;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
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
            'number' => $this->room?->boardingHouse?->number,
            'boarding_house_id' => $this->boarding_house_id,
            'name' => $this->name,
            'number' => $this->number,
            'description' => $this->description,
            'capacity' => $this->capacity,
            'status' => $this->status,
            'min_price' => $this->min_price ?? $this->min_price, // Added by GetRoomsAction or relation
            // Add relations if loaded
            'prices' => $this->whenLoaded('prices'),
            'transactions_count' => $this->whenCounted('transactions'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
