<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnrollmentRegistation extends Model
{
    use HasFactory;

    protected $fillable = [
        "status","student_id","enrollment_id", "company_id", 
    ];

    public function enrollment(){
        return $this->belongsTo(Enrollment::class);
    }
    public function company(){
        return $this->belongsTo(Company::class);
    }
}
