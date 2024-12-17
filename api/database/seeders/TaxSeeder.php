<?php

namespace Database\Seeders;

use App\Models\Tax;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
    }
    public static function initial_taxes($company_id){
        Tax::create([
            'designation' => "M00",
            'description' => "M00 - Regime Simplificado",
            'status' => "1",
            'company_id'=> $company_id,
        ]);
        Tax::create([
            'designation' => "M02",
            'status' => "1",
            'description' => "M02 - Transmissão de bens e serviço não sujeita",
            'company_id'=> $company_id,
        ]);
    }
}
