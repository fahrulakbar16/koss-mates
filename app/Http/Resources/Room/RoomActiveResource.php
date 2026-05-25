<?php

namespace App\Http\Resources\Room;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomActiveResource extends JsonResource
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
            'number' => $this->room->boardingHouse->number,
            'name' => $this->room->boardingHouse->name,
            'address' => $this->room->boardingHouse->address,
            'phone' => $this->room->boardingHouse->phone,
            'images' => $this->room->boardingHouse->images,
            'owner' => $this->room?->boardingHouse?->owner,
            'room' => $this->room,
            'is_moved' => $this->is_moved,
            'plan' => $this->plan,
            'start_date' => $this->start_date ? Carbon::parse($this->start_date)->format('Y-m-d') : null,
            'end_date' => $this->end_date ? Carbon::parse($this->end_date)->format('Y-m-d') : null,
            'status' => $this->status,
            'foto_kamar' => $this->foto_kamar,
            'verifikasi_admin' => $this->verifikasi_admin,
            'transactions' => $this->transactions,
        ];
    }
}
