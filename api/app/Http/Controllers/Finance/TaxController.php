<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaxResource;
use App\Models\Company;
use App\Models\Tax;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaxController extends Controller
{
    public function index()
    {
        try {
            $taxes = Tax::where('company_id',  '=' ,Auth::user()->company->id)->get();
        return response(
            ['taxes'=>TaxResource::collection($taxes)],200
        )->header('Content-Type', 'application/json');
        } catch (Exception $th) {
            return response(['taxes'=> $th->getMessage()],500)->header('Content-type', 'application/json');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {

            $tax = Tax::create([
                'designation' => $request->input('designation'),
                'description' => $request->input('description'),
                'company_id' => Auth::user()->company->id,
            ]);
            return response(['taxes'=>TaxResource::collection(Tax::where('company_id',  '=',Auth::user()->company->id)->get())],200)->header('Content-Type', 'application/json');

        } catch (Exception $th) {
            return response(['taxes'=> $th->getMessage()],500)->header('Content-type', 'application/json');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $tax = Tax::findOrFail(intval($id));
        return response(
            ['tax'=>TaxResource::make($tax)],200
        )->header('Content-Type', 'application/json');
        } catch (Exception $th) {
            return response(['tax'=> $th->getMessage()],400)->header('Content-type', 'application/json');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {

            $tax = Tax::findOrFail(intval($id));
            $tax->designation = $request->input('designation');
            $tax->description = $request->input('description');
            $tax->save();
            return response(['tax'=>TaxResource::collection(Tax::where('company_id',  '=',Auth::user()->company->id)->get())],200)->header('Content-Type', 'application/json');

        } catch (Exception $th) {
            return response(['tax'=> $th->getMessage()],500)->header('Content-type', 'application/json');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $tax = Tax::findOrFail(intval($id));
            $tax->delete();
            return response(['taxs'=>TaxResource::collection(Tax::where('company_id',  '=',Auth::user()->company->id)->get())],200)->header('Content-Type', 'application/json');

        } catch (Exception $th) {
            return response(['taxs'=> $th->getMessage()],500)->header('Content-type', 'application/json');
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function changeStatus(Request $request, String $id){
        try {
            $tax = Tax::findOrFail(intval($id));
            $tax->status = $request->input('status');
            $tax->save();
            return response(['taxs'=>TaxResource::collection(Tax::where('company_id',  '=',Auth::user()->company->id)->get())],200)->header('Content-Type', 'application/json');

        } catch (Exception $th) {
            return response(['taxs'=> $th->getMessage()],500)->header('Content-type', 'application/json');
         }
    }

}
