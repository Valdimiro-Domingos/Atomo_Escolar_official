<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departament extends Model
{
    use HasFactory;

    protected $fillable = [
        'designation',
        'description',
      'status',
      'company_id',
    ]; //

    public function users(){
        return $this->hasMany(User::class);
    }

    public function company(){
        return $this->belongsTo(Company::class);
    }
}
