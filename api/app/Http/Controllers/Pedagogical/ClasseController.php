<?php

namespace App\Http\Controllers\Pedagogical;

use App\Http\Controllers\Controller;
use App\Http\Resources\ClasseResource;
use App\Models\Classes;
use App\Models\Enrollment;
use App\Models\Schedule;
use App\Models\Company;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClasseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $classe = Classes::where('company_id',  Auth::user()->company_id)->get();
        return response(
            ['classe'=>ClasseResource::collection($classe)],200
        )->header('Content-Type', 'application/json');
        } catch (Exception $th) {
            return response(['classe'=> $th->getMessage(), "message"=>"Desculpe pelo transtorno. Houve um erro. Estamos trabalhando nisso. Por favor, tente novamente mais tarde."],500)->header('Content-type', 'application/json');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {

            $classe = Classes::create([
                'designation' => $request->input('designation'),
                'description' => $request->input('description'),
                'company_id' => Auth::user()->company->id,
            ]);
            return response(['classe'=>ClasseResource::collection(Classes::where('company_id',  '=',Auth::user()->company->id)->get())],200)->header('Content-Type', 'application/json');

        } catch (Exception $th) {
            return response(['classe'=> $th->getMessage(), "message"=>"Desculpe pelo transtorno. Houve um erro. Estamos trabalhando nisso. Por favor, tente novamente mais tarde."],500)->header('Content-type', 'application/json');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $classe = Classes::findOrFail(intval($id));
        return response(
            ['classe'=>ClasseResource::make($classe)],200
        )->header('Content-Type', 'application/json');
        } catch (Exception $th) {
            return response(['classe'=> $th->getMessage(), "message"=>"Desculpe pelo transtorno. Houve um erro. Estamos trabalhando nisso. Por favor, tente novamente mais tarde."],500)->header('Content-type', 'application/json');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {

            $classe = Classes::findOrFail(intval($id));
            $classe->designation = $request->input('designation');
            $classe->description = $request->input('description');
            $classe->save();
            return response(['classe'=>ClasseResource::collection(Classes::where('company_id',  '=',Auth::user()->company->id)->get())],200)->header('Content-Type', 'application/json');

        } catch (Exception $th) {
            return response(['classe'=> $th->getMessage(), "message"=>"Desculpe pelo transtorno. Houve um erro. Estamos trabalhando nisso. Por favor, tente novamente mais tarde."],500)->header('Content-type', 'application/json');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
        
           if(Enrollment::where('company_id', Auth::user()->company_id)->where('classe_id', $id)->count() >  0 || 
                    Schedule::where('company_id', Auth::user()->company_id)->where('classe_id', $id)->count() > 0) {
                    return response()->json(["message" => "Não podemos eliminar este registro, obrigado!"], 500);
                }
                
                
            $classe = Classes::findOrFail(intval($id));
            $classe->delete();
            return response(['classe'=>ClasseResource::collection(Classes::where('company_id',  '=',Auth::user()->company->id)->get())],200)->header('Content-Type', 'application/json');

        } catch (Exception $th) {
            if($th->getCode() == 23000){
                return response(['classe'=>$th->getMessage(), "message"=>"Não é possível excluir esta classe devido a registros associados. Por favor, remova ou atualize esses registros antes de tentar excluir a classe novamente."],500)->header('Content-type', 'application/json');
            }else{
                return response(['classe'=> $th->getMessage(), "message"=>"Desculpe pelo transtorno. Houve um erro. Estamos trabalhando nisso. Por favor, tente novamente mais tarde."],500)->header('Content-type', 'application/json');
            }
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function changeStatus(Request $request, String $id){
        try {
            $classe = Classes::findOrFail(intval($id));
            $classe->status = $request->input('status');
            $classe->save();
            return response(['classe'=>ClasseResource::collection(Classes::where('company_id',  '=',Auth::user()->company->id)->get())],200)->header('Content-Type', 'application/json');

        } catch (Exception $th) {
            return response([
                'classe'=> $th->getMessage(),
                "message"=>"Desculpe pelo transtorno. Houve um erro. Estamos trabalhando nisso. Por favor, tente novamente mais tarde."
            ],
            500)->header('Content-type', 'application/json');
         }
    }
}
