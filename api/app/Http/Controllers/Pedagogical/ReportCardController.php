<?php

namespace App\Http\Controllers\Pedagogical;



use  PDF;
use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\MiniSchedule;
use App\Models\Student;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\GradeScheduleResource;
use App\Http\Resources\MiniScheduleResource;
use App\Models\Company;

use App\Http\Resources\EnrollmentResource;
use App\Models\Enrollment;
use App\Models\Schedule;



class ReportCardController extends Controller
{
    public function showAll($id)
    {
        try {
            
            $request = MiniSchedule::where('company_id', Auth::user()->company_id)->findOrFail(intval($id));
            
            $listaEstudantes = Enrollment::with("student")->with("student.grade")->where('class_room_id', $request->class_room_id)
            ->where('classe_id', $request->classe_id)
            ->where('course_id', $request->course_id)
            ->where('school_year_id', $request->school_year_id)
            ->where('turma_id', $request->turma_id)
            ->where('period_id', $request->period_id)
            ->where('company_id', Auth::user()->company_id)->fisrt();
            
            foreach ($listaEstudantes as $key => $value) {
                $listaEstudantes[$key] = $value->student;
            }
            return response()->json([ 'students' => $listaEstudantes],200);
            
        } catch (Exception $th) {
            return response(['grade'=> $th->getMessage(), "message"=>"Nota não encontrada!"],500)->header('Content-type', 'application/json');
        }
    }
    
    
    public function studentId(Request $request, $id){
        
        $nameStudent = 'Boletim';
        
        $enrollment = Enrollment::with("student")->with("student.grade")
        //->where('company_id', Auth::user()->company_id)
        ->findOrFail(intval($id));
        if($enrollment->student){
            // $nameStudent = $enrollment->student->name;
        }
        
        $company = Company::findOrFail($enrollment->company_id);
        
        
        $studentId = $enrollment->student_id;
        $mini = Schedule::with('mini_shedules')->with('mini_shedules.grades')
        ->whereHas('mini_shedules.grades', function ($query) use ($studentId) {
            $query->where('student_id', $studentId);
        })
        ->where('class_room_id', $enrollment->class_room_id)
        ->where('classe_id', $enrollment->classe_id)
        ->where('course_id', $enrollment->course_id)
        ->where('school_year_id', $enrollment->school_year_id)
        ->where('turma_id', $enrollment->turma_id)
        ->where('period_id', $enrollment->period_id)
        ->first();
        
        $miniShedule = MiniScheduleResource::collection($mini->mini_shedules);
        $pdf = PDF::loadView('documents/boletim-student', compact('enrollment','miniShedule', 'company'));
        $pdf->setPaper('A4', 'portrait');
        return  $pdf->stream($nameStudent.".pdf");
        
        // $mini = MiniSchedule::with(['grades' => function ($query) use ($studentId) {
        //     $query->where('student_id', $studentId);
        // }])
        // ->where('class_room_id', $enrollment->class_room_id)
        // ->where('classe_id', $enrollment->classe_id)
        // ->where('course_id', $enrollment->course_id)
        // ->where('school_year_id', $enrollment->school_year_id)
        // ->where('turma_id', $enrollment->turma_id)
        // ->where('period_id', $enrollment->period_id)
        // ->get();
        
            
    }
}
