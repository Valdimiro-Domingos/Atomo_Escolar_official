<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"=> $this->id,
            "designation"=> $this->designation,
            "description"=> $this->description,
            "file"=> $this->file,
            "school_year"=> SchoolYearResource::make($this->school_year), 
            "classe"=> ClasseResource::make($this->classe),
            "turma"=> TurmaResource::make($this->turma),
            "course"=> CourseResource::make($this->course),
            "class_room"=> ClassRoomResource::make($this->class_room),
            "period"=> PeriodResource::make($this->period),

        ];
    }
}
