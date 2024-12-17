<?php

namespace App\Http\Controllers\Pdf;

use App\Http\Controllers\Controller;
use App\Http\Resources\GradeScheduleResource;
use App\Http\Resources\MiniScheduleResource;
use App\Models\Company;
use App\Models\Grade;
use App\Models\MiniSchedule;
use PDF;

class MiniSchedulePdf extends Controller
{
    public  function get_mini_schedule_pdf($id){


        $mini_schedule = MiniSchedule::findOrFail(intval($id));
        $dados = array(
            'dados' => MiniScheduleResource::make($mini_schedule),
            'empresa' => Company::findOrFail(intval($mini_schedule->company_id)),
            'primeiro_trimestre' => GradeScheduleResource::collection(Grade::with('mini_schedule')
                ->where('mini_schedule_id', '=',$mini_schedule->id)
                ->whereHas('mini_schedule.trimestre', function ($query) {
                    $query->where('designation', 'LIKE', '%1%');
                })
                ->get()),
            'segundo_trimestre' =>GradeScheduleResource::collection(Grade::with('mini_schedule')
                ->where('mini_schedule_id', '=',$mini_schedule->id)
                ->whereHas('mini_schedule.trimestre', function ($query) {
                    $query->where('designation', 'LIKE', '%2%');
                })
                ->get()),
            'terceiro_trimestre' =>GradeScheduleResource::collection(Grade::with('mini_schedule')
                ->where('mini_schedule_id', '=',$mini_schedule->id)
                ->whereHas('mini_schedule.trimestre', function ($query) {
                    $query->where('designation', 'LIKE', '%3%');
                })
                ->get()),
            "grades"=> GradeScheduleResource::collection(Grade::where('mini_schedule_id', '=',$mini_schedule->id)->get()),
            'bancos' => ""
        );

      

        $nome_arquivo = 'matrícula - '.$mini_schedule->id.''. time() . '.pdf';

        $pdf = PDF::loadView('pdf.schedule', compact('dados'));
        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream($nome_arquivo);


        // $html = View::make('pdf.mini-schedule', compact('dados'))->render();
        // //return $html;
        // $dompdf = new Dompdf();
        // // Carrega o HTML para o Dompdf
        // $dompdf->loadHtml($html);
        // // Renderiza o HTML para gerar o PDF
        // $dompdf->render();
        // // Nome do arquivo para download
        // // Envia o PDF para download
        // $dompdf->setPaper('A4', 'landscape');
        // return $dompdf->stream($nome_arquivo);

        // $pdf = Pdf::loadView('pdf.mini-schedule', compact('dados'));
        // $pdf->setPaper('A4', 'landscape');
        // return $pdf->stream($nome_arquivo);

    }

    public  function mini_schedule_pdf($id){


        $mini_schedule = MiniSchedule::findOrFail(intval($id));
        $dados = array(
            'dados' => MiniScheduleResource::make($mini_schedule),
            'empresa' => Company::findOrFail(intval($mini_schedule->company_id)),
            'primeiro_trimestre' => GradeScheduleResource::collection(Grade::with('mini_schedule')
                ->where('mini_schedule_id', '=',$mini_schedule->id)
                ->whereHas('mini_schedule.trimestre', function ($query) {
                    $query->where('designation', 'LIKE', '%1%');
                })
                ->get()),
            'segundo_trimestre' =>GradeScheduleResource::collection(Grade::with('mini_schedule')
                ->where('mini_schedule_id', '=',$mini_schedule->id)
                ->whereHas('mini_schedule.trimestre', function ($query) {
                    $query->where('designation', 'LIKE', '%2%');
                })
                ->get()),
            'terceiro_trimestre' =>GradeScheduleResource::collection(Grade::with('mini_schedule')
                ->where('mini_schedule_id', '=',$mini_schedule->id)
                ->whereHas('mini_schedule.trimestre', function ($query) {
                    $query->where('designation', 'LIKE', '%3%');
                })
                ->get()),
            "grades"=> GradeScheduleResource::collection(Grade::where('mini_schedule_id', '=',$mini_schedule->id)->get()),
            'bancos' => ""
        );

      

        $nome_arquivo = 'matrícula - '.$mini_schedule->id.''. time() . '.pdf';
        // return view('pdf.mini_schedule_id', compact('dados'));
        $pdf = PDF::loadView('pdf.mini-schedule', compact('dados'));
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream($nome_arquivo);
    }
}
