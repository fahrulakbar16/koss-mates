<?php

namespace App\Http\Resources\RoomPrice;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomPriceResource extends JsonResource
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
            'room_id' => $this->room_id,
            'duration' => $this->duration,
            'price' => $this->price,
            'addons' => $this->addons,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
