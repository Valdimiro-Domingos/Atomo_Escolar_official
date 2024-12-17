<?php

namespace App\Models;

use App\Models\Student;
use App\Models\Enrollment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MiniSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'designation',
        // 'description',
        "company_id",
        "trimestre_id",
        "file",
        "profeessor_id",
        "discipline_id",
        'schedule_id'
    ];

    public function discipline(){
        return $this->belongsTo(Discipline::class);
    }

    public function grades(){
        return $this->hasMany(Grade::class, 'mini_schedule_id');
    }
     
    public function profeessor(){
        return $this->belongsTo(User::class);
    }
    public function schedule(){
        return $this->belongsTo(Schedule::class);
    }
    public function trimestre(){
        return $this->belongsTo(Trimestre::class);
    }
    
    public function classe(){
        return $this->belongsTo(Classes::class);
    }
    
    public function class_room(){
        return $this->belongsTo(ClassRoom::class);
    }
    
    public function school_year(){
        return $this->belongsTo(SchoolYear::class);
    }
    
    public function course(){
        return $this->belongsTo(Course::class);
    }
    
    public function turma(){
        return $this->belongsTo(Turma::class);
    }
    
    
    
    
    public function period(){
        return $this->belongsTo(Period::class, "period_id");
    }

    
}
