<?php

namespace App\Http\Resources\Admin\FinancialReport;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $roomCategory = $this->prices->first() ?
            'KAMAR ' . strtoupper($this->name) . ' (' . $this->prices->first()->duration . ' BULAN)' :
            'KAMAR ' . strtoupper($this->name);

        $statusBadge = match ($this->status) {
            'occupied' => 'TERISI',
            'booked' => 'DIPESAN',
            'maintenance' => 'PERAWATAN',
            default => 'TERSEDIA',
        };

        return [
            'id' => $this->id,
            'name' => strtoupper($this->name),
            'category' => $roomCategory,
            'status' => $statusBadge,
            'status_color' => $this->status === 'occupied' ? 'green' : 'gray',
        ];
    }
}
