<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassRoom extends Model
{
    use HasFactory;
    protected $fillable = [
        'designation',
        'description',
        'status',
        'company_id',
    ];
    protected $table = 'class_rooms';

    public function courses(){
        return $this->hasMany(CourseClassRoom::class);
    }
    public function company(){
        return $this->belongsTo(Company::class);
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
