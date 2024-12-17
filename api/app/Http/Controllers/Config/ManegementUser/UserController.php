<?php

namespace App\Http\Controllers\Config\ManegementUser;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Company;
use App\Models\Departament;
use App\Models\DepartamentUser;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $users = User::with('departament','role')->where('company_id', Auth::user()->company->id)->get();
            return response(['users'=>UserResource::collection($users)],200)->header('Content-Type', 'application/json');
        } catch (Exception $th) {
            return response([
             'message' => $th->getMessage()
            ], 500);
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function professor()
    {
        try {
            $users = User::with('departament')->with('role')->whereHas('role', function ($query) {
                $query->where('guard', 'professor');
            })->where('company_id', Auth::user()->company->id)->get();
            
            return response(['users' => UserResource::collection($users)], 200)->header('Content-Type', 'application/json');
        } catch (Exception $th) {
            return response([
                'message' => $th->getMessage()
            ], 500);
        }
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $role = Role::findOrFail(intval($request->input('role_id')));
            if (!$role) {
                return response(['error'=>'Dosen\'t exist this role'],404)->header('Content-Type', 'application/json');
            }
            $departament = Departament::findOrFail(intval($request->input('departament_id')));
            if (!$departament) {
                return response(['error'=>'Dosen\'t exist this departament'],404)->header('Content-Type', 'application/json');
            }
            DB::beginTransaction();
            $user = new User();
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->password = Hash::make('12345678');
            $user->company_id = Auth::user()->company->id;
            $user->role_id = $request->input('role_id');
            $user->departament_id = $request->input('departament_id');
            $user_save = $user->save();
            if (  $user_save) {
                DB::commit();
                return response(['users'=>UserResource::collection(User::where('company_id',  '=',Auth::user()->company->id)->get())],200)->header('Content-Type', 'application/json');
            }else {
                DB::rollBack();
                return response(['error'=>'Erro ao cadastrar usuário'],400)->header('Content-Type', 'application/json');
            }

        } catch (Exception $th) {
            DB::rollBack();
            return response(['error'=>$th->getMessage()],400)->header('Content-Type', 'application/json');        }
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        try {
            $user = User::findOrFail($id);
            return response(['user'=>UserResource::make($user)])->header('Content-Type', 'application/json');
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
        //return $request->input('role_id');

        $user = User::findOrFail($id);
        $role = Role::findOrFail(intval($request->input('role_id')));

        DB::beginTransaction();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->role_id = $request->input('role_id');
        $user->departament_id = $request->input('departament_id');
        $user_save = $user->save();
        if (  $user_save) {
            DB::commit();
            return response(['users'=>$user],200)->header('Content-Type', 'application/json');
        }else {
            DB::rollBack();
            return response(['error'=>'Erro ao atualizar usuário'],400)->header('Content-Type', 'application/json');
        }
       } catch (Exception $th) {

        DB::rollBack();
        return response(['error'=>$th->getMessage()],400)->header('Content-Type', 'application/json');       }


    }

    public function password_default( $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->password = Hash::make("12345678");
            $user->save();
            return response()->json(['message' => 'Senha alterada para o padrao'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao alterar a senha', 'message' => $e->getMessage()], 500);
        }
    }

    public function password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pass' => 'required|min:6',
            'pass_new_confirmation' => 'required|min:6',
        ], [
            'pass.min' => 'A senha atual deve ter no mínimo 6 caracteres',
            'pass.required' => 'A nova senha é obrigatória',
            'pass_new_confirmation.confirmed' => 'A confirmação da nova senha não corresponde',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['message' => 'A confirmação da nova senha não corresponde'], 500);
        }
    
        try {
            $user = User::findOrFail($request->id);
    
            if (Hash::check($request->pass, $user->password)) {
                $user->password = Hash::make($request->pass_new_confirmation);
                $user->save();
                return response()->json(['message' => 'Sucesso'], 200);
            } else {
                return response()->json(['message' => 'Senha incorreta'], 500);
            }
    
            return response()->json(['message' => 'Senha alterada com sucesso', 'user' => $user], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao alterar a senha', 'message' => $e->getMessage()], 500);
        }
    }
    


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
       try {
        if (Auth::user()->id == $id) {
            return response(['message'=>"O usuario não pode ser eliminado!"],403)->header('Content-Type', 'application/json');
        }
        $user = User::findOrFail($id);
        $user->delete();
        return response(['users'=>UserResource::collection(User::with('departament','role')->where('company_id',  '=',Auth::user()->company->id)->get()),
        'message'=>"Usuario eliminado com sucesso!"],200)->header('Content-Type', 'application/json');
       } catch (Exception $th) {
        return response(['error'=>$th->getMessage(), "message"=>"Desculpe pelo transtorno. Houve um erro. Estamos trabalhando nisso. Por favor, tente novamente mais tarde."],400)->header('Content-Type', 'application/json');
       }
    }
}
