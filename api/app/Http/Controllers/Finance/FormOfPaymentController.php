<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Http\Resources\FormOfPaymentResource;
use App\Models\FormOfPayment;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FormOfPaymentController extends Controller
{
    public function index(){
        try {
            $form_of_payment = FormOfPayment::where('company_id', Auth::user()->company->id)->get();
            return response(
                ['form_of_payment'=>FormOfPaymentResource::collection($form_of_payment)],200
            )->header('Content-Type', 'application/json');
            } catch (Exception $th) {
                return response(["message"=>"Desculpe pelo transtorno. Houve um erro. Estamos trabalhando nisso. Por favor, tente novamente mais tarde.",
                'form_of_payment'=> $th->getMessage()],500)->header('Content-type', 'application/json');
            }
    }
}
