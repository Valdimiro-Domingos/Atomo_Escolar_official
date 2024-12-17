<?php

namespace App\Http\Controllers\Pdf;

use App\Http\Controllers\Controller;
use App\Http\Resources\EnrollmentResource;
use App\Models\Company;
use App\Models\Enrollment;
use Illuminate\Support\Facades\View;
use PDF;

class EnrollmentPdf extends Controller
{
    //get_enrollment_pdf.php

    public function get_enrollment_pdf($id){
        $enrollment = Enrollment::with('student')->findOrFail(intval($id));

        $dados = [
            'dados' => EnrollmentResource::make($enrollment),
            'student' =>  $enrollment->student,
            'empresa' => Company::findOrFail(intval($enrollment->company_id)),
            'bancos' => "",
        ];


        $nome_arquivo = 'matrícula - '.$enrollment->enrollment_number.'-'. time() . '.pdf';
        
        
        $pdf = PDF::loadView('documents.matricula', compact('dados'));
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->download($nome_arquivo);

        
    }

}
