<?php

namespace Database\Seeders;

use App\Models\Trimestre;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TrimestreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
    }
    public static function initialize_trimestre($company_id){

        for ($i=1; $i <4 ; $i++) {
            Trimestre::create([
                'designation' => ''.$i,
                'description' => $i.' ª trimestre',
                'status' => '1',
                'company_id'=> $company_id,
            ]);
        }

    }
}
