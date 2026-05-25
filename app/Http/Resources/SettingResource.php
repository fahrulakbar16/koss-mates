<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'key' => $this->key,
            'value' => $this->value, // Keep raw value for editing
            'type' => $this->type,
            'group' => $this->group,
            'description' => $this->description,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

        // Add formatted URL for images
        if ($this->type === 'image' && $this->value) {
            $data['value_url'] = asset('storage/' . $this->value);
        }

        return $data;
    }
}

