<?php

namespace App\Http\Controllers\Config\Company;

use App\Http\Controllers\Controller;
use App\Http\Resources\DepartamnetResource;
use App\Models\Company;
use App\Models\Departament;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class DepartamentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departaments = Departament::where('company_id',  '=',Auth::user()->company->id)->get();
        return response(['departaments'=>DepartamnetResource::collection($departaments)],200)->header('Content-Type', 'application/json');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            
            $departament = Departament::create([
                'designation' => $request->input('designation'),
                'description' => $request->input('description'),
                'status' => '1',
                'company_id' => Auth::user()->company->id,
            ]);
            return response(['departaments'=>DepartamnetResource::collection(Departament::where('company_id',  '=',Auth::user()->company->id)->get())],200)->header('Content-Type', 'application/json');

        } catch (Exception $th) {
            return response(['departament'=> $th->getMessage()],500)->header('Content-type', 'application/json');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $departament = Departament::findOrFail($id);
        return response(['departament'=>DepartamnetResource::make($departament)],200)->header('Content-Type', 'application/json');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $departament = Departament::findOrFail($id);
            $departament->update([
                'designation' => $request->input('designation'),
                'description' => $request->input('description'), 
            ]);
            return response(['departament'=>DepartamnetResource::collection(Departament::where('company_id',  '=',Auth::user()->company->id)->get())],200)->header('Content-Type', 'application/json');

        } catch (Exception $th) {
            return response(['departament'=> $th->getMessage()],500)->header('Content-type', 'application/json');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $departament = Departament::findOrFail($id);
   
            if(User::where('departament_id', $id)->count() == 0){
                $departament->delete();
            }else{
                return response()->json(["message"=>"Existe usuario com este perfil"], 500);
            }
        
            return response(['departament'=>DepartamnetResource::collection(Departament::where('company_id',  '=',Auth::user()->company->id)->get())],200)->header('Content-Type', 'application/json');

        } catch (Exception $th) {
            return response(['departament'=> $th->getMessage()],500)->header('Content-type', 'application/json');
        }
    }
}
