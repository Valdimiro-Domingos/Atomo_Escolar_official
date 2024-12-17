<?php

namespace App\Http\Controllers\Pdf;

use App\Models\Enrollment;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\InvoiceReceipt;
use App\Models\InvoiceReceiptItens;
use Dompdf\Dompdf;
use Dompdf\Options;
use Exception;
use PDF;
use App\Http\Resources\EnrollmentResource;
use App\Http\Resources\InvoiceReceiptResource;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;


class InvoiceReceiptPdf extends Controller
{
    public function get_invoice_receipts_pdf($id){
        try {
            $invoice_receipt = InvoiceReceipt::with('user','student',
                'enrollment','enrollment.school_year',
                 'enrollment.turma',
                 'enrollment.classe',
                 'enrollment.course',
                 'enrollment.class_room',
                 )->findOrFail(intval($id));
            $dados = array(
                'dados' => $invoice_receipt,
                'empresa' => Company::findOrFail(intval($invoice_receipt->company_id)),
                'item' => InvoiceReceiptItens::with('article','article.article_category')->where('invoice_receipt_id', $invoice_receipt->id)->get(),
                'bancos' => ""
            );
            
            $nome_arquivo = 'fatura-recibo - '.$invoice_receipt->id.''. time() . '.pdf';
            $image_path = "http://192.168.1.74:8001/images/avatar/user.png";
            $pdf = Pdf::loadView('pdf.get_invoice_receipt', compact('dados','nome_arquivo','image_path'));
           
            $pdf->setPaper('A4', 'portrait');

            // $html = View::make('pdf.get_invoice_receipt', compact('dados','nome_arquivo','image_path'));

            // $options = new Options();
            // $options->set('defaultPaperSize', 'A4');
            // $options->set('isHtml5ParserEnabled', true);
            // $dompdf = new Dompdf($options);
            // $dompdf->loadHtml($html);
            // $dompdf->render();
            $response = $pdf->stream($nome_arquivo);
            return $response;
        } catch (Exception $th) {
            return response(['pdf'=> $th->getMessage()],500)->header('Content-type', 'application/json');
        }
    }

    public function invoice_receipts_pdf($id){

  
        try {
            $invoice_receipt = InvoiceReceipt::with('user','student')->findOrFail(intval($id));
            $enrollment = Enrollment::with('student')->findOrFail(intval( $invoice_receipt->student->enrollmentOne->id));

            $nome_arquivo = 'fatura-recibo - '.$invoice_receipt->id.''. time() . '.pdf';
                
                 $dados = [
                'dados' => ($invoice_receipt),
                'empresa' => Company::findOrFail(intval($invoice_receipt->company_id)),
                'item' => InvoiceReceiptItens::with('article','article.article_category')->where('invoice_receipt_id', $invoice_receipt->id)->get(),
                'bancos' => "",
                "enrollement" => EnrollmentResource::make($enrollment),
                "arquivo" =>   $nome_arquivo
            ];


            $pdf = PDF::loadView('pdf.invoice_receipt', compact('dados'));
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream($nome_arquivo);

           
            // $pdf = Pdf::loadView('pdf.get_invoice_receipt', compact('dados','nome_arquivo','image_path'));
            //  $pdf->setOptions([
            //     'imageQuality' => 50, // Reduz a qualidade das imagens para 50%
            //     'compress' => true,
            //     'tempDir' => 'public/images/temp',
            //     'isRemoteEnabled' => true,
            // ]);
            // $pdf->setPaper('A4', 'portrait');

            // $html = View::make('pdf.get_invoice_receipt', compact('dados','nome_arquivo','image_path'));

            // $options = new Options();
            // $options->set('defaultPaperSize', 'A4');
            // $options->set('isHtml5ParserEnabled', true);
            // $dompdf = new Dompdf($options);
            // $dompdf->loadHtml($html);
            // $dompdf->render();
        } catch (Exception $th) {
            return response(['pdf'=> $th->getMessage()],500)->header('Content-type', 'application/json');
        }
    }
}
