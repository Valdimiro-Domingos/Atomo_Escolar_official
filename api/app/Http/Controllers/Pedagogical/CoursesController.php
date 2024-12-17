<?php

namespace App\Http\Controllers\Pedagogical;

use App\Http\Controllers\Controller;
use App\Http\Resources\CourseResource;
use App\Models\Company;
use App\Models\Course;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CoursesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $courses = Course::where('company_id',  '=',Auth::user()->company->id)->get();
            return response(
                ['courses'=>CourseResource::collection($courses)],200
            )->header('Content-Type', 'application/json');
        } catch (Exception $th) {
            return response(['courses'=> $th->getMessage()],400)->header('Content-type', 'application/json');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            
            $course = new Course();
            $course->designation = $request->designation;
            $course->description = $request->description;
            $course->company_id = Auth::user()->company->id;
            $course->save();
            return response(
                ['course'=>new CourseResource($course)],200
            )->header('Content-Type', 'application/json');

        } catch (Exception $th) {
            return response(['courses'=> $th->getMessage()],400)->header('Content-type', 'application/json');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $course = Course::findOrfail(intval($id));
            return response(
                ['course'=>new CourseResource($course)],200
            )->header('Content-Type', 'application/json');
        } catch (Exception $th) {
            return response(['courses'=> $th->getMessage()],400)->header('Content-type', 'application/json');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $course = Course::findOrfail(intval($id));
            $course->designation = $request->designation;
            $course->description = $request->description;
            $course->save();
            return response(
                ['course'=> CourseResource::collection(Course::where('company_id',  '=',Auth::user()->company->id)->get())],200
            )->header('Content-Type', 'application/json');

        } catch (Exception $th) {
            return response(['courses'=> $th->getMessage()],500)->header('Content-type', 'application/json');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $course = Course::findOrfail(intval($id));
            $course->delete();
            return response(
                ['course'=>CourseResource::collection(Course::where('company_id',  '=',Auth::user()->company->id)->get())],200
            )->header('Content-Type', 'application/json');

        } catch (Exception $th) {
            return response(['courses'=> $th->getMessage()],400)->header('Content-type', 'application/json');
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function changeStatus(Request $request, string $id){
        try {
            $course = Course::findOrfail(intval($id));
            $course->status = $request->status;
            $course->save();
            return response(
                ['course'=>CourseResource::collection(Course::where('company_id',  '=',Auth::user()->company->id)->get())],200
            )->header('Content-Type', 'application/json');

        } catch (Exception $th) {
            return response(['courses'=> $th->getMessage()],400)->header('Content-type', 'application/json');
        }
    }
}
