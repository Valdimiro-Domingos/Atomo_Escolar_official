<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyBank extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'account_number',
        'iban',
      'swift',
      'company_id',
    ];
      protected $table = 'company_banks';

    public function company(){
        return $this->belongsTo(Company::class);
    }
    }
