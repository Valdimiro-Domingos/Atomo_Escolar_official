<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Http\Resources\RetentionResource;
use App\Models\Company;
use App\Models\Retention;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RetentionController extends Controller
{
    public function index()
    {
        try {
            $retentions = Retention::where('company_id',  '=',Auth::user()->company->id)->get();
        return response(
            ['retentions'=>RetentionResource::collection($retentions)],200
        )->header('Content-Type', 'application/json');
        } catch (Exception $th) { 
            return response(['retentions'=> $th->getMessage()],500)->header('Content-type', 'application/json');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            
            $retention = Retention::create([
                'designation' => $request->input('designation'),
                'description' => $request->input('description'),
                'company_id' => Auth::user()->company->id,
            ]);
            return response(['retention'=>RetentionResource::collection(Retention::where('company_id',  '=',Auth::user()->company->id)->get())],200)->header('Content-Type', 'application/json');

        } catch (Exception $th) {
            return response(['retention'=> $th->getMessage()],500)->header('Content-type', 'application/json');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $retention = Retention::findOrFail(intval($id));
        return response(
            ['retention'=>RetentionResource::make($retention)],200
        )->header('Content-Type', 'application/json');
        } catch (Exception $th) {
            return response(['retention'=> $th->getMessage()],400)->header('Content-type', 'application/json');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {

            $retention = Retention::findOrFail(intval($id));
            $retention->designation = $request->input('designation');
            $retention->description = $request->input('description');
            $retention->save();
            return response(['retention'=>RetentionResource::collection(Retention::where('company_id',  '=',Auth::user()->company->id)->get())],200)->header('Content-Type', 'application/json');

        } catch (Exception $th) {
            return response(['retention'=> $th->getMessage()],500)->header('Content-type', 'application/json');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $retention = Retention::findOrFail(intval($id));
            $retention->delete();
            return response(['retention'=>RetentionResource::collection(Retention::where('company_id',  '=',Auth::user()->company->id)->get())],200)->header('Content-Type', 'application/json');

        } catch (Exception $th) {
            return response(['retention'=> $th->getMessage()],500)->header('Content-type', 'application/json');
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function changeStatus(Request $request, String $id){
        try {
            $retention = Retention::findOrFail(intval($id));
            $retention->status = $request->input('status');
            $retention->save();
            return response(['retention'=>RetentionResource::collection(Retention::where('company_id',  '=',Auth::user()->company->id)->get())],200)->header('Content-Type', 'application/json');

        } catch (Exception $th) {
            return response(['retention'=> $th->getMessage()],500)->header('Content-type', 'application/json');
         }
    }

}
