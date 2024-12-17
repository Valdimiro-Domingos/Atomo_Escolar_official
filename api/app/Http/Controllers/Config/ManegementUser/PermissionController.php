<?php

namespace App\Http\Controllers\Config\ManegementUser;

use App\Http\Controllers\Controller;
use App\Http\Resources\{PermissionResource};
use App\Models\{Permission};
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $permissions = Permission::where('company_id', '=',Auth::user()->company->id)->get();
            return response(['permissions'=>PermissionResource::collection($permissions)],200)->header('Content-Type', 'application/json');
        } catch (Exception $th) {
            return response([
            'message' => $th->getMessage()
            ], 500)->header('Content-Type', 'application/json');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $permission = new Permission();
            $permission->permission = $request->input('permission');
            $permission->company_id = Auth::user()->company->id;
            $permission->save();
            return response(['permissions'=>PermissionResource::collection(Permission::where('company_id', '=',Auth::user()->company->id)->get())],200)->header('Content-Type', 'application/json');
        } catch (Exception $th) {
            return response(['error'=>$th->getMessage()],400)->header('Content-Type', 'application/json');        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $permission = Permission::findOrFail($id);
            return response(['permission'=>PermissionResource::make($permission)])->header('Content-Type', 'application/json');
        } catch (Exception $th) {
            return response(['error'=>$th->getMessage()],400)->header('Content-Type', 'application/json');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $permission = Permission::findOrFail($id);
            $permission->permission = $request->input('permission');
            $permission->save();
            return response(['permissions'=>PermissionResource::collection(Permission::where('company_id', '=',Auth::user()->company->id)->get())],200)->header('Content-Type', 'application/json');
        } catch (Exception $th) {
            return response(['error'=>$th->getMessage()],400)->header('Content-Type', 'application/json');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $permission = Permission::findOrFail($id);
            $permission->delete();
            return response(['permissions'=>PermissionResource::collection(Permission::where('company_id', '=',Auth::user()->company->id)->get())],200)->header('Content-Type', 'application/json');
        } catch (Exception $th) {
            return response(['error'=>$th->getMessage()],400)->header('Content-Type', 'application/json');
        }


    }
}
