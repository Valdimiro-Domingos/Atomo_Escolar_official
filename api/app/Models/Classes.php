<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    use HasFactory;
    protected $fillable = [
        'designation',
        'description',
        'status',
        'company_id',
    ];
    protected $table = 'classes';
    public function company(){
        return $this->belongsTo(Company::class);
    }
    public function courses(){
        return $this->hasMany(ClassesCourse::class);
    }

    public function turmas(){
        return $this->hasMany(Turma::class);
    }
    public function enrollment(){
        return $this->hasMany(Enrollment::class);
    }
    public function schedule(){
        return $this->hasMany(Schedule::class);
    }


}
