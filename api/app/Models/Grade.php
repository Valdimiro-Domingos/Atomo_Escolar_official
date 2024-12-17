<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'mini_schedule_id',
        'continuous_evaluation_average',
        'teachers_test_score',
        'quarterly_test_score',
        'quarterly_average',
        'company_id'
    ];

    public function student(){
        return $this->belongsTo(Student::class);
    }
    public function mini_schedule(){
        return $this->belongsTo(MiniSchedule::class);
    }
}
