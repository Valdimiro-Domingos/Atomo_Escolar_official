<?php

namespace App\Http\Controllers\Pedagogical;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Http\Resources\TrimestreResource;
use App\Models\Trimestre;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrimestreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $trimestre = Trimestre::where('company_id',  '=',Auth::user()->company->id)->get();
            return response(
                ['trimestres'=> TrimestreResource::collection($trimestre)],200
            )->header('Content-Type', 'application/json');
        } catch (Exception $th) {
            return response(['trimestres'=> $th->getMessage()],400)->header('Content-type', 'application/json');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            
            $trimestre = new Trimestre();
            $trimestre->designation = $request->input('designation');
            $trimestre->description = $request->input('description'); 
            $trimestre->company_id = Auth::user()->company->id;
            $trimestre->save();
            return response(
                ['trimestre'=> TrimestreResource::collection(Trimestre::where('company_id',  '=',Auth::user()->company->id)->get())],200
            )->header('Content-Type', 'application/json');
        } catch (Exception $th) {
            return response(['trimestre'=> $th->getMessage()],400)->header('Content-type', 'application/json');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $trimestre = Trimestre::find($id);
            return response(
                ['trimestre'=>  TrimestreResource::make($trimestre)],200
            )->header('Content-Type', 'application/json');
        } catch (Exception $th) {
            return response(['trimestre'=> $th->getMessage()],400)->header('Content-type', 'application/json');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    { 
        try {
            $trimestre = Trimestre::findOrfail($id);
            $trimestre->designation = $request->input('designation');
            $trimestre->description = $request->input('description');  
            $trimestre->save();
            return response(
                ['trimestre'=> TrimestreResource::collection(Trimestre::where('company_id',  '=',Auth::user()->company->id)->get())],200
            )->header('Content-Type', 'application/json');
        } catch (Exception $th) {
            return response(['trimestre'=> $th->getMessage()],400)->header('Content-type', 'application/json');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $trimestre = Trimestre::find($id);
            $trimestre->delete();
            return response(
                ['trimestre'=> TrimestreResource::collection(Trimestre::where('company_id',  '=',Auth::user()->company->id)->get())],200
            )->header('Content-Type', 'application/json');
        } catch (Exception $th) {
            return response(['trimestre'=> $th->getMessage()],400)->header('Content-type', 'application/json');
        }
    }
    public function changeStatus(Request $request,string $id){
        try {
            $trimestre = Trimestre::findOrfail(intval($id));
            $trimestre->status = $request->input('status');
            $trimestre->save();
            return response(
                ['trimestre'=>TrimestreResource::make($trimestre)],200
            )->header('Content-Type', 'application/json');
        } catch (Exception $th) {
            return response(['trimestre'=> $th->getMessage()],400)->header('Content-type', 'application/json');
        }
    }
}
