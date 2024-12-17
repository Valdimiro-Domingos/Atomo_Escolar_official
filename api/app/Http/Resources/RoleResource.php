<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
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
            'role' => $this->role,
            'guard' => $this->guard,
            'created_at' => substr(strval($this->created_at),0,11),
            'updated_at' => substr(strval($this->updated_at),0,11),
        ];
    }
}
