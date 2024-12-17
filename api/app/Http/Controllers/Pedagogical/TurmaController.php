<?php

namespace App\Http\Controllers\Pedagogical;

use App\Http\Controllers\Controller;
use App\Http\Resources\TurmaResource;
use App\Models\Company;
use App\Models\Turma;
use Illuminate\Http\Request;
use App\Models\Classes;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ClasseResource;
use App\Http\Resources\SchoolYearResource;
use App\Models\SchoolYear;

use App\Models\Course;
use App\Http\Resources\CourseResource;

use App\Http\Resources\PeriodResource;
use App\Models\Period;



// gerador de relatorios por turma
use PDF;
use App\Http\Resources\EnrollmentResource;
use App\Models\Enrollment;
use App\Models\Schedule;




class TurmaController extends Controller
{
      public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['relatorio']);
    }



    public function index()
    {
        try {
            $turma = Turma::where('company_id',Auth::user()->company_id)->get();
            return response(
                ['turmas'=> TurmaResource::collection($turma)],200
            )->header('Content-Type', 'application/json');
        } catch (\Exception $e) {
            return response(['turmas'=> $e->getMessage()],400)->header('Content-type', 'application/json');

        }
    }


    public function view()
    {
        try {
            return response(
                ['turmas'=> TurmaResource::collection(Turma::where('company_id',Auth::user()->company_id)->get()),
                'classes'=>ClasseResource::collection( Classes::where('company_id', Auth::user()->company_id)->get()),
                'school_year'=> SchoolYearResource::collection(SchoolYear::where('company_id',Auth::user()->company_id)->get()),
                'courses'=>CourseResource::collection(Course::where('company_id',  Auth::user()->company->id)->get()),
                'company_id'=> Auth::user()->company_id
                ],200
            )->header('Content-Type', 'application/json');
        } catch (\Exception $e) {
            return response(['turmas'=> $e->getMessage()],400)->header('Content-type', 'application/json');

        }
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {

            $turma = new Turma();
            $turma->designation = $request->input('designation');
            $turma->description = $request->input('description');
            $turma->company_id = Auth::user()->company->id;
            $turma->save();
            return response(
                ['turma'=>  TurmaResource::collection(Turma::where('company_id',  '=',Auth::user()->company->id)->get())],200
            )->header('Content-Type', 'application/json');
        } catch (\Exception $e) {
            return response(['turma'=> $e->getMessage()],400)->header('Content-type', 'application/json');

        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $turma = Turma::findOrfail(intval($id));
            return response(
                ['turma'=>  TurmaResource::make($turma)],200
            )->header('Content-Type', 'application/json');
        } catch (\Exception $e) {
            return response(['turma'=> $e->getMessage()],400)->header('Content-type', 'application/json');

        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $turma = Turma::findOrfail(intval($id));
            $turma->designation = $request->input('designation');
            $turma->description = $request->input('description');
            $turma->save();
            return response(
                ['turma'=>  TurmaResource::collection(Turma::where('company_id',  '=',Auth::user()->company->id)->get())],200
            )->header('Content-Type', 'application/json');
        } catch (\Exception $e) {
            return response(['turma'=> $e->getMessage()],400)->header('Content-type', 'application/json');

        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $turma = Turma::where('company_id', Auth::user()->company_id)->findOrfail(intval($id));
            
            if(Enrollment::where('company_id', Auth::user()->company_id)->where('turma_id', $id)->count() >  0 || Schedule::where('company_id', Auth::user()->company_id)->where('turma_id', $id)->count() > 0) {
                return response()->json(["message" => "Não podemos eliminar este registro, obrigado!"], 500);
            }else{
                $turma->delete();
            }
            
            return response(
                ['turma'=>  TurmaResource::collection(Turma::where('company_id', Auth::user()->company_id)->get())],200
            )->header('Content-Type', 'application/json');
        } catch (\Exception $e) {
            return response(['turma'=> $e->getMessage()],400)->header('Content-type', 'application/json');

        }
    }
    public function changeStatus(Request $request, string $id){
        try {
            $turma = Turma::findOrfail(intval($id));
            $turma->status = $request->input('status');
            $turma->save();
            return response(
                ['turma'=>  TurmaResource::collection(Turma::where('company_id',  '=',Auth::user()->company->id)->get())],200
            )->header('Content-Type', 'application/json');
        } catch (\Exception $e) {
            return response(['turma'=> $e->getMessage()],400)->header('Content-type', 'application/json');

        }
    }
}
