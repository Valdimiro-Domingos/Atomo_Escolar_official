<?php

namespace App\Http\Controllers\Pdf;


use App\Models\Student;
use App\Http\Controllers\Controller;
use App\Http\Resources\GradeScheduleResource;
use App\Http\Resources\MiniScheduleResource;
use App\Http\Resources\ScheduleResource;
use App\Models\Company;
use App\Models\Grade;
use App\Models\MiniSchedule;
use App\Models\Schedule;
use PDF;



class ShedulePdf extends Controller
{

    public  function Schedule_pdf($id){

        $schedule = Schedule::findOrFail(intval($id));
        
        $estudantes = Student::whereHas('enrollmentOne', function ($query) use ($schedule) {
            $query->where('classe_id', $schedule->classe_id)
            ->where('school_year_id', $schedule->school_year_id)
            ->where('class_room_id', $schedule->class_room_id)
            ->where('turma_id', $schedule->turma_id);
        })->get();

        $dados = array(
            'dados' =>  ScheduleResource::make($schedule),
            'empresa' => ($schedule->company),
            "students" => $estudantes
        );


        $nome_arquivo = 'pauta - '.$schedule->id.''. time() . '.pdf';
        // return view('pdf.mini_schedule_id', compact('dados'));
        $pdf = PDF::loadView('pdf.schedule', compact('dados'));
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream($nome_arquivo);
    }
}
