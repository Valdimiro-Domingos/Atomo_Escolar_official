<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\ArticleType;
use App\Models\Retention;
use App\Models\Tax;
use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companys = Company::pluck('id');
        foreach ($companys as $compan) {
            $this->initial_article($compan);
        }
    }
    
    public static function initial_article($company_id){
  
        $article_type = ArticleType::where('company_id', '=',$company_id)->where('designation', 'LIKE','%Serviços')->first();
        $article_category = ArticleCategory::where('company_id', '=',$company_id)->where('designation','Matricula')->first();
        $tax = Tax::where('company_id', '=',$company_id)->where('designation', 'M00 - Regime Simplificado')->first();
        $retention = Retention::where('company_id', '=',$company_id)->where('designation', 'Sem retenção na fonte')->first();
        Article::create([
            'designation' => 'Matricula',
            'price' => 15000,
            'article_type_id' => $article_type->id,
            'article_category_id' => $article_category->id,
            'retention_id' => $retention->id,
            'tax_id' => $tax->id,
            'company_id'=> $company_id,
        ]);

        $meses = [
            1 => 'Janeiro',2 => 'Fevereiro',3 => 'Março', 4 => 'Abril',5 => 'Maio',6 => 'Junho',7 => 'Julho',8 => 'Agosto',9 => 'Setembro',
            10 => 'Outubro',11 => 'Novembro',12 => 'Dezembro'
        ];
        $article_type = ArticleType::where('company_id', '=',$company_id)->where('designation', 'LIKE','%Serviços')->first();
        $article_category = ArticleCategory::where('company_id', '=',$company_id)->where('designation', 'LIKE','%Propinas')->first();
        $tax = Tax::where('company_id', '=',$company_id)->first();
        $retention = Retention::where('company_id', '=',$company_id)->first();
        foreach ($meses as $mes) {
            Article::create([
                'designation' => $mes,
                'price' => 15000,
                'article_type_id' => $article_type->id,
                'article_category_id' => $article_category->id,
                'retention_id' => $retention->id,
                'tax_id' => $tax->id,
                'company_id'=> $company_id,
            ]);
        }


        $article_type = ArticleType::where('company_id', '=',$company_id)->where('designation', 'LIKE','%Serviços')->first();
        $article_category = ArticleCategory::where('company_id', '=',$company_id)->where('designation', 'LIKE','%Transporte')->first();
        $tax = Tax::where('company_id', '=',$company_id)->first();
        $retention = Retention::where('company_id', '=',$company_id)->first();
        foreach ($meses as $mes) {
            Article::create([
                'designation' => $mes,
                'price' => 15000,
                'article_type_id' => $article_type->id,
                'article_category_id' => $article_category->id,
                'retention_id' => $retention->id,
                'tax_id' => $tax->id,
                'company_id'=> $company_id,
            ]);
        }
        $article_type = ArticleType::where('company_id', '=',$company_id)->where('designation', 'LIKE','%Produto')->first();
        $article_category = ArticleCategory::where('company_id', '=',$company_id)->where('designation', 'LIKE','%Vendas')->first();
        $tax = Tax::where('company_id', '=',$company_id)->first();
        $retention = Retention::where('company_id', '=',$company_id)->first();
        $meses = ['Folha de Prova','Bata','Uniforme de Educação Física','Uniforme' ];
        foreach ($meses as $mes) {
            Article::create([
                'designation' => $mes,
                'price' => 15000,
                'article_type_id' => $article_type->id,
                'article_category_id' => $article_category->id,
                'retention_id' => $retention->id,
                'tax_id' => $tax->id,
                'company_id'=> $company_id,
            ]);
        }
        $meses = ['Cartão de Propina','Passe de estudante','Taxa/Seguro','Actividade Extra Currilucar' ];
        $article_type = ArticleType::where('company_id', '=',$company_id)->where('designation', 'LIKE','%Produto')->first();
        $article_category = ArticleCategory::where('company_id', '=',$company_id)->where('designation', 'LIKE','%Diversos')->first();
        $tax = Tax::where('company_id', '=',$company_id)->first();
        $retention = Retention::where('company_id', '=',$company_id)->first();
        foreach ($meses as $mes) {
            Article::create([
                'designation' => $mes,
                'price' => 15000,
                'article_type_id' => $article_type->id,
                'article_category_id' => $article_category->id,
                'retention_id' => $retention->id,
                'tax_id' => $tax->id,
                'company_id'=> $company_id,
            ]);
        }
        $meses = ['Declaração S/Nota','Declaração C/Nota','Certificado','Diploma','Transferência','Boletim de Nota','Termo' ];
        $article_type = ArticleType::where('company_id', '=',$company_id)->where('designation', 'LIKE','%Serviços')->first();
        $article_category = ArticleCategory::where('company_id', '=',$company_id)->where('designation', 'LIKE','%Pagamento de Documentos%')->first();
        $tax = Tax::where('company_id', '=',$company_id)->first();
        $retention = Retention::where('company_id', '=',$company_id)->first();
        foreach ($meses as $mes) {
            Article::create([
                'designation' => $mes,
                'price' => 15000,
                'article_type_id' => $article_type->id,
                'article_category_id' => $article_category->id,
                'retention_id' => $retention->id,
                'tax_id' => $tax->id,
                'company_id'=> $company_id,
            ]);
        }
        $meses = ['Pagamento de matrícula'  ];
        $article_type = ArticleType::where('company_id', '=',$company_id)->where('designation', 'LIKE','%Serviços')->first();
        $article_category = ArticleCategory::where('company_id', '=',$company_id)->where('designation', 'LIKE','%Matrículas%')->first();
        $tax = Tax::where('company_id', '=',$company_id)->first();
        $retention = Retention::where('company_id', '=',$company_id)->first();
        foreach ($meses as $mes) {
            Article::create([
                'designation' => $mes,
                'price' => 15000,
                'article_type_id' => $article_type->id,
                'article_category_id' => $article_category->id,
                'retention_id' => $retention->id,
                'tax_id' => $tax->id,
                'company_id'=> $company_id,
            ]);
        }
        $meses = ['Pagamento de matrícula'  ];
        $article_type = ArticleType::where('company_id', '=',$company_id)->where('designation', 'LIKE','%Serviços')->first();
        $article_category = ArticleCategory::where('company_id', '=',$company_id)->where('designation', 'LIKE','%Confirmação%')->first();
        $tax = Tax::where('company_id', '=',$company_id)->first();
        $retention = Retention::where('company_id', '=',$company_id)->first();
        foreach ($meses as $mes) {
            Article::create([
                'designation' => $mes,
                'price' => 15000,
                'article_type_id' => $article_type->id,
                'article_category_id' => $article_category->id,
                'retention_id' => $retention->id,
                'tax_id' => $tax->id,
                'company_id'=> $company_id,
            ]);
        }
    }
}
