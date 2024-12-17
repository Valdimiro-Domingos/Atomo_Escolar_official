<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleCategoryResource;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\Company;
use App\Models\InvoiceReceiptItens;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ArticleCategoryController extends Controller
{
    public function index()
    {
        try {

            $article_categories = ArticleCategory::with('article')->where('company_id',  '=',Auth::user()->company->id)->get();
        return response(
            ['article_categories'=>ArticleCategoryResource::collection($article_categories)],200
        )->header('Content-Type', 'application/json');
        } catch (Exception $th) {
            return response(['article_categories'=> $th->getMessage()],500)->header('Content-type', 'application/json');
        }
    }
    
    public function get_category_in_invoice_receipts()
    {
        try {

            $subselect = InvoiceReceiptItens::select('article_id as id')
            ->join('invoice_receipts','invoice_receipts.id','=','invoice_receipt_itens.invoice_receipt_id')
                ->get();
                $article_categories = ArticleCategory::where('company_id',  '=',Auth::user()->company->id)->
                get();
                for ($i=0; $i < count($article_categories) ; $i++) {
                    $article_categories[$i]['article'] = Article::where('article_category_id', $article_categories[$i]['id'])
                    ->whereNotIn('id', $subselect->pluck('id'))
                    ->get();
                }
        return response(
            ['article_categories'=>ArticleCategoryResource::collection($article_categories)],200
        )->header('Content-Type', 'application/json');
        } catch (Exception $th) {
            return response(['article_categories'=> $th->getMessage()],500)->header('Content-type', 'application/json');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {

            $article_category = ArticleCategory::create([
                'designation' => $request->input('designation'),
                'unique' => $request->input('unique'),
                'description' => $request->input('description'),
                'company_id' => Auth::user()->company->id,
            ]);
            return response(['article_categories'=>ArticleCategoryResource::collection(ArticleCategory::where('company_id',  '=',Auth::user()->company->id)->get())],200)->header('Content-Type', 'application/json');

        } catch (Exception $th) {
            return response(['article_categories'=> $th->getMessage()],500)->header('Content-type', 'application/json');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $article_category = ArticleCategory::findOrFail(intval($id));
        return response(
            ['article_category'=>ArticleCategoryResource::make($article_category)],200
        )->header('Content-Type', 'application/json');
        } catch (Exception $th) {
            return response(['article_category'=> $th->getMessage()],400)->header('Content-type', 'application/json');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {

            $article_category = ArticleCategory::findOrFail(intval($id));
            $article_category->designation = $request->input('designation');
            $article_category->description = $request->input('description');
            $article_category->save();
            return response(['article_categorys'=>ArticleCategoryResource::collection(ArticleCategory::where('company_id',  '=',Auth::user()->company->id)->get())],200)->header('Content-Type', 'application/json');

        } catch (Exception $th) {
            return response(['article_categorys'=> $th->getMessage()],500)->header('Content-type', 'application/json');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $article_category = ArticleCategory::findOrFail(intval($id));
          	if($article_category && Article::where('article_category_id', $article_category->id)->exists()){
            	  return response(['article_categorys'=> "Não podes elimina esta categoria porque existe artigos"],500)->header('Content-type', 'application/json');
            }
              
            $article_category->delete();
            return response(['article_categorys'=>ArticleCategoryResource::collection(ArticleCategory::where('company_id',  '=',Auth::user()->company->id)->get())],200)->header('Content-Type', 'application/json');

        } catch (Exception $th) {
            return response(['article_categorys'=> $th->getMessage()],500)->header('Content-type', 'application/json');
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function changeStatus(Request $request, String $id){
        try {
            $article_category = ArticleCategory::findOrFail(intval($id));
            $article_category->status = $request->input('status');
            $article_category->save();
            return response(['article_categorys'=>ArticleCategoryResource::collection(ArticleCategory::where('company_id',  '=',Auth::user()->company->id)->get())],200)->header('Content-Type', 'application/json');

        } catch (Exception $th) {
            return response(['article_categorys'=> $th->getMessage()],500)->header('Content-type', 'application/json');
         }
    }

}
