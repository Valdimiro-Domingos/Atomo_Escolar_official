<?php

namespace Database\Seeders;

use App\Models\Period;
use Exception;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
    }

    public static function initialize_period($company_id){
        try {
            DB::beginTransaction();
            Period::create([
                'time_start' => '08:30:00',
                'time_end' => '12:00:00',
                'designation' => 'Manhã',
                'description' => ' MANHÃ',
                'status' => '1',
                'company_id'=> $company_id,
            ]);
            Period::create([
                'time_start' => '12:45:00',
                'time_end' => '17:00:00',
                'designation' => 'Tarde',
                'description' => ' TARDE',
                'status' => '1',
                'company_id'=> $company_id,
            ]);
            Period::create([
                'time_start' => '18:45:00',
                'time_end' => '21:00:00',
                'designation' => 'Noite',
                'description' => ' NOITE',
                'status' => '1',
                'company_id'=> $company_id,
            ]);
            DB::commit();
        } catch (Exception $th) {
            DB::rollBack();
        }
    }
}
