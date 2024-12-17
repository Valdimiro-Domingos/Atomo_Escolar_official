<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\FormOfPayment;
class InvoiceReceiptItens extends Model
{
    use HasFactory;

    protected $fillable = [
        "qtd",
        "discount",
        "rate",
        "paid", 
        'price',
        "article_id",
        "category_id",
        "article_designation",
        "category_designation",
        "invoice_receipt_id",
        "company_id",
    ];

    public function invoice_receipt(){
        return $this->belongsTo(InvoiceReceipt::class);
    }
    public function article(){
        return $this->belongsTo(Article::class);
    }

}
