<?php

namespace App\Models;

use App\Models\Student;
use App\Models\Certification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\InvoiceReceipt;
use App\Models\User;



class Enrollment extends Model
{
    use HasFactory;
    protected $fillable = [
        "student_id",
        "user_id",
        "school_year_id",
        "period_id",
        "classe_id",
        "dropout",
        "observation",
        "class_room_id",
        'paid',
        'enrollment_number',
        "turma_id",
        "course_id",
        "status",
        "message",
        "company_id",
    ];

    public function company(){
        return $this->belongsTo(Company::class);
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

    public function class_room(){
        return $this->belongsTo(ClassRoom::class);
    }

    public function enrollment_registation(){
        return $this->hasMany(EnrollmentRegistation::class);
    }
    
    public static function withRegistrations()
    {
        return self::whereHas('enrollment_registation')->get();
    }

    public function student(){
        return $this->belongsTo(Student::class);
    }


    public function invoice_receipt(){
        return $this->hasOne(InvoiceReceipt::class, 'enrollment_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function certification(){
        return $this->student->certification(); // Acesso ao relacionamento através do estudante
    }
}
