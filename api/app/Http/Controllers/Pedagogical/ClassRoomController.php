<?php

namespace App\Http\Controllers\Pedagogical;

use App\Http\Controllers\Controller;
use App\Http\Resources\ClassRoomResource;
use App\Models\ClassRoom;
use App\Models\Schedule;
use App\Models\Enrollment;
use App\Models\Company;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClassRoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $classrooms = ClassRoom::where('company_id',  '=',Auth::user()->company->id)->get();
            return response(
                ['classrooms'=>ClassRoomResource::collection($classrooms)],200
            )->header('Content-Type', 'application/json');
        } catch (Exception $th) {
            return response(['classrooms'=> $th->getMessage()],400)->header('Content-type', 'application/json');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            
            $classroom = new ClassRoom();
            $classroom->designation = $request->designation;
            $classroom->description = $request->description;
            $classroom->company_id = Auth::user()->company->id;
            $classroom->save();
            return response(
                ['classroom'=> ClassRoomResource::collection(ClassRoom::where('company_id',  '=',Auth::user()->company->id)->get())],200
            )->header('Content-Type', 'application/json');

        } catch (Exception $th) {
            return response(['classrooms'=> $th->getMessage()],500)->header('Content-type', 'application/json');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $classroom = ClassRoom::findOrfail(intval($id));
            return response(
                ['classroom'=>ClassRoomResource::make($classroom)],200
            )->header('Content-Type', 'application/json');
        } catch (Exception $th) {
            return response(['classrooms'=> $th->getMessage()],400)->header('Content-type', 'application/json');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $classroom = ClassRoom::findOrfail(intval($id));
            $classroom->designation = $request->designation;
            $classroom->description = $request->description;
            $classroom->save();
            return response(
                ['classroom'=> ClassRoomResource::collection(ClassRoom::where('company_id',  '=',Auth::user()->company->id)->get())],200
            )->header('Content-Type', 'application/json');

        } catch (Exception $th) {
            return response(['classrooms'=> $th->getMessage()],500)->header('Content-type', 'application/json');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
                
                if(Enrollment::where('company_id', Auth::user()->company_id)->where('class_room_id', $id)->count() >  0 || 
                    Schedule::where('company_id', Auth::user()->company_id)->where('class_room_id', $id)->count() > 0) {
                    return response()->json(["message" => "Não podemos eliminar este registro, obrigado!"], 500);
                }
        
            $classroom = ClassRoom::findOrfail(intval($id));
            $classroom->delete();
            return response(
                ['classroom'=>ClassRoomResource::collection(ClassRoom::where('company_id',  '=',Auth::user()->company->id)->get())],200
            )->header('Content-Type', 'application/json');

        } catch (Exception $th) {
            return response(['classrooms'=> $th->getMessage()],400)->header('Content-type', 'application/json');
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function changeStatus(Request $request, string $id){
        try {
            $classroom = ClassRoom::findOrfail(intval($id));
            $classroom->status = $request->status;
            $classroom->save();
            return response(
                ['classroom'=>ClassRoomResource::collection(ClassRoom::where('company_id',  '=',Auth::user()->company->id)->get())],200
            )->header('Content-Type', 'application/json');

        } catch (Exception $th) {
            return response(['classrooms'=> $th->getMessage()],500)->header('Content-type', 'application/json');
        }
    }
}
