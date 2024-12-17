<?php

namespace Database\Seeders;

use App\Models\FormOfPayment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FormOfPaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // FormOfPayment::create(['name' => 'Flight 10']);
    }

    public static function initial_form_of_pays($company_id){
        $form_of_pays = ['Deposito','TPA','Transferência', 'Dinheiro'];
        foreach ($form_of_pays as $form_of_pay) {
            FormOfPayment::create([
                'designation' => $form_of_pay,
                'description' => $form_of_pay,
                'company_id'=> $company_id,
            ]);
        }
    }
}
