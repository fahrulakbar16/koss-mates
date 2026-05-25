<?php

namespace App\Http\Resources\BoardingHouse;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BoardingHouseDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Calculate minimum price from all rooms
        $minPrice = $this->rooms
            ->flatMap(function ($room) {
                return $room->prices->pluck('price');
            })
            ->filter()
            ->min();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'number' => $this->number,
            'address' => $this->address,
            'description' => $this->description,
            'phone' => $this->phone,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'gender' => $this->gender,
            'persentasi_pemilik' => $this->persentasi_pemilik,
            'total_rooms' => $this->total_rooms,
            'occupied_rooms' => $this->occupied_rooms,
            'occupied_percentage' => $this->total_rooms > 0 ? round((($this->occupied_rooms / $this->total_rooms) * 100), 2) : 0,
            'available_rooms' => $this->total_rooms - $this->occupied_rooms,
            'thumbnail' => $this->thumbnail ? asset('storage/' . $this->thumbnail) : null,
            'images' => $this->images->map(function ($image) {
                return asset('storage/' . $image->image);
            }),
            'cluster' => $this->cluster ? new ClusterResource($this->cluster) : null,
            'owner' => $this->owner ? [
                'id' => $this->owner->id,
                'name' => $this->owner->name,
                'phone' => $this->owner->phone ?? null,
            ] : null,
            'rooms' => $this->rooms->map(function ($room) {
                return [
                    'id' => $room->id,
                    'name' => $room->name,
                    'number' => $room->number,
                    'description' => $room->description,
                    'capacity' => $room->capacity,
                    'status' => $room->status,
                    'facilities' => $room->facilities,
                    'min_price' => $room->getMinPrice(),
                    'prices' => $room->prices->map(function ($price) {
                        return [
                            'id' => $price->id,
                            'duration' => $price->duration,
                            'price' => $price->price,
                            'name' => $price->name,
                            'addons' => $price->addons,
                        ];
                    }),
                ];
            }),
            'min_price' => $minPrice ? (float) $minPrice : 0,
            'rooms_count' => $this->rooms->count(),
        ];
    }
}
