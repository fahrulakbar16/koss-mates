<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LogActivityResource extends JsonResource
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
            'subject' => $this->subject,
            'url' => $this->url,
            'method' => $this->method,
            'ip' => $this->ip,
            'agent' => $this->agent,
            'user' => [
                'id' => $this->user_id,
                'name' => $this->user ? $this->user->name : 'Guest',
            ],
            'properties' => $this->properties,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'human_time' => $this->created_at->diffForHumans(),
        ];
    }
}
