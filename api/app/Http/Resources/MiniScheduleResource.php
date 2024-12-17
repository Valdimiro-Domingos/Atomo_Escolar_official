<?php

namespace App\Http\Resources;

use App\Models\Grade;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MiniScheduleResource extends JsonResource
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
            "schedule"=> ScheduleResource::make($this->schedule),
            "trimestre"=> TrimestreResource::make($this->trimestre),
            "discipline"=> DisciplineResource::make($this->discipline),
            "professor"=> UserResource::make($this->profeessor),
            "file"=> $this->file,
            // "period"=> PeriodResource::make($this->period),
            // "turma"=> TurmaResource::make($this->turma),
            // "school_year"=> SchoolYearResource::make($this->school_year),
            // "course"=> CourseResource::make($this->course),
            // "class_room"=> ClassRoomResource::make($this->class_room),
            // "classe"=> ClasseResource::make($this->classe),
        ];
    }
}
