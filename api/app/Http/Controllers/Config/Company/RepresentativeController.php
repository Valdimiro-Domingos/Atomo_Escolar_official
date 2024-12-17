<?php

namespace App\Http\Controllers\Config\Company;

use App\Http\Controllers\Controller;
use App\Http\Resources\RepresentativeResource;
use App\Models\Representatives;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RepresentativeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $representatives = Representatives::where('id',  Auth::user()->company->id)->first();
            return response(['representatives'=>RepresentativeResource::collection($representatives)],200)->header('Content-Type', 'application/json');
        } catch (Exception $th) {
            return response([
                "message"=>"Desculpe pelo transtorno. Houve um erro. Estamos trabalhando nisso. Por favor, tente novamente mais tarde.",
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
           
        
        try {
            $representative = new Representatives();
            $representative->general_manager = $request->input('general_manager');
            $representative->pedagogical_manager = $request->input('pedagogical_manager');
            $representative->provincial_manager = $request->input('provincial_manager');
            $representative->municipal_manager = $request->input('municipal_manager');
            $representative->company_id =  Auth::user()->company->id; 
            $representatives = Representatives::findOrfail(intval($id));
            return response(['representatives'=>RepresentativeResource::make($representatives)],200)->header('Content-Type', 'application/json');
        } catch (Exception $th) {
            return response([
                "message"=>"Desculpe pelo transtorno. Houve um erro. Estamos trabalhando nisso. Por favor, tente novamente mais tarde.",
            ], 500);
        }
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $representatives = Representatives::findOrfail(intval($id));
            return response(['representatives'=>RepresentativeResource::make($representatives)],200)->header('Content-Type', 'application/json');
        } catch (Exception $th) {
            return response([
                "message"=>"Desculpe pelo transtorno. Houve um erro. Estamos trabalhando nisso. Por favor, tente novamente mais tarde.",
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
