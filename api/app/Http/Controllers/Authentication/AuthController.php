<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\Company;
use App\Models\Manager;
use App\Models\System;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        if (!Auth()->attempt($credentials)) {
            return response(['message' => 'Credências inválida', 'error' => "pass"], 400)->header('Content-Type', 'application/json');
        }

        $user = Auth()->user();

        $hoje = Carbon::now(); // Obter a data atual
        $company = Company::find($user->company_id);
        $prazoInicio = Carbon::parse($company->dateIssure);
        $prazoTermino = Carbon::parse($company->dateDue);

        // Verifique se a data atual está dentro do intervalo
        if ($hoje->greaterThanOrEqualTo($prazoInicio) && $hoje->lessThanOrEqualTo($prazoTermino)) {} else {
            if ($user->company_id != 1) {
                return $this->autentication($company->designation, $company->identity);
            }
        }

        if ($user->company->status == 1) {
            // $user->tokens()->delete();
            $token = $user->createToken("login_token")->plainTextToken;
            $retorn = ['user' => UserResource::make($user), 'token' => $token];
            return response($retorn)->header('Content-Type', 'application/json');
        } else {
            if ($user->company->status == '3') {
                return response()->json(['message' => 'Escola desvinculado!', "error" => "company"], 400);
            }if ($user->company->status == '0') {

                return response()->json(['message' => 'Escola em processo!', "error" => "company"], 400);
            } else {
                return response()->json(['message' => 'Escola em processo de validação', "error" => "company"], 400);
            }
        }
    }

    private function autentication($designation, $identity, $system = "school")
    {
        $url = Manager::first()->link;
        $response = Http::get($url);

        if ($response->successful()) {
            $responseData = $response->json();
            $companyRecords = $responseData['company'] ?? [];

            $validCompany = false;
            for ($i = 0; $i < count($companyRecords); $i++) {
                $company = $companyRecords[$i];

                // Verifica se o sistema, a designação e o NIF (identity) são correspondentes
                if (($company['system'] == $system && $company['designation'] == $designation && $company['identity'] == $identity) ||
                    ($company['system'] == $system && $company['designation'] == $designation) ||
                    ($company['system'] == $system && $company['identity'] == $identity)) {

                    $hoje = Carbon::now();
                    $prazoInicio = Carbon::parse($company['dateIssure']);
                    $prazoTermino = Carbon::parse($company['dateDue'])->addDays(1);
                    $diferencaDias = $prazoInicio->diffInDays($prazoTermino);

                    if ($hoje->greaterThanOrEqualTo($prazoInicio) && $hoje->lessThanOrEqualTo($prazoTermino)) {
                        if ($company['status'] == 0) {
                            Auth::logout();
                            return response()->json(['error' => 'company', 'message' => 'A sua lincença expirou por favor, entre em contato com a nossa equipe para renovar a licença!'], 400);
                        }
                        if ($company['status'] == 1) {
                            $validCompany = true;

                            return true;
                            //return response()->json(['message' => 'Empresa válida', 'dias_restantes' => $diferencaDias]);
                        }
                    } else {
                        // Se o prazo expirou, faz o logout e redireciona com mensagem
                        Auth::logout();
                        return response()->json(['error' => 'company', 'message' => 'O prazo de subscrição da empresa expirou!']);
                    }
                }
            }

            // Se nenhum registro válido foi encontrado
            if (!$validCompany) {
                Auth::logout();
                return response()->json(['error' => 'company', 'message' => 'A sua lincença expirou por favor, entre em contato com a nossa equipe para renovar a licença!'], 400);
            }
        } else {
            Auth::logout();
            return response()->json(['error' => 'company', 'message' => 'Pedimos que atualize sua pagina e tente novamente'], 400);
        }
    }

    public function update_system(Request $request)
    {
        return response()->json(System::first());
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logouted user'], 200);
    }
}
