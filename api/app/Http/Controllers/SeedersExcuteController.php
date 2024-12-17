<?php

namespace App\Http\Controllers;

use Database\Seeders\{
    ArticleCategory,
    ArticleSeeder,
    ArticleType,
    ClassSeeder,
    DepartamentSeeder,
    FormOfPaymentSeeder,
    RoleSeeder,
    TrimestreSeeder,
    PeriodSeeder,
    RetentionsSeeder,
    TaxSeeder,
};

class SeedersExcuteController extends Controller
{
    public static function execute_seed($company_id){
        RoleSeeder::initial_roles($company_id);
        $departament = DepartamentSeeder::initial_departaments($company_id);
        ClassSeeder::initialize_classes($company_id);
        PeriodSeeder::initialize_period($company_id);
        TrimestreSeeder::initialize_trimestre($company_id);
        ArticleType::initial_article_types($company_id);
        ArticleCategory::initial_article_category($company_id);
        RetentionsSeeder::initial_retentions($company_id);
        TaxSeeder::initial_taxes($company_id);
        ArticleSeeder::initial_article($company_id);
        FormOfPaymentSeeder::initial_form_of_pays($company_id);
        $return = [
            "departament" => $departament,
        ];

        return response($return);
    }
}
