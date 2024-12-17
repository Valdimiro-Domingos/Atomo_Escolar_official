<?php

namespace Database\Seeders;

use App\Models\ArticleCategory as ModelsArticleCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Company;

class ArticleCategory extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companys = Company::pluck('id');
        foreach ($companys as $compan) {
            $this->initial_article_category($compan);
        }
    }

    public static function initial_article_category($company_id){
        $article_categorys = ['Matrículas','Confirmação','Propinas', 'Transporte','Vendas','Pagamento de Documentos', 'Diversos', ];
        foreach ($article_categorys as $article_category) {
            ModelsArticleCategory::create([
                'designation' => $article_category,
                'description' => $article_category,
                'company_id'=> $company_id,
            ]);
        }
    }
}
