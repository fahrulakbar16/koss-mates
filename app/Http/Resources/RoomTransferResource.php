<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomTransferResource extends JsonResource
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
            'reason' => $this->reason,
            'plan_date' => $this->plan_date,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // Relationships (conditional or always loaded depending on controller)
            'user_room' => $this->whenLoaded('userRoom'),
            'room' => $this->whenLoaded('room'), // Destination

            // Flattened info for table ease
            'tenant_name' => $this->userRoom->user->name ?? 'Unknown',
            'current_room_name' => $this->userRoom->room->name ?? 'Unknown',
            'current_room_boarding_house_name' => $this->userRoom->room->boardingHouse->name ?? 'Unknown',
            'destination_room_name' => $this->room->name ?? 'Unknown',
            'boarding_house_name' => $this->room->boardingHouse->name ?? 'Unknown',
        ];
    }
}
