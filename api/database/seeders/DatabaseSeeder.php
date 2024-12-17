<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AuthSeeder::class,

            ArticleCategory::class,
            ArticleSeeder::class,
            ArticleType::class,
            ClassSeeder::class,
            CompanyAddressSeeder::class,
            CompanyBankSeeder::class,
            CompanyContactSeeder::class,
            CompanyRepresentativeSeeder::class,
            CompanySeeder::class,
            CompanySocialNetworkSeeder::class,
            CourseClassRoomSeeder::class,
            DepartamentSeeder::class,
            DepartamentUserSeeder::class,
            DisciplineCourseSeeder::class,
            ExpensesSeeder::class,
            FormOfPaymentSeeder::class,
            InvoiceReceiptItensSeeder::class,
            InvoiceReceiptSeeder::class,
            MenuComponentSeeder::class,
            PeriodSeeder::class,
            PermissionSeeder::class,
            RetentionsSeeder::class,
            RolePermissionSeeder::class,
            RoleSeeder::class,
            RoleUserSeeder::class,
            TaxSeeder::class,
            TrimestreSeeder::class,
        ]);
    }
}
