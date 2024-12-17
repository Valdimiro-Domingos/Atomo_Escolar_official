<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trimestre extends Model
{
    use HasFactory;
    protected $fillable = [
        'designation',
        'description',
        'status',
        'company_id'
    ];

    protected $table = 'trimestres';
    public function courses(){
        return $this->hasMany(TrimestreCourse::class);
    }
    public function company(){
        return $this->belongsTo(Company::class);
    }
    public function mini_schedule(){
        return $this->hasMany(MiniSchedule::class);
    }
}
