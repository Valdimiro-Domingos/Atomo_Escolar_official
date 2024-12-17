<?php

namespace Database\Seeders;

use App\Models\Classes;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
    }

    public static function initialize_classes($company_id){

        for ($i=1; $i <14 ; $i++) {
            Classes::create([
                'designation' => ''.$i,
                'description' => $i.' ª Classe',
                'status' => '1',
                'company_id'=> $company_id,
            ]);
        }

    }


}
