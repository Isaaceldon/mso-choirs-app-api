<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChoirResource extends JsonResource
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
            'members' => $this->members,
            'tabernacle_id' => $this->tabernacle_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            // Vous pouvez ajouter d'autres attributs selon vos besoins
        ];
    }
}