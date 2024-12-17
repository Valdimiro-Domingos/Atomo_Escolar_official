<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $fillable = [
        'designation' ,
        'description',
        'status',
        'company_id',
    ];
    protected $table = 'courses';

    public function company(){
        return $this->belongsTo(Company::class);
    }
    public function classrooms(){
        return $this->hasMany(CourseClassroom::class);
    }
    public function turmas(){
        return $this->hasMany(Turma::class);
    }
    public function disciplines(){
        return $this->hasMany(DisciplineCourse::class);
    }
    public function period(){
        return $this->hasMany(PeriodCourse::class);
    }
    public function classs(){
        return $this->hasMany(ClassesCourse::class);
    }
    public function trimestres(){
        return $this->hasMany(TrimestreCourse::class);
    }

    public function enrollment(){
        return $this->hasMany(Enrollment::class);
    }
    public function schedule(){
        return $this->hasMany(Schedule::class);
    }
}
