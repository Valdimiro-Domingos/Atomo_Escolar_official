<?php

namespace App\Http\Controllers\Config\Company;

use App\Http\Controllers\Controller;
use App\Http\Resources\CompanyBankResource;
use App\Models\Company;
use App\Models\CompanyBank;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyBankController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        try {
            $company_bank = CompanyBank::where('company_id', '=',Auth::user()->company->id)->get();
            return response(['company_bank'=>CompanyBankResource::collection($company_bank)],200)->header('Content-Type', 'application/json');
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
        $company_bank = new CompanyBank();
        $company_bank->company_id = Auth::user()->company->id;
        $company_bank->name = $request->input('name');
        $company_bank->account_number = $request->input('account_number');
        $company_bank->iban = $request->input('iban');
        $company_bank->swift = $request->input('swift');
        $company_bank->save();

        return response(
            ['company_bank'=>CompanyBankResource::collection(CompanyBank::where('company_id', '=',Auth::user()->company->id)->get())],200
        )->header('Content-Type', 'application/json');
       } catch (Exception $th) {
        return response(['error'=>$th->getMessage()],400)->header('Content-Type', 'application/json');
       }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $company_bank = CompanyBank::findOrFail($id);
            return response([ 'company_bank'=>CompanyBankResource::make($company_bank)],200)
            ->header('Content-Type', 'application/json');
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
            $company_bank = CompanyBank::findOrFail($id);
            $company_bank->update($request->only(
                'name',
                'account_number',
                'iban',
              'swift',
                'company_id',
            ));
            return response( ['company_bank'=>CompanyBankResource::collection(CompanyBank::where('company_id', '=',Auth::user()->company->id)->get())],200)->header('Content-Type', 'application/json');
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
            $company_bank = CompanyBank::findOrFail($id);
            $company_bank->delete();
            return response( ['company_bank'=>CompanyBankResource::collection(CompanyBank::where('company_id', '=',Auth::user()->company->id)->get())],200)->header('Content-Type', 'application/json');
        } catch (Exception $th) {
            return response(['error'=>$th->getMessage()],400)->header('Content-Type', 'application/json');
        }
    }
}
