<?php

namespace App\Http\Controllers\secretary;

use Exception;
use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Classes;
use App\Models\ClassRoom;
use App\Models\Course;
use App\Models\Period;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\Turma;
use App\Models\EnrollmentRegistation;
use App\Models\Enrollment;
use App\Http\Resources\EnrollmentResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\InvoiceReceipt;
use App\Models\InvoiceReceiptItens;

use App\Http\Controllers\Pedagogical\PeriodController;
use App\Http\Controllers\Pedagogical\TurmaController;

use App\Http\Controllers\Config\ConfigController;

class EnrollmentController extends Controller
{
    protected $colletion;
    protected $periodo;
    
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['relatorio']);
         $this->colletion = new ConfigController();
         $this->periodo = new PeriodController();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // EnrollmentResource::collection

        try {
            $enrollment =  Enrollment::with(['class_room','course','period','classe','turma','student','school_year'])
                ->where('company_id', Auth::user()->company_id)
                ->where('status', '1')
                ->orderByDesc("id")
                ->get();
            return response(
                ['enrollment'=> ($enrollment), ],200
            )->header('Content-Type', 'application/json');
        } catch (Exception $th) {
            return response(['enrollment'=> $th->getMessage(),"message"=>"Desculpe pelo transtorno. Houve um erro. Estamos trabalhando nisso. Por favor, tente novamente mais tarde."],404)->header('Content-type', 'application/json');
        }
    }
    
    
    public function dropout() {
        try {
            $enrollment =  Enrollment::with(['class_room','course','period','classe','turma','student','school_year'])
                ->where('company_id', Auth::user()->company_id)
                ->where('status', '0')->where('dropout', '1')->orderByDesc("id")
                ->get();
                
            return response()->json([
                'enrollment'=>$enrollment, 
                "enrollment_number" =>  Enrollment::where('company_id', Auth::user()->company_id)->count()+1,
                "items"=>$this->colletion->model()->original], 200);
        } catch (Exception $th) {
            return response(['enrollment'=> $th->getMessage(),"message"=>"Desculpe pelo transtorno. Houve um erro. Estamos trabalhando nisso. Por favor, tente novamente mais tarde."],404)->header('Content-type', 'application/json');
        }
    }
    
    public function view() {
        try {
            $enrollment =  Enrollment::with(['class_room','course','period','classe','turma','student','school_year'])
                ->where('company_id', Auth::user()->company_id)
                ->where('dropout', '0')->where('status', '1')->orderByDesc("id")
                ->get();
                
            return response()->json([
                'enrollment'=>$enrollment, 
                "enrollment_number" =>  Enrollment::where('company_id', Auth::user()->company_id)->count()+1,
                "items"=>$this->colletion->model()->original], 200);
        } catch (Exception $th) {
            return response(['enrollment'=> $th->getMessage(),"message"=>"Desculpe pelo transtorno. Houve um erro. Estamos trabalhando nisso. Por favor, tente novamente mais tarde."],404)->header('Content-type', 'application/json');
        }
    }
    


    /**t
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $itens = InvoiceReceipt::where('company_id', Auth::user()->company->id)->count() +1;
        
        if(count(Student::where('identity', $request->input('identity'))->where('company_id', Auth::user()->company_id)->get()) > 0) {
            return response()->json(['message'=>'Identidade já existente'], 500);
        }
        
        try {


            $class_room = ClassRoom::findOrfail($request->input('class_room_id'));
            $turma = Turma::findOrfail($request->input('turma_id'));
            $classe = Classes::findOrfail($request->input('classe_id'));
            $school_year = SchoolYear::findOrfail($request->input('school_year_id'));
            $period = Period::findOrfail($request->input('period_id'));



            if($class_room && $turma && $school_year && $classe && $period){

                DB::beginTransaction();
                $student = new Student();
                $student->name = $request->input('name');
                $student->identity = $request->input('identity');
                $student->mother_name = isset($request->mother_name) ? $request->mother_name: null;
                $student->father_name = isset($request->father_name) ? $request->father_name: null;
                $student->birth_year = isset($request->birth_year) ? $request->birth_year: null;
                $student->address = isset($request->address) ? $request->address: null;
                // $student->county = isset($request->county) ? $request->county: null;
                $student->gender = mb_strtoupper($request->input('gender'));

                if ($request->hasFile('photo')) {
                    $file = $request->file('photo');
                    $filename = time() .'.'. $file->getClientOriginalExtension();
                    $file->move(public_path('secretary/enrollment'), $filename);
                    $student->photo = $filename;
                }




                $student->company_id = Auth::user()->company_id;
                $student->save();


                $enrollment = new Enrollment();
                $enrollment->company_id = Auth::user()->company_id;
                $enrollment->user_id = Auth::user()->id;
                $enrollment->class_room_id = $class_room->id;
                $enrollment->period_id = $period->id;
                $enrollment->school_year_id = $school_year->id;
                $enrollment->classe_id = $classe->id;
                $enrollment->turma_id = $turma->id;
                $enrollment->course_id = $request->input('course_id') ? $request->input('course_id') : null;
                $enrollment->student_id = $student->id;
                $enrollment->status = '1';
                $enrollment->message = 'matricula';
                $enrollment->enrollment_number = $request->input('enrollment_number') ??  Enrollment::where("company_id", Auth::user()->company_id)->count()+1;
                $enrol = $enrollment->save();

                if($student && $enrol){
                    DB::commit();
                     return $this->view()->original;
                }
            }


        } catch (Exception $th) {
            DB::rollBack();
            return response([
                'enrollment'=> $th->getMessage(),"message"=>"Desculpe pelo transtorno. Houve um erro. Estamos trabalhando nisso. Por favor, tente novamente mais tarde.",
            ],500)
            ->header('Content-type', 'application/json');
        }

    }

    public function confirmation(Request $request)
    {
        try {


            $class_room = ClassRoom::findOrfail($request->input('class_room_id'));
            $turma = Turma::findOrfail($request->input('turma_id'));
            $classe = Classes::findOrfail($request->input('classe_id'));
            $school_year = SchoolYear::findOrfail($request->input('school_year_id'));
            $period = Period::findOrfail($request->input('period_id'));
            $course = Course::findOrfail($request->input('course_id'));

            DB::beginTransaction();

            $enrollmentCurrent =  Enrollment::findOrfail(intval($request->input('enrollment_id')));
            $enrollmentCurrent->status = '0';
            $enrollmentCurrent->save();
            
            
            EnrollmentRegistation::create([
                "company_id" => $enrollmentCurrent->company_id,
                "enrollment_id" => $enrollmentCurrent->id,
                "student_id" => $enrollmentCurrent->student_id,
                "status" => "1",
            ]);



            $enrollment = new Enrollment();
            $enrollment->company_id = Auth::user()->company_id;
            $enrollment->class_room_id = $class_room->id;
            $enrollment->period_id = $period->id;
            $enrollment->school_year_id = $school_year->id;
            $enrollment->classe_id = $classe->id;
            $enrollment->turma_id = $turma->id;
            $enrollment->user_id = Auth::user()->id;
            $enrollment->course_id = $course->id;
            $enrollment->student_id = $request->student_id;
            $enrollment->status = '1';
            $enrollment->message = 'matricula';
            $enrollment->enrollment_number = $request->input('enrollment_number');
            $enrol = $enrollment->save();
            if($enrol){
                DB::commit();
                return $this->view()->original;
            }
        } catch (Exception $th) {
            DB::rollBack();
            return response([
                'enrollment'=> $th->getMessage(),"message"=>"Desculpe pelo transtorno. Houve um erro. Estamos trabalhando nisso. Por favor, tente novamente mais tarde.",
            ],500)
            ->header('Content-type', 'application/json');
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id=null)
    {
        if($id==null){
            return response(['enrollment'=> "","message"=>"Seleciona uma matricula"],404)->header('Content-type', 'application/json');

        }
            try {
                $enrollment =  Enrollment::with('class_room','student','course','period','classe','turma','school_year')->findOrFail(intval($id));
                return response(
                    ['enrollment'=> EnrollmentResource::make($enrollment)],200
                )->header('Content-Type', 'application/json');
            } catch (ModelNotFoundException $th) {
                return response(['enrollment'=> $th->getMessage(),"message"=>"Matrícula não encontrada!."],404)->header('Content-type', 'application/json');
            }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try 
        {
            $enrollment = Enrollment::findOrFail($id);
    
             if ($request->hasFile('photo')) {
                 $file = $request->file('photo');
                 $filename = time() . '.' . $file->getClientOriginalExtension();
                 $file->move(public_path('secretary/enrollment'), $filename);
                 $enrollment->student->photo = $filename;
             } else {
                 $enrollment->student->photo = null;
             }
             
            $enrollment->update($request->except('photo'));
             
            $student = Student::findOrFail($enrollment->student->id);
            $student->update($request->except('photo'));
            
            return $this->view()->original;

        } catch (Exception $th) {
            return response(['enrollment'=> $th->getMessage(),"message"=>"Desculpe pelo transtorno. Houve um erro. Estamos trabalhando nisso. Por favor, tente novamente mais tarde."],404)->header('Content-type', 'application/json');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        try {
            $enrollment = Enrollment::findOrFail($id);
            if($enrollment->dropout == '1'){
                $enrollment->update(['status'=>'1', 'dropout'=> '0', 'observation'=>$request->observation]);
            }else{
                $enrollment->update(['status'=>'0', 'dropout'=> '1', 'observation'=>$request->observation]);
            }
            
            return $this->view()->original;
        } catch (Exception $th) {
            if($th->getCode() == 23000){
                return response(['classe'=>$th->getMessage(),
                 "message"=>"Não é possível excluir esta classe devido a registros associados. Por favor, remova ou atualize esses registros antes de tentar excluir a classe novamente."],500)->header('Content-type', 'application/json');
            }else{
                return response(['classe'=> $th->getMessage(),
                 "message"=>"Desculpe pelo transtorno. Houve um erro. Estamos trabalhando nisso. Por favor, tente novamente mais tarde."],500)->header('Content-type', 'application/json');
            }
        }
    }
}
