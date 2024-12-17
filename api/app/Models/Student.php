<?php

namespace App\Models;

use App\Models\Enrollment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Grade;
use App\Models\InvoiceReceipt;



class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "photo",
        "identity",
        "gender",
        "father_name",
        "mother_name",
        "address",
        'company_id',
        'birth_year'
    ];

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }


    public function enrollment(){
        return $this->belongsTo(Enrollment::class, 'id','student_id');
    }

    public function enrollmentOne(){
        return $this->hasOne(Enrollment::class, 'student_id');
    }

    public function grade(){
        return $this->hasOne(Grade::class);
    }

    public function invoice_receipt(){
        return $this->hasOne(InvoiceReceipt::class);
    }
    
    
    public function certification()
    {
        return $this->hasOne(Certification::class);
    }
    
}
