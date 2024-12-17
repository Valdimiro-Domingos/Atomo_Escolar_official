<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrimestreCourse extends Model
{
    use HasFactory;
    protected $fillable = [
        'trimestre_id',
        'course_id',
    ];

    protected $table = 'trimestre_courses';
    public function trimestre(){
        return $this->belongsTo(Trimestre::class);
    }
    public function course(){
        return $this->belongsTo(Course::class);
    }
}
