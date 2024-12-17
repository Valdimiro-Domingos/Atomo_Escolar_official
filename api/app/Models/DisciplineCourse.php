<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisciplineCourse extends Model
{
    use HasFactory;
    protected $fillable = [
        'discipline_id',
        'course_id',
    ];

    protected $table = 'discipline_courses';
    public function discipline(){
        return $this->belongsTo(Discipline::class);
    }
    public function course(){
        return $this->belongsTo(Course::class);
    }
}
