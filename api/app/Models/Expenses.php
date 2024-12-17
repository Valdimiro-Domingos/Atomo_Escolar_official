<?php

namespace App\Models;


use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expenses extends Model
{
    use HasFactory;

    protected $fillable = [
        'designation',
        'description',
        'status',
        'value',
        'company_id'
    ];

    public function company(){
        return $this->belongsTo(Company::class, 'company_id');
    }

    

}
