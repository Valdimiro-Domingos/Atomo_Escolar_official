<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Representatives extends Model
{
    use HasFactory;

    protected $fillable = [
        'general_manager',
        'pedagogical_manager',
        'provincial_manager',
        'municipal_manager',
        'company_id'
    ];

    public function company(){
        return $this->belongsTo(Company::class);
    }
}
