<?php

namespace App\Http\Resources\BoardingHouse;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class BoardingHouseResource extends JsonResource
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
            'number' => $this->number,
            'address' => $this->address,
            'description' => $this->description,
            'rooms_count' => $this->rooms_count,
            'gender' => $this->gender,
            'persentasi_pemilik' => $this->persentasi_pemilik,
            'rooms_available_count' => $this->rooms_available_count,
            'cluster' => new ClusterResource($this->cluster),
            'thumbnail' => $this->thumbnail ? asset('storage/' . $this->thumbnail) : null,
            'price' => $this->rooms->flatMap(function ($room) {
                return $room->prices->pluck('price');
            })->min() ?? 0,
            'distance' => $this->when(isset($this->distance), $this->distance),
        ];
    }
}
