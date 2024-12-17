<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $table = 'companies';
    protected $fillable = [
        'designation','nif',
        'logo',
        'foundation_date',
        'share_capital',
        'email',
        'contact',
        'description',
        'representative_name',
        'representative_identification',
        'country',
        'city',
        'dateIssure',
        'dateDue',
        'address',
        'whatsapp',
        'facebook',
        'web_site',
        'general_manager',
        'pedagogical_manager',
        'provincial_manager',
        'municipal_manager',

    ];

    public function user(){
        return $this->hasOne(User::class, 'company_id');
    }

    public function users(){
        return $this->hasMany(User::class);
    }

    public function company_banks(){
        return $this->hasMany(CompanyBank::class);
    }

    public function course(){
        return $this->hasMany(Course::class);
    }

    public function  classrooms(){
        return $this->hasMany( Classroom::class);
    }
    public function departaments(){
        return $this->hasMany(Departament::class);
    }
    public function schedule(){
        return $this->hasMany(Schedule::class);
    }
    public function disciplines(){
        return $this->hasMany(Discipline::class);
    }

    public function classes(){
        return $this->hasMany(Classes::class);
    }
    public function period(){
        return $this->hasMany(Period::class);
    }
    public function trimestres(){
        return $this->hasMany(Trimestre::class);
    }

    public function school_year(){
        return $this->hasMany(SchoolYear::class);
    }
    public function enrollment(){
        return $this->hasMany(Enrollment::class);
    }
    public function enrollment_registation(){
        return $this->hasMany(EnrollmentRegistation::class);
    }

    public function retention(){
        return $this->hasMany(Retention::class);
    }
    public function transport(){
        return $this->belongsTo(Transport::class);
    }

    public function article_types(){
        return $this->hasMany(ArticleType::class);
    }
    public function article_categories(){
        return $this->hasMany(ArticleCategory::class);
    }
    public function tax(){
        return $this->belongsTo(Tax::class);
    }
    public function article(){
        return $this->hasMany(Article::class);
    }

    public function invoice_receipt(){
        return $this->hasMany(InvoiceReceipt::class);
    }
    public function expenses(){
        return $this->hasMany(Expenses::class);
    }
    public function representatives(){
        return $this->hasOne(Representatives::class);
    }
}
