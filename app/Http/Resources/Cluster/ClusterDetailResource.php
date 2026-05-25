<?php

namespace App\Http\Resources\Cluster;

use App\Http\Resources\BoardingHouse\BoardingHouseResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClusterDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "description" => $this->description,
            "address" => $this->address,
            "created_at" => $this->created_at->format('Y-m-d H:i:s'),
            "updated_at" => $this->updated_at->format('Y-m-d H:i:s'),
            "total_rooms" => $this->total_rooms,
            "total_occupied_rooms" => $this->total_occupied_rooms,
            'occupied_percentage' => $this->total_rooms > 0 ? round((($this->total_occupied_rooms / $this->total_rooms) * 100), 2) : 0,
            'boarding_houses' => BoardingHouseResource::collection($this->boardingHouses),
        ];
    }
}
