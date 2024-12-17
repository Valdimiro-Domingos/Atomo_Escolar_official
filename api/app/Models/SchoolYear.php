<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Enrollment;
use App\Models\Company;
use App\Models\Schedule;



class SchoolYear extends Model
{
    use HasFactory;
    protected $fillable = [
        'designation',
        'description',
        'status',
        'company_id',
    ];
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
