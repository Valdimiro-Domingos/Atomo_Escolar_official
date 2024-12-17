<?php

namespace App\Http\Controllers\Pedagogical;

use App\Http\Controllers\Controller;
use App\Http\Resources\StudentResource;
use App\Models\MiniSchedule;
use App\Models\Schedule;
use App\Models\Student;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\EnrollmentResource;
use App\Models\Enrollment;
use App\Http\Controllers\Pedagogical\PeriodController;

use App\Http\Controllers\Pedagogical\TurmaController;

// dates
use App\Http\Controllers\Config\ConfigController;





class StudentController extends Controller
{
    protected $colletion;
    protected $periodo;
    protected $base;
    
    public function __construct() {
        $this->periodo = new PeriodController();
        $this->colletion = new TurmaController();
        $this->base = new ConfigController();
    }

    public function index() {
        try {
            
                $students = EnrollmentResource::collection( Enrollment::with('student')->join('students', 'enrollments.student_id', '=', 'students.id')
                ->where('enrollments.status', '1')
                ->where('enrollments.company_id', Auth::user()->company_id)
                ->orderBy('students.name', 'asc')
                ->get(['enrollments.*', 'students.name']));

                    return response()->json(
                    ['students'=>($students), 
                    "items"=>($this->base->model()->original), 
                    "periodos"=>$this->periodo->init()->original],200);

            } catch (Exception $th) {
                return response(['students'=> $th->getMessage(), "message"=>"Problema Interno"],500)->header('Content-type', 'application/json');
        }
    }
    
    public function view() {
        try {
                $students = EnrollmentResource::collection( Enrollment::with('student')->join('students', 'enrollments.student_id', '=', 'students.id')
                    ->where('enrollments.status', '1')
                    ->where('enrollments.company_id', Auth::user()->company_id)
                    ->orderBy('students.name', 'asc')
                    ->get(['enrollments.*', 'students.name']));
                    // foreach ($students as $key => $student) {
                        // $students = EnrollmentResource::make(Enrollment::with(['class_room','course','period','classe','turma','student','school_year'])->findOrFail($student->enrollment_id));
                    // }
                
                return response()->json([
                'students'=> ($students), 
                "items"=>($this->base->model()->original), 
                "periodos"=>[]],200);
        
            } catch (Exception $th) {
                return response(['students'=> $th->getMessage(), "message"=>"Problema Interno"],500)->header('Content-type', 'application/json');
        }
    }
    
    public function search(Request $request) {
        
    }


    public function student_in_turma ($id){
       try {
           $mini_schedule = MiniSchedule::findOrfail(intval($id));
            $schedule = Schedule::findOrfail(intval($mini_schedule->schedule_id));
            $students  = Student::select('students.id','students.name')
                ->join('enrollments','enrollments.student_id', 'students.id')
             	->where('enrollments.status','1')
               ->where('enrollments.turma_id',$schedule->turma_id)
               ->where('enrollments.class_room_id',$schedule->class_room_id) ->get();
               //->where('enrollments.classe_id',$schedule->classe_id)

                
                return $students;

                return response(['students'=>StudentResource::collection(
                    $students)],200)->header('Content-Type', 'application/json');


            } catch (Exception $th) {
                return response(['students'=> $th->getMessage(), "message"=>"Desculpe pelo transtorno. Houve um erro. Estamos trabalhando nisso. Por favor, tente novamente mais tarde."],500)->header('Content-type', 'application/json');

            }
    }
    

}
