<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SongResource extends JsonResource
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
            'choir_id' => $this->choir_id,
            'user_id' => $this->user_id,
            'category' => $this->category,
            'title' => $this->title,
            'audio_url' => $this->audio_url,
            'created_at' => $this->created_at,
        ];
    }
}