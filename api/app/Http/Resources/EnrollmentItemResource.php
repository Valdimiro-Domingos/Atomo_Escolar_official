<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Resources\Json\JsonResource;


class EnrollmentItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'id' => $this->student_id,
            'enrollment_number' => $this->enrollment_number,
            'student' => StudentResource::make($this->student)->name,
            'school_year' => SchoolYearResource::make($this->school_year)->designation,
            'classe' => ClasseResource::make($this->classe)->designation,
            'turma' => TurmaResource::make($this->turma)->designation,
            'course' => CourseResource::make($this->course)->designation,
            'class_room' => ClassRoomResource::make($this->class_room)->designation,
            'period' => PeriodResource::make($this->period)->designation,
            // Acessar o relacionamento 'certification' do estudante
            'certification' => $this->student->certification ? (CertificationResource::make($this->student->certification)->file) : null,
        ];

    }
}
