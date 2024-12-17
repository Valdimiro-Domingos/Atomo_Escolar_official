<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleTypeResource;
use App\Models\ArticleType;
use App\Models\Company;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Article;



class ArticleTypeController extends Controller
{
    public function index()
    {
        try {
            $article_types = ArticleType::where('company_id',  Auth::user()->company_id)->orderByDesc('id')->get();
        return response(
            ['article_types'=>ArticleTypeResource::collection($article_types)],200
        )->header('Content-Type', 'application/json');
        } catch (Exception $th) {
            return response(['article_types'=> $th->getMessage()],500)->header('Content-type', 'application/json');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            
            $article_type = ArticleType::create([
                'designation' => $request->input('designation'),
                'description' => $request->input('description'),
                'company_id' => Auth::user()->company->id,
            ]);
            return response(['article_type'=>ArticleTypeResource::collection(ArticleType::where('company_id',  Auth::user()->company_id)->orderByDesc('id')->get())],200)->header('Content-Type', 'application/json');

        } catch (Exception $th) {
            return response(['article_type'=> $th->getMessage()],500)->header('Content-type', 'application/json');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        try {
            $article_type = ArticleType::findOrFail(intval($id));
        return response(
            ['article_type'=>ArticleTypeResource::make($article_type)],200
        )->header('Content-Type', 'application/json');
        } catch (Exception $th) {
            return response(['article_type'=> $th->getMessage()],400)->header('Content-type', 'application/json');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {

            $article_type = ArticleType::findOrFail(intval($id));
            $article_type->designation = $request->input('designation');
            $article_type->description = $request->input('description');
            $article_type->save();
            return response(['article_type'=>ArticleTypeResource::collection(ArticleType::where('company_id',  '=',Auth::user()->company->id)->get())],200)->header('Content-Type', 'application/json');

        } catch (Exception $th) {
            return response(['article_type'=> $th->getMessage()],500)->header('Content-type', 'application/json');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    {
        try {
            $article_type = ArticleType::findOrFail(intval($id));
            
            if($article_type && Article::where('article_type_id', $article_type->id)->exists()){
            	  return response(['article_type'=> "Não podes elimina esta categoria porque existe artigos"],500)->header('Content-type', 'application/json');
            }
            
            
            $article_type->delete();
            return response(['article_type'=>ArticleTypeResource::collection(ArticleType::where('company_id',  '=',Auth::user()->company->id)->get())],200)->header('Content-Type', 'application/json');

        } catch (Exception $th) {
            return response(['article_type'=> $th->getMessage()],500)->header('Content-type', 'application/json');
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function changeStatus(Request $request,  $id){
        try {
            $article_type = ArticleType::findOrFail(intval($id));
            $article_type->status = $request->input('status');
            $article_type->save();
            return response(['article_type'=>ArticleTypeResource::collection(ArticleType::where('company_id',  '=',Auth::user()->company->id)->get())],200)->header('Content-Type', 'application/json');

        } catch (Exception $th) {
            return response(['article_type'=> $th->getMessage()],500)->header('Content-type', 'application/json');
         }
    }

}
