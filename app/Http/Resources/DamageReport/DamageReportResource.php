<?php

namespace App\Http\Resources\DamageReport;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DamageReportResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'photo' => $this->photo ? asset('storage/' . $this->photo) : null,
            'photo_path' => $this->photo,
            'status' => $this->status,
            'status_label' => $this->getStatusLabel(),
            'admin_notes' => $this->admin_notes,
            'user_room_id' => $this->user_room_id,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'created_at_formatted' => $this->created_at?->translatedFormat('d F Y, H:i'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),

            // Relationships (when loaded)
            'user_room' => $this->whenLoaded('userRoom'),
            'user' => $this->whenLoaded('user'),
        ];
    }

    /**
     * Get status label in Indonesian.
     *
     * @return string
     */
    private function getStatusLabel(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu',
            'in_progress' => 'Sedang Diproses',
            'resolved' => 'Selesai',
            'rejected' => 'Ditolak',
            default => $this->status,
        };
    }
}
