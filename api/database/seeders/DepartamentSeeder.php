<?php

namespace Database\Seeders;

use App\Models\Departament;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartamentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
    }

    public static function initial_departaments($company_id){
        $departament =  Departament::create([
            'designation' => "Administração",
            'description' => 'Administração geral da Escola',
            'status'=> '1',
            'company_id'=> $company_id,
        ]); 

        return $departament;

    }
}
