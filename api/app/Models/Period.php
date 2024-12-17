<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    use HasFactory;
    protected $fillable = [
        'time_start',
        'time_end',
        'company_id',
        'designation',
        'description',
        'status'
    ];
    public function courses(){
        return $this->hasMany(PeriodCourse::class);
    }
    public function schedule(){
        return $this->hasMany(Schedule::class);
    }
    public function company(){
        return $this->belongsTo(Company::class);
    }
    public function enrollment(){
        return $this->hasMany(Enrollment::class);
    }
}
