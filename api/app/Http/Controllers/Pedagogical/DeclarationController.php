<?php

namespace App\Http\Controllers\Pedagogical;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DeclarationController extends Controller
{
    // public function index()
    // {
    //     try {
    //         $enrollment =  Enrollment::with('class_room','course','period','classe','turma','student','school_year')
    //             ->where('company_id', '=',Auth::user()->company->id)
    //             ->get();
    //         return response(
    //             ['enrollment'=> EnrollmentResource::collection($enrollment)],200
    //         )->header('Content-Type', 'application/json');
    //     } catch (Exception $th) {
    //         return response(['enrollment'=> $th->getMessage(),"message"=>"Desculpe pelo transtorno. Houve um erro. Estamos trabalhando nisso. Por favor, tente novamente mais tarde."],404)->header('Content-type', 'application/json');
    //     }
    // }
}
