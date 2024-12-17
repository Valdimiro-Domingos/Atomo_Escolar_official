<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Expenses;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\InvoiceReceipt;

class DashBoardController extends Controller
{
   /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
           $users = User::where('company_id', Auth::user()->company_id)->count();
           $invoice_number = InvoiceReceipt::where('company_id', Auth::user()->company_id)->sum('total');
           $enrollments = Enrollment::where('company_id',  Auth::user()->company_id)->where('status', '1')->count();
           $expenses = Expenses::where('company_id', Auth::user()->company_id)->where('status', '1')->sum('value');
           $enrollments = [
            'users' =>$users,
             'revenue' => $invoice_number,
            'enrollments' =>$enrollments,
            'expenses' =>$expenses,
             
             'databases' => [
              'revenue' => InvoiceReceipt::where('company_id', Auth::user()->company_id)->pluck('total'),
              'expenses' => Expenses::where('company_id', Auth::user()->company_id)->where('status', '1')->pluck('value'),
              'enrollments' => Enrollment::where('company_id', Auth::user()->company_id)->where('status', '1')->pluck('id')
          ]


           ];
            return response(
                ['dashboard'=> $enrollments],200
            )->header('Content-Type', 'application/json');
        } catch (Exception $th) {
            return response(['trimestres'=> $th->getMessage()],400)->header('Content-type', 'application/json');
        }
    }

}
