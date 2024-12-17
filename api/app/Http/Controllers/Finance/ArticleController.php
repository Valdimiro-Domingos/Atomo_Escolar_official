<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\ArticleType;
use App\Models\Retention;
use App\Models\Tax;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\InvoiceReceiptItens;


class ArticleController extends Controller
{
    public function index()
    {
        try {
            $articles = Article::with('retention','article_category','article_type', 'tax')->where('company_id',  Auth::user()->company_id)->orderByDesc('id')->get();
        return response(
            ['articles'=>ArticleResource::collection($articles)],200
        )->header('Content-Type', 'application/json');
        } catch (Exception $th) {
            return response(['articles'=> $th->getMessage()],500)->header('Content-type', 'application/json');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $article_category = ArticleCategory::findOrFail(intval($request->input("article_category_id")));
            $article_type = ArticleType::findOrFail(intval($request->input("article_type_id")));
            $tax = Tax::findOrFail(intval($request->input("tax_id")));
            $retention = Retention::findOrFail(intval($request->input("retention_id")));

            $article = Article::create([
                'designation' => $request->input('designation'),
                'description' => $request->input('description') ?? null,
                'price' => $request->input('price'),
                'company_id' => Auth::user()->company_id,
                "article_category_id"=>$article_category->id,
                "article_type_id"=>$article_type->id,
                "retention_id"=>$retention->id,
                "tax_id"=>$tax->id,
            ]);
            return response(['articles'=>ArticleResource::collection(Article::with('retention','article_category','article_type', 'tax')
                ->where('company_id',  Auth::user()->company_id)->orderByDesc('id')->get())],200)->header('Content-Type', 'application/json');

          } catch (Exception $th) {
            return response(['articles'=> $th->getMessage()],500)->header('Content-type', 'application/json');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $article = Article::with('retention','article_category','article_type', 'tax')->findOrFail(intval($id));
        return response(
            ['article'=>ArticleResource::make($article)],200
        )->header('Content-Type', 'application/json');
        } catch (Exception $th) {
            return response(['article'=> $th->getMessage()],400)->header('Content-type', 'application/json');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {

            $article = Article::findOrFail(intval($id));

            $article->update($request->all());
            return response(['articles'=>ArticleResource::collection(Article::with('retention','article_category','article_type', 'tax')->
            where('company_id',Auth::user()->company->id)->get())],200)->header('Content-Type', 'application/json');

        } catch (Exception $th) {
            return response(['articles'=> $th->getMessage()],500)->header('Content-type', 'application/json');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $article = Article::findOrFail(intval($id));
            
          if($article && InvoiceReceiptItens::where('article_id', $id)->exists()){
            	  return response(['articles'=> "Não podes elimina esta artigo porque existe na fatura"],500)->header('Content-type', 'application/json');
            }
          
            $article->delete();
            return response(['articles'=>ArticleResource::collection(Article::where('company_id',  '=',Auth::user()->company->id)->get())],200)->header('Content-Type', 'application/json');

        } catch (Exception $th) {
            return response(['articles'=> $th->getMessage()],500)->header('Content-type', 'application/json');
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function changeStatus(Request $request, String $id){
        try {
            $article = Article::findOrFail(intval($id));
            $article->status = $request->input('status');
            $article->save();
            return response(['articles'=>ArticleResource::collection(Article::with('retention','article_category','article_type', 'tax')->where('company_id',  '=',Auth::user()->company->id)->get())],200)->header('Content-Type', 'application/json');

        } catch (Exception $th) {
            return response(['articles'=> $th->getMessage()],500)->header('Content-type', 'application/json');
         }
    }

}

