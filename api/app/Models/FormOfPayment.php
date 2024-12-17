<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormOfPayment extends Model
{
    use HasFactory;
    protected $fillable = [
        'designation',
        'description',
        'status',
        'company_id'
    ];
    public function invoice_receipt(){
        return $this->hasOne(InvoiceReceipt::class);
    }
}
