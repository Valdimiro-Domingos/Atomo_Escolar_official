<?php

namespace App\Http\Controllers\Config\Reports;


use PDF;
use Exception;
use Carbon\Carbon;
use App\Models\Expenses;
use Illuminate\Http\Request;
use App\Models\Enrollment;
use App\Models\Company;
use App\Models\InvoiceReceipt;
use App\Models\InvoiceReceiptItens;
use App\Http\Controllers\Controller;
use App\Http\Resources\ExpenseResource;
use App\Http\Resources\EnrollmentResource;
use Illuminate\Support\Facades\Auth;




class ReportController extends Controller
{

    public function report(Request $request, $type, $company_id, $date_of = null, $date_=null) {

        $date_of_issure = isset($date_of) ? str_replace('-', '/', $date_of) : date('Y/m/d');
        $date_due = isset($date_) ? str_replace('-', '/', $date_) : date('Y/m/d');

            
        $request['type'] = $type;
        $request['date_due'] = $date_due;
        $request['date_of_issure'] = $date_of_issure;

       $dateOfIssure = Carbon::parse($date_of_issure)->format('Y-m-d');
       $dateDue = Carbon::parse($date_due)->format('Y-m-d');

        switch ($request->type) {
            case 'matricula':
                $enrollment =  Enrollment::with(['class_room', 'company', 'user', 'course', 'period', 'classe', 'turma', 'student', 'school_year'])
                    ->where('company_id',$company_id)
                    ->where('status', '1')
                     ->whereBetween('created_at', [$dateOfIssure . ' 00:00:00', $dateDue . ' 23:59:59'])
                    ->get();


                $company = Company::findOrFail($company_id);
                $pdf = PDF::loadView('relatorios.relatorio', compact('enrollment', 'request', 'company'));
                $pdf->setPaper('A4', 'landscape');
                return $pdf->stream("Reporte-Matricula.pdf");
            break;


            case 'fatura-recibo':
                $invoice_receipt = InvoiceReceipt::with(['user', 'student'])->where('company_id', $company_id)
                //->whereBetween('created_at', [$dateOfIssure, $dateDue])
                ->whereBetween('created_at', [$dateOfIssure . ' 00:00:00', $dateDue . ' 23:59:59'])
                ->get();
                
                // return $invoice_receipt;
               $company = Company::findOrFail($company_id);
               $pdf = PDF::loadView('relatorios.relatorio', compact('invoice_receipt', 'request', 'company'));
               $pdf->setPaper('A4', 'landscape');
               return $pdf->stream("Reporte-.".$type.".pdf");
            break;

             case 'despesas':
                $expenses = Expenses::where('company_id', $company_id)
                //->whereBetween('created_at', [$dateOfIssure, $dateDue])
                ->whereBetween('created_at', [$dateOfIssure . ' 00:00:00', $dateDue . ' 23:59:59'])
                ->get();
                
                $company = Company::findOrFail($company_id);
                $pdf = PDF::loadView('relatorios.relatorio', compact('expenses', 'request', 'company'));
                $pdf->setPaper('A4', 'landscape');
                return $pdf->stream("Reporte-".$type.".pdf");
            break;
        }
    }
}
