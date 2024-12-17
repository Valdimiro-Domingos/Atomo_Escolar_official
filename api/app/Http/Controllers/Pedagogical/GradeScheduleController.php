<?php

namespace App\Http\Controllers\Pedagogical;

use App\Http\Controllers\Controller;
use App\Http\Resources\GradeScheduleResource;
use App\Models\Grade;

use App\Models\MiniSchedule;
use App\Models\Student;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;



use App\Http\Resources\EnrollmentResource;
use App\Models\Enrollment;


class GradeScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $mini_schedule_id  = null){


        try{
            return $this->showAll($request->mini_schedule_id);

            // $mini_schedule = isset($request->mini_schedule_id ) ?
            //         [Grade::with('mini_schedule')->where('mini_schedule_id', $request->mini_schedule_id)->
            //         where('company_id',Auth::user()->company_id)->get()]
            //     :
            //         Grade::where('company_id',  '=',Auth::user()->company_id)->get();
            //         return response(['grades'=>GradeScheduleResource::collection($mini_schedule)],200)->header('Content-Type', 'application/json');

        }catch(Exception $e){
            return response(["message"=>"Desculpe pelo transtorno. Houve um erro. Estamos trabalhando nisso. Por favor, tente novamente
            mais tarde."],403);
        }




    }
    
    public function teste(Request $request, $mini_schedule_id  = null){
        try{

            $student  = Student::select('students.id','students.name','enrollments.*')
                ->join('enrollments','enrollments.student_id','=','students.id')
                ->where('enrollments.company_id',  '=',Auth::user()->company->id)->where('enrollments.status','=','1')
                ;

                if ($request->has('classe_id')) {
                    $student->where('enrollments.classe_id', '=',  $request->input('classe_id'));
                }
                if ($request->has('turma_id')) {
                    $student->where('enrollments.turma_id', '=',  $request->input('turma_id'));
                }
                if ($request->has('class_room_id')) {
                    $student->where('enrollments.class_room_id', '=',  $request->input('class_room_id'));
                }
                $students = $student->get();
                return response(['grades'=>GradeScheduleResource::collection($students)],200)->header('Content-Type', 'application/json');

        }catch(Exception $e){
            return response(["message"=>"Desculpe pelo transtorno. Houve um erro. Estamos trabalhando nisso. Por favor, tente novamente
            mais tarde."],403);
        }
    }

    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            
            if($request->id){
               return $this->update($request, $request->id);
            }else{
                
                $student = Student::findOrfail(intval($request->input('student_id')));
                $mini_schedule = MiniSchedule::findOrfail(intval($request->input('mini_schedule_id')));
                if(!((float)($request->continuous_evaluation_average) >= 0 && (float)($request->continuous_evaluation_average)<21) || !(((float)($request->teachers_test_score)>=0 && (float)($request->teachers_test_score)<21)) || !(((float)($request->quarterly_test_score)>=0 && (float)($request->quarterly_test_score)<21)) ){
                    return response(["message"=> "As notas devem estar no intervalo válido (0 a 20)",'grades'=>GradeScheduleResource::collection(
                        Grade::where('mini_schedule_id', $mini_schedule->id)->where('company_id',  '=',Auth::user()->company->id)->get())
                    ],400)->header('Content-Type', 'application/json');
                }
    
                $grades = Grade::where(['student_id' => $student->id, 'mini_schedule_id' => $mini_schedule->id,'company_id' => Auth::user()->company->id,])->first();
                if ($grades) {
                    return response([ "message"=>" Desculpe, as notas do estudante ". $student->name ." já foram lançadas!", 'grades'=>GradeScheduleResource::collection(
                        Grade::where('mini_schedule_id', $mini_schedule->id)->where('company_id',  '=',Auth::user()->company->id)->get())],500)->header('Content-type', 'application/json');
                   
                }
                $grade = Grade::create([
                    'continuous_evaluation_average' => $request->input('continuous_evaluation_average'),
                    'teachers_test_score' => $request->input('teachers_test_score'),
                    'quarterly_test_score' => $request->input('quarterly_test_score'),
                    'quarterly_average' => (
                        (float)($request->continuous_evaluation_average) +
                        (float)($request->teachers_test_score) +
                        (float)($request->quarterly_test_score))/3,
                    'student_id' => $student->id,
                    'mini_schedule_id' => $mini_schedule->id,
                    'company_id' => Auth::user()->company_id,
                ]);
                
               return $this->showAll($request->mini_schedule_id);
            }

        } catch (Exception $th) {
                       return response(['schedules'=> $th->getMessage(),
                       "message"=>"Desculpe pelo transtorno. Houve um erro. Estamos trabalhando nisso. Por favor, tente novamente
                       mais tarde."],500)->header('Content-type', 'application/json');

        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        try {
            $grades = Grade::findOrFail(intval($id));
        return response(
            ['grade'=>GradeScheduleResource::make($grades)],200
        )->header('Content-Type', 'application/json');
        } catch (Exception $th) {
            return response(['grade'=> $th->getMessage(), "message"=>"Nota não encontrada!"],500)->header('Content-type', 'application/json');
        }
    }
    
    public function showAll($id)
    {
        try {
            // tras os estudantes e suas notas
            $grads = MiniSchedule::with("grades")->with('grades.student')
            ->where('company_id', Auth::user()->company_id)
            ->findOrFail(intval($id));

            
            $students = Enrollment::with("student")
            ->where('school_year_id', $grads->schedule->school_year_id)
            ->where('classe_id', $grads->schedule->classe_id)
            ->where('turma_id', $grads->schedule->turma_id)
            ->where('course_id', $grads->schedule->course_id)
            ->where('class_room_id', $grads->schedule->class_room_id)
            ->where('period_id', $grads->schedule->period_id)
            ->where('company_id', Auth::user()->company_id)->get();
            
            return response()->json([ 'students' => $students, "grads"=>$grads->grades],200);
            
        } catch (Exception $th) {
            return response(['grade'=> $th->getMessage(), "message"=>"Nota não encontrada!"],500)
            ->header('Content-type', 'application/json');
        }
    }

   
    public function  update(Request $request,$id){
        try {
            $grade = Grade::findOrfail(intval($id));
            if(!((float)($request->continuous_evaluation_average) >= 0 && (float)($request->continuous_evaluation_average)<21) || !(((float)($request->teachers_test_score)>=0 && (float)($request->teachers_test_score)<21)) || !(((float)($request->quarterly_test_score)>=0 && (float)($request->quarterly_test_score)<21)) ){
                return response(["message"=> "As notas devem estar no intervalo válido (0 a 20)",'grades'=>GradeScheduleResource::collection(
                    Grade::where('mini_schedule_id', $grade->mini_schedule_id)->where('company_id',  '=',Auth::user()->company->id)->get())
                ],400)->header('Content-Type', 'application/json');
            }
            $grade->continuous_evaluation_average = (float)($request->continuous_evaluation_average);
            $grade->teachers_test_score = (float)($request->teachers_test_score);
            $grade->quarterly_test_score = (float)($request->quarterly_test_score);
            $grade->quarterly_average =  (
                (float)($request->continuous_evaluation_average) +
                (float)($request->teachers_test_score) +
                (float)($request->quarterly_test_score))/3;
            $grade->save();
         return  $this->showAll($request->mini_schedule_id);

        } catch (Exception $th) {
            return response(['grades'=> $th->getMessage(),
             "message"=>"Desculpe pelo transtorno. Houve um erro. Estamos trabalhando nisso. Por favor, tente novamente mais tarde."],500)->header('Content-type', 'application/json');

        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $grades = Grade::findOrFail($id);

            return response(["message"=>"Não é possível eliminar nota de um Estudante",'grades'=>GradeScheduleResource::collection(
                Grade::where('mini_schedule_id', $grades->mini_schedule_id)->where('company_id',  '=',Auth::user()->company->id)->get()),],400)->header('Content-Type', 'application/json');

        } catch (Exception $th) {
            if($th->getCode() == 23000){
                return response(['grades'=>$th->getMessage(),  "message"=>"Não é possível excluir esta Mini pauta devido a registros associados. Por favor, remova ou atualize esses registros antes de tentar excluir a classe novamente."],500)->header('Content-type', 'application/json');
            }else{
                return response(['grades'=> $th->getMessage(), "message"=>"Não é possível eliminar nota de um Estudante."],500)->header('Content-type', 'application/json');
            }
        }
    }
    
    
}
