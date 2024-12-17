<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Turma extends Model
{
    use HasFactory;
    protected $table = 'turmas';

    protected $fillable = [
        'designation',
        'description',
        'status',
        'company_id',
    ];
    public function course(){
        return $this->belongsTo(Course::class);
    }

    public function company(){
        return $this->belongsTo(Company::class);
    }
    public function class(){
        return $this->belongsTo(Classes::class);
    }
    public function classRoom(){
        return $this->belongsTo(ClassRoom::class);
    }
    public function enrollment(){
        return $this->hasMany(Enrollment::class);
    }
    public function schedule(){
        return $this->hasMany(Schedule::class);
    }
}
