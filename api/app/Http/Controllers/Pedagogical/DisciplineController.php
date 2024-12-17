<?php

namespace App\Http\Controllers\Pedagogical;

use App\Http\Controllers\Controller;
use App\Http\Resources\DisciplineResource;
use App\Models\Discipline;
use App\Models\Company;
use Exception;
use APP\Models\MiniShedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;




class DisciplineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $discipline = Discipline::where('company_id',  '=',Auth::user()->company->id)->get();
            return response(
                ['disciplines'=>DisciplineResource::collection($discipline)],200
            )->header('Content-Type', 'application/json');
        } catch (Exception $th) {
            return response(['disciplines'=> $th->getMessage()],500)->header('Content-type', 'application/json');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {            
            $discipline = Discipline::create([
                'designation' => $request->input('designation'),
                'description' => $request->input('description'),
                'company_id' => Auth::user()->company->id,
            ]);
            return response(['disciplines'=>DisciplineResource::collection(Discipline::where('company_id',  '=',Auth::user()->company->id)->get())],200)->header('Content-Type', 'application/json');

        } catch (Exception $th) {
            return response(['disciplines'=> $th->getMessage()],500)->header('Content-type', 'application/json');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $discipline = Discipline::findOrFail(intval($id));
        return response(
            ['disciplines'=>DisciplineResource::make($discipline)],200
        )->header('Content-Type', 'application/json');
        } catch (Exception $th) {
            return response(['disciplines'=> $th->getMessage()],400)->header('Content-type', 'application/json');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {

            $discipline = Discipline::findOrFail(intval($id));
            $discipline->designation = $request->input('designation');
            $discipline->description = $request->input('description');
            $discipline->save();
            return response(['disciplines'=>DisciplineResource::collection(Discipline::where('company_id',  '=',Auth::user()->company->id)->get())],200)->header('Content-Type', 'application/json');

        } catch (Exception $th) {
            return response(['disciplines'=> $th->getMessage()],500)->header('Content-type', 'application/json');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            
            
             if(MiniShedule::where('company_id' ,Auth::user()->company_id)->where('discipline_id', $id)->count() >  0) {
                    return response()->json(["message" => "Não podemos eliminar este registro, obrigado!"], 500);
            }
            
            
            $discipline = Discipline::where('company_id' ,Auth::user()->company_id)->findOrFail(intval($id));
            $discipline->delete();
            return response(['disciplines'=>DisciplineResource::collection(Discipline::where('company_id',  '=',Auth::user()->company->id)->get())],200)->header('Content-Type', 'application/json');

        } catch (Exception $th) {
            return response(['disciplines'=> $th->getMessage()],500)->header('Content-type', 'application/json');
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function changeStatus(Request $request, String $id){
        try {
            $discipline = Discipline::findOrFail(intval($id));
            $discipline->status = $request->input('status');
            $discipline->save();
            return response(['disciplines'=>DisciplineResource::collection(Discipline::where('company_id',  '=',Auth::user()->company->id)->get())],200)->header('Content-Type', 'application/json');

        } catch (Exception $th) {
            return response(['disciplines'=> $th->getMessage()],400)->header('Content-type', 'application/json');
         }
    }
}
