<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GradeScheduleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return[
            'id' => $this->id,
            'student' => StudentResource::make($this->student),
            'quarterly_average' => $this->quarterly_average,
            'quarterly_test_score' => $this->quarterly_test_score,
            'teachers_test_score' => $this->teachers_test_score,
            'continuous_evaluation_average' => $this->continuous_evaluation_average,
            // 'mini_schedule' => MiniScheduleResource::make($this->mini_schedule), 
        ];
    }
}
