<?php

namespace App\Http\Controllers\secretary;

use App\Http\Controllers\Controller;
use App\Http\Resources\SchoolYearResource;
use App\Models\SchoolYear;
use App\Models\Company;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SchoolYearController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $schoolYear = SchoolYear::where('company_id',Auth::user()->company_id)->get();
            return response(
                ['school_year'=> SchoolYearResource::collection($schoolYear)],200
            )->header('Content-Type', 'application/json');
        } catch (Exception $th) {
            return response(['school_year'=> $th->getMessage()],400)->header('Content-type', 'application/json');
        }


    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {

            $school_year = new SchoolYear();
            $school_year->designation = $request->input('designation');
            $school_year->description = $request->input('description');
            $school_year->company_id = Auth::user()->company->id;
            $school_year->save();
            return response(
                ['school_year'=> SchoolYearResource::collection(SchoolYear::get())],200
            )->header('Content-Type', 'application/json');
        } catch (Exception $th) {
            return response(['school_year'=> $th->getMessage()],404)->header('Content-type', 'application/json');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $schoolYear = SchoolYear::findOrFail(intval($id));
            return response(
                ['school_year'=>  SchoolYearResource::make($schoolYear)],200
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
            $schoolYear = SchoolYear::findOrFail(intval($id));
            $schoolYear->designation = $request->input('designation');
            $schoolYear->description = $request->input('description');
            $schoolYear->save();
            return response(
                ['school_year'=> SchoolYearResource::collection(SchoolYear::where('company_id',Auth::user()->company_id)->get())],200
            )->header('Content-Type', 'application/json');
        } catch (Exception $th) {
            return response(['school_year'=> $th->getMessage()],404)->header('Content-type', 'application/json');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $schoolYear = SchoolYear::findOrFail(intval($id));
            $schoolYear->delete();
            return response(
                ['school_year'=> SchoolYearResource::collection(SchoolYear::where('company_id',Auth::user()->company_id)->get())],200
            )->header('Content-Type', 'application/json');
        } catch (Exception $th) {
            return response(['school_year'=> $th->getMessage()],404)->header('Content-type', 'application/json');
        }
    }

    public function changeStatus(Request $request, string $id){
        try {
            $schoolYear = SchoolYear::findOrFail(intval($id));
            $schoolYear->designation = $request->input('status');
            $schoolYear->save();
            return response(
                ['school_year'=> SchoolYearResource::collection(SchoolYear::where('company_id',Auth::user()->company_id)->get())],200
            )->header('Content-Type', 'application/json');
        } catch (Exception $th) {
            return response(['school_year'=> $th->getMessage()],404)->header('Content-type', 'application/json');
        }

    }

}
