<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student;


class Certification extends Model
{
    use HasFactory;
    
     protected $fillable = [
        "student_id",
        "file",
        "company_id",
    ];
    
    public function student(){
        return $this->belongsTo(Student::class, 'student_id');
    }
}
