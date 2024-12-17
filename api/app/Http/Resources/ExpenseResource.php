<?php

namespace App\Http\Resources;


use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            "designation"=> $this->designation,
            "description"=> $this->description ?? '--',
            "value"=> $this->value,
            "date"=> Carbon::parse($this->created_at)->format('Y-m-d'),
        ];
    }
}
