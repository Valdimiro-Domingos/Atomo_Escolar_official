<?php

namespace App\Models;


use App\Models\Student;
use App\Models\Enrollment;
use App\Models\FormOfPayment;
use App\Models\InvoiceReceiptItens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InvoiceReceipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'date_of_issue',
        'due_date',
        'user_id',
        'client_name',
        'student_id',
        'form_of_payment_id',
        'enrollment_id',
        'coin',
        'total',
        'hash',
        'subtotal',
        'discount',
        'tax',
        'status',
        'company_id'
    ];
    public function invoice_receipt_itens(){
        return $this->hasMany(InvoiceReceiptItens::class);
    }

    public function company(){
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function student(){
        return $this->belongsTo(Student::class);
    }

    public function form_of_payment(){
        return $this->belongsTo(FormOfPayment::class);
    }

    public function enrollment(){
        return $this->belongsTo(Enrollment::class);
    }


    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
