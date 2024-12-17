<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Http\Resources\ExpenseResource;
use App\Models\Expenses;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function index()
    {
        try {
            $expenses = Expenses::where('company_id',  '=',Auth::user()->company->id)->get();
        return response(
            ['expenses'=>ExpenseResource::collection($expenses)],200
        )->header('Content-Type', 'application/json');
        } catch (Exception $th) {
            return response(["message"=>"Desculpe pelo transtorno. Houve um erro. Estamos trabalhando nisso. Por favor, tente novamente mais tarde.",'expenses'=> $th->getMessage()],500)->header('Content-type', 'application/json');
        }
    }

    public function store(Request $request)
    {
        try {

            $expense = Expenses::create([
                'designation' => $request->input('designation'),
                'description' => $request->input('description'),
                'value' => $request->input('value'),
                'company_id' => Auth::user()->company->id,
            ]);
            return response(['expenses'=>ExpenseResource::collection(Expenses::where('company_id',Auth::user()->company->id)->get())],200)->header('Content-Type', 'application/json');

        } catch (Exception $th) {
            return response(['expenses'=> $th->getMessage()],500)->header('Content-type', 'application/json');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $expense = Expenses::findOrFail(intval($id));
        return response(
            ['expense'=>ExpenseResource::make($expense)],200
        )->header('Content-Type', 'application/json');
        } catch (Exception $th) {
            return response(['expense'=> $th->getMessage()],400)->header('Content-type', 'application/json');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {

            $expense = Expenses::findOrFail(intval($id));
            $expense->designation = $request->input('designation');
            $expense->description = $request->input('description');
            $expense->value = $request->input('value');
            $expense->save();
            return response(['expenses'=>ExpenseResource::collection(Expenses::where('company_id',  '=',Auth::user()->company->id)->get())],200)->header('Content-Type', 'application/json');

        } catch (Exception $th) {
            return response(['expenses'=> $th->getMessage()],500)->header('Content-type', 'application/json');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $expense = Expenses::findOrFail(intval($id));
            $expense->delete();
            return response(['expenses'=>ExpenseResource::collection(Expenses::where('company_id',  '=',Auth::user()->company->id)->get())],200)->header('Content-Type', 'application/json');

        } catch (Exception $th) {
            return response(['expenses'=> $th->getMessage()],500)->header('Content-type', 'application/json');
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function changeStatus(Request $request, String $id){
        try {
            $expense = Expenses::findOrFail(intval($id));
            $expense->status = $request->input('status');
            $expense->save();
            return response(['expenses'=>ExpenseResource::collection(Expenses::where('company_id',  '=',Auth::user()->company->id)->get())],200)->header('Content-Type', 'application/json');

        } catch (Exception $th) {
            return response(['expenses'=> $th->getMessage()],500)->header('Content-type', 'application/json');
         }
    }

}
