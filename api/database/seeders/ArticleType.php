<?php

namespace Database\Seeders;

use App\Models\ArticleType as ModelsArticleType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ArticleType extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

    }

    public static function initial_article_types($company_id){
        $article_types = ['Produto','Serviços'];
        foreach ($article_types as $article_type) {
            ModelsArticleType::create([
                'designation' => $article_type,
                'description' => $article_type,
                'company_id'=> $company_id,
            ]);
        }
    }
}
