<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discipline extends Model
{
    use HasFactory;
    protected $fillable = [
        'designation',
        'description',
        'status',
        'company_id',
    ];

    protected $table = 'disciplines';

    public function company(){
        return $this->belongsTo(Company::class);
    }
    public function courses(){
        return $this->hasMany(DisciplineCourse::class);
    }
    public function mini_schedule(){
        return $this->hasOne(MiniSchedule::class);
    }
}
