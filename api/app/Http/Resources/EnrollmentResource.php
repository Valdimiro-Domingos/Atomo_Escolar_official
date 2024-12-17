<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Resources\Json\JsonResource;

class EnrollmentResource extends JsonResource
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
            'enrollment_number' => $this->enrollment_number,
            'data_de_emissao' => $this->created_at,
            'paid' => $this->paid,
            'student' => StudentResource::make($this->student),
            'school_year' => SchoolYearResource::make($this->school_year),
            'classe' => ClasseResource::make($this->classe),
            'turma' => TurmaResource::make($this->turma),
            'course' => CourseResource::make($this->course),
            'class_room' => ClassRoomResource::make($this->class_room),
            'period' => PeriodResource::make($this->period),
            'student' => StudentResource::make($this->student)
        ];
    }
}
