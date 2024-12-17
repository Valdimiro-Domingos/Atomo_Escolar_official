<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Company;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $roles = ['pedagogical', 'secretary','finance','profeessor'];
        // foreach ($roles as $role) {
        //     Role::create([
        //         'role' => $role,
        //         'guard' => $role,
        //     ]);
        // }


        
    }
    public static function initial_roles($company_id){
        $company_id = Company::pluck('id');
        $roles = ['super admin','admin','pedagogica', 'secretaria','professor','finanças',];
        foreach ($roles as $role) {
            Role::create([
                'role' => $role,
                'guard' => $role,
                'company_id'=> $company_id,
            ]);
        }
    }
}
