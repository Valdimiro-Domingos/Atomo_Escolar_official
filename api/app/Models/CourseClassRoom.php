<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseClassRoom extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'class_room_id',
    ];

    public function course(){
        return $this->belongsTo(Course::class);
    }
    public function classroom(){
        return $this->belongsTo(ClassRoom::class);
    }
}
