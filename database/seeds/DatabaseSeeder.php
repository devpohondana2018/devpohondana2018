<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Master
        $this->call(UsersTableSeeder::class);
        $this->call(StatusesTableSeeder::class);
        $this->call(AdminsTableSeeder::class);
        $this->call(RolesPermissionsTableSeeeder::class);
        $this->call(LoanTenorTableSeeder::class);
        $this->call(LoanGradesTableSeeder::class);
        $this->call(CompaniesTableSeeder::class);
        $this->call(BanksTableSeeder::class);
        $this->call(SuperAdminSeeder::class);
        $this->call(AdminAdditionalMenusTableSeeder::class);
        $this->call(ProvincesTableSeeder::class);
        $this->call(DistrictTableSeeder::class);

        // Transaction
        $this->call(LoansTableSeeder::class);
        $this->call(InvestmentsTableSeeder::class);
        $this->call(InstallmentsTableSeeder::class);
        $this->call(TransactionsTableSeeder::class);
        $this->call(ProvincesTableSeeder::class);
        $this->call(DistrictTableSeeder::class);
    }
}
