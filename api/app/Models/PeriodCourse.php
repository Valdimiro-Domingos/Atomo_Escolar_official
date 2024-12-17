<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodCourse extends Model
{
    use HasFactory;

    protected $fillable = [
        'period_id',
        'course_id',
    ];
    public function period(){
        return $this->belongsTo(Period::class);
    }
    public function course(){
        return $this->belongsTo(Course::class);
    }
}
