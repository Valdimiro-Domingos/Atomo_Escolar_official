<?php

namespace App\Http\Controllers\Pedagogical;

use App\Http\Controllers\Controller;
use App\Http\Resources\PeriodResource;
use App\Models\Period;
use App\Models\Company;
use Exception;
use Illuminate\Http\Request;
use App\Models\Enrollment;
use App\Models\Schedule;
use Illuminate\Support\Facades\Auth;

class PeriodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $period = Period::where('company_id' ,Auth::user()->company_id)->get();
        return response(
            ['periods'=>PeriodResource::collection($period)],200
        )->header('Content-Type', 'application/json');
        } catch (Exception $th) {
            return response([
                'periods'=> $th->getMessage(), 
                "message"=>"Desculpe pelo transtorno. Houve um erro. Estamos trabalhando nisso. Por favor, tente novamente mais tarde."
            ],500)
            ->header('Content-type', 'application/json');
        }
    }
    
    
    public function init(){
        $period = Period::where('company_id' ,Auth::user()->company->id)->get();
        return response(
            PeriodResource::collection($period),200
        )->header('Content-Type', 'application/json');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            
            $period = Period::create([
              'time_start' => $request->input('time_start'),
                'time_end' => $request->input('time_end'),
                'designation' => $request->input('designation'),
                'description' => $request->input('description'),
                'company_id' => Auth::user()->company->id,
            ]);
            return response(['periods'=>PeriodResource::collection(Period::where('company_id',  '=',Auth::user()->company->id)->get())],200)->header('Content-Type', 'application/json');

        } catch (Exception $th) {
                       return response(['periods'=> $th->getMessage(), "message"=>"Desculpe pelo transtorno. Houve um erro. Estamos trabalhando nisso. Por favor, tente novamente mais tarde."],500)->header('Content-type', 'application/json');

        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $period = Period::findOrFail($id);
        return response(
            ['period'=>PeriodResource::make($period)],200
        )->header('Content-Type', 'application/json');
        } catch (Exception $th) {
            return response(['period'=> $th->getMessage(), "message"=>"Desculpe pelo transtorno. Houve um erro. Estamos trabalhando nisso. Por favor, tente novamente mais tarde."],500)->header('Content-type', 'application/json');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {

            $period = Period::findOrFail($id);
            $period->time_start = $request->input('time_start');
            $period->time_end = $request->input('time_end');
            $period->designation = $request->input('designation');
            $period->description = $request->input('description');
            $period->save();
            return response(['period'=>PeriodResource::collection(Period::where('company_id',  '=',Auth::user()->company->id)->get())],200)->header('Content-Type', 'application/json');

        } catch (Exception $th) {
            return response(['period'=> $th->getMessage(), "message"=>"Desculpe pelo transtorno. Houve um erro. Estamos trabalhando nisso. Por favor, tente novamente mais tarde."],500)->header('Content-type', 'application/json');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    {
        try {
            $period = Period::findOrFail($id);
                
            if(Enrollment::where('company_id' ,Auth::user()->company_id)->where('period_id', $id)->count() >  0 || Schedule::where('company_id' ,Auth::user()->company_id)->where('period_id', $id)->count() > 0) {
                return response()->json(["message" => "Não podemos eliminar este registro, obrigado!"], 500);
            }else{
                $period->delete();
            }
            return response(['period'=>PeriodResource::collection(Period::where('company_id',  Auth::user()->company_id)->get())],200)->header('Content-Type', 'application/json');

        } catch (Exception $th) {
            if($th->getCode() == 23000){
                return response(['classe'=>$th->getMessage(), "message"=>"Não é possível excluir esta classe devido a registros associados. Por favor, remova ou atualize esses registros antes de tentar excluir a classe novamente."],500)->header('Content-type', 'application/json');
            }else{
                return response(['classe'=> $th->getMessage(), "message"=>"Desculpe pelo transtorno. Houve um erro. Estamos trabalhando nisso. Por favor, tente novamente mais tarde."],500)->header('Content-type', 'application/json');
            }
        }
    }
}
