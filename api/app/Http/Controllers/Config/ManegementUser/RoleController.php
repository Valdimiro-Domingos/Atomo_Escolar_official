<?php

namespace App\Http\Controllers\Config\ManegementUser;

use App\Http\Controllers\Controller;
use App\Http\Resources\RoleResource;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response([
            'roles'=>RoleResource::collection(Role::where('company_id',Auth::user()->company_id)->get())
        ],200)
        ->header('Content-type', 'application/json');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $role = Role::create([
            'role' => $request->role,
            'guard' => $request->header('Accept'),
            'company_id' => Auth::user()->company_id,
        ]);

        return response(['roles'=>RoleResource::collection(Role::where('company_id',Auth::user()->company_id)->get())],200)->header('Content-type', 'application/json');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $role = Role::where('company_id',Auth::user()->company_id)->findOrFail($id);
        return response([
            'roles'=>RoleResource::make($role )
        ],200)
        ->header('Content-type', 'application/json');
    }
    public function show_with_designation()
    {
        $role = Role::where('company_id', Auth::user()->company_id)->where('role','professor')->first();;
        return response([
            'roles'=>RoleResource::make($role )
        ],200)
        ->header('Content-type', 'application/json');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $role->update([
            'role' => $request->role,
            'guard' => $request->header('Accept'),
        ]);
        return response([
            'roles'=>RoleResource::collection(Role::where('company_id', Auth::user()->company_id)->get())
        ],200)
        ->header('Content-type', 'application/json');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Role::findOrFail($id);
        
        if(User::where('role_id', $id)->count() == 0){
            $role->delete();
        }else{
            return response()->json(["message"=>"Existe usuario com este registro"], 500);
        }
        



        return response([
            'roles'=>RoleResource::collection(Role::where('company_id', Auth::user()->company_id)->get())
        ],200)
        ->header('Content-type', 'application/json');
    }
}
