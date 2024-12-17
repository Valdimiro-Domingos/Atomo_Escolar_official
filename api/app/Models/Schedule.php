<?php

namespace App\Models;

use App\Models\Student;
use App\Models\Enrollment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;
    protected $fillable = [
        "designation",
        "description",
        "company_id",
        "status",
        "file",
        "school_year_id",
        "period_id",
        "classe_id",
        "turma_id",
        "course_id",
        "class_room_id",
        "discipline_id",
    ];

    public function company(){
        return $this->belongsTo(Company::class);
    }

    public function profeessor(){
        return $this->belongsTo(User::class);
    }
  
    public function school_year(){
        return $this->belongsTo(SchoolYear::class);
    }
    public function turma(){
        return $this->belongsTo(Turma::class);
    }
    public function classe(){
        return $this->belongsTo(Classes::class);
    }
    public function period(){
        return $this->belongsTo(Period::class);
    }
    public function course(){
        return $this->belongsTo(Course::class);
    }

    public function trimeste(){
        return $this->belongsTo(Trimestre::class);
    }
    public function class_room(){
        return $this->belongsTo(ClassRoom::class);
    }

    public function students()
    {
        return $this->belongsTo(Enrollment::class, "turma_id")->where('classe_id', $this->classe_id);
    }
    

    public function mini_shedules(){
        return $this->hasMany(MiniSchedule::class);
    }
    
   
}
