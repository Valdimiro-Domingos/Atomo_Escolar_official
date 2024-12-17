<?php

namespace App\Http\Controllers\secretary;

use App\Http\Controllers\Controller;
use App\Http\Resources\EnrollmentResource;
use App\Models\Classes;
use App\Models\ClassRoom;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\EnrollmentRegistation;
use App\Models\Period;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\Turma;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EnrollmentRegistationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $enrollment =  Enrollment::with('class_room','course','period','classe','turma','student','school_year')
                ->where('company_id', Auth::user()->company_id)
                ->get();
            return response(
                ['enrollment'=> EnrollmentResource::collection($enrollment)],200
            )->header('Content-Type', 'application/json');
        } catch (Exception $th) {
            return response(['enrollment'=> $th->getMessage()],404)->header('Content-type', 'application/json');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    //  dropout
    public function store(Request $request)
    {
        try {
            
            $enrollment_number = null;
            $class_room = ClassRoom::findOrfail($request->input('class_room_id'));
            $turma = Turma::findOrfail($request->input('turma_id'));
            $classe = Classes::findOrfail($request->input('classe_id'));
            $school_year = SchoolYear::findOrfail($request->input('school_year_id'));
            $period = Period::findOrfail($request->input('period_id'));
            $course = Course::findOrfail($request->input('course_id'));

            DB::beginTransaction();

            $enrollment =  Enrollment::findOrfail(intval($request->input('enrollment_id')));
            $enrollment->status = '0';
            $enrollment_number = $enrollment->enrollment_number;
            $enrollment->save();
            
            
            
            
            EnrollmentRegistation::create([
                "company_id" => Auth::user()->company_id,
                "enrollment_id" => $enrollment->id,
                "student_id" => $enrollment->student_id,
                "status" => "1",
            ]);

            $student = Student::findOrfail($request->input('student_id'));
            $student->name = $request->input('name');
            $student->identity = $request->input('identity');
            $student->gender = mb_strtoupper($request->input('gender'));
            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $filename = time() .'.'. $file->getClientOriginalExtension();
                $file->move(public_path('secretary/enrollment'), $filename);
                $student->photo = $filename;
            }
            $student->mother_name = isset($request->mother_name) ? $request->mother_name: null;
            $student->father_name = ($request->father_name) ? $request->father_name: null;
            $stu = $student->save();

            $enrollments = new Enrollment();
            $enrollments->company_id = Auth::user()->company->id;
            $enrollments->class_room_id = $class_room->id;
            $enrollments->period_id = $period->id;
            $enrollments->school_year_id = $school_year->id;
            $enrollments->classe_id = $classe->id;
            $enrollments->turma_id = $turma->id;
            $enrollments->course_id = $course->id;
            $enrollments->student_id = $student->id;
            $enrollments->enrollment_number = $enrollment_number ;
            $enrollments->status = '1';
            $enrollments->message = 'matricula';
            $enrol = $enrollments->save();

            if($stu && $enrol){
                DB::commit();
                return response(
                    [
                        'enrollment'=> EnrollmentResource::collection(Enrollment::with('class_room','course','period','classe','turma','student','school_year')
                        ->where('company_id', '=',Auth::user()->company->id)->get()),
                        "message"=>"Confirmação feita com sucesso!"
                        ],200
                )->header('Content-Type', 'application/json');
            }
        } catch (Exception $th) {
            DB::rollBack();
            return response([
                'enrollment'=> $th->getMessage(),
                "message"=>"Desculpe pelo transtorno. Houve um erro. Estamos trabalhando nisso. Por favor, tente novamente mais tarde."
            ],500)
            ->header('Content-type', 'application/json');
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $enrollment =  Enrollment::with('enrollment.class_room','enrollment.course','enrollment.period','enrollment.classe','enrollment.turma','enrollment.school_year')->findOrfail($id);
            return response(
                ['registation'=> EnrollmentResource::make($enrollment)],200
            )->header('Content-Type', 'application/json');
        } catch (Exception $th) {
            return response(['registation'=> $th->getMessage()],404)->header('Content-type', 'application/json');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $enrollment = Enrollment::findOrFail($id);
            $enrollment->update([
                'enrollment_id'=> $request->input('enrollment_id'),
            ]);
            return response(
                ['registation'=> EnrollmentResource::collection(EnrollmentRegistation::with('enrollment.class_room','enrollment.course','enrollment.period','enrollment.classe','enrollment.turma','enrollment.school_year')->get())],200
            )->header('Content-Type', 'application/json');
        } catch (Exception $th) {
            return response(['registation'=> $th->getMessage()],404)->header('Content-type', 'application/json');
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $enrollment = Enrollment::findOrFail($id);
            $enrollment->delete();
            return response(
                ['registation'=> EnrollmentResource::collection(EnrollmentRegistation::with('enrollment.class_room','enrollment.course','enrollment.period','enrollment.classe','enrollment.turma','enrollment.school_year')->get())],200
            )->header('Content-Type', 'application/json');
        } catch (Exception $th) {
            return response(['registation'=> $th->getMessage()],404)->header('Content-type', 'application/json');
        }
    }
}
