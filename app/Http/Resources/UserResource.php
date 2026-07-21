<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'username'          => $this->username,
            'email'             => $this->email,
            'profile_photo_url' => (function () {
                // Check if user has uploaded a photo
                if ($this->profile_photo_path) {
                    // Return URL to uploaded photo
                    return url('storage/' . $this->profile_photo_path);
                }

                // Check if profile_photo_url is already a full URL (e.g., from Google login)
                if ($this->profile_photo_url && filter_var($this->profile_photo_url, FILTER_VALIDATE_URL)) {
                    return $this->profile_photo_url;
                }

                // Return default avatar with first letter of name
                $initial = strtoupper(substr($this->name ?? 'U', 0, 1));
                return "https://ui-avatars.com/api/?name={$initial}&color=7F9CF5&background=EBF4FF";
            })(),
            'role'                  => $this->roles->first()?->name ?? '-',
            'last_seen'             => $this->last_seen,
            'tenant'                => $this->whenLoaded('tenant'),
            'phone'                 => $this->whenLoaded('tenant', function () {
                return $this->tenant->phone;
            }),
            'address'               => $this->whenLoaded('tenant', function () {
                return $this->tenant->address;
            }),
            'id_card_number'        => $this->whenLoaded('tenant', function () {
                return $this->tenant->id_card_number;
            }),
            'file_ktp'              => $this->whenLoaded('tenant', function () {
                return $this->tenant->file_ktp;
            }),
            'birth_date'            => $this->whenLoaded('tenant', function () {
                return $this->tenant->birth_date;
            }),
            'gender'                => $this->whenLoaded('tenant', function () {
                return $this->tenant->gender;
            }),
            'emergency_contact'     => $this->whenLoaded('tenant', function () {
                return $this->tenant->emergency_contact;
            }),
            'is_moved'  => $this->whenLoaded('tenant', function () {
                return $this->tenant->is_moved;
            }),
        ];
    }
}
