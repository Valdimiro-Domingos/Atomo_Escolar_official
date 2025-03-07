<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transport extends Model
{
    use HasFactory;
    protected $fillable = [
        'designation',
        'description',
        'status',
        'company_id',
    ];

    public function company(){
        return $this->belongsTo(Company::class);
    }
}
