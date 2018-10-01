<?php

use Illuminate\Database\Seeder;

class LoanGradesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // tenor ID 1 = 1 bulan
        DB::table('loan_grades')->insert([
		    'rank' => 'A',
		    'loan_tenor_id' => 1,
		    'platform_rate' => 1,
		    'borrower_rate' => 2,
		    'lender_rate' => 1.25,
		    'active' => true,
		    'created_at' => date("Y-m-d H:i:s"),
			'updated_at' => date("Y-m-d H:i:s"),
		]);
		DB::table('loan_grades')->insert([
		    'rank' => 'B',
		    'loan_tenor_id' => 1,
		    'platform_rate' => 1,
		    'borrower_rate' => 2.5,
		    'lender_rate' => 1.3,
		    'active' => true,
		    'created_at' => date("Y-m-d H:i:s"),
			'updated_at' => date("Y-m-d H:i:s"),
		]);
		DB::table('loan_grades')->insert([
		    'rank' => 'C',
		    'loan_tenor_id' => 1,
		    'platform_rate' => 1,
		    'borrower_rate' => 3,
		    'lender_rate' => 1.5,
		    'active' => true,
		    'created_at' => date("Y-m-d H:i:s"),
			'updated_at' => date("Y-m-d H:i:s"),
		]);
		DB::table('loan_grades')->insert([
		    'rank' => 'D',
		    'loan_tenor_id' => 1,
		    'platform_rate' => 0,
		    'borrower_rate' => 0,
		    'lender_rate' => 0,
		    'active' => true,
		    'created_at' => date("Y-m-d H:i:s"),
			'updated_at' => date("Y-m-d H:i:s"),
		]);

		// tenor ID 2 = 3 bulan
		DB::table('loan_grades')->insert([
		    'rank' => 'A',
		    'loan_tenor_id' => 2,
		    'platform_rate' => 1,
		    'borrower_rate' => 2,
		    'lender_rate' => 1.25,
		    'active' => true,
		    'created_at' => date("Y-m-d H:i:s"),
			'updated_at' => date("Y-m-d H:i:s"),
		]);
		DB::table('loan_grades')->insert([
		    'rank' => 'B',
		    'loan_tenor_id' => 2,
		    'platform_rate' => 1,
		    'borrower_rate' => 2.5,
		    'lender_rate' => 1.3,
		    'active' => true,
		    'created_at' => date("Y-m-d H:i:s"),
			'updated_at' => date("Y-m-d H:i:s"),
		]);
		DB::table('loan_grades')->insert([
		    'rank' => 'C',
		    'loan_tenor_id' => 2,
		    'platform_rate' => 1,
		    'borrower_rate' => 3,
		    'lender_rate' => 1.5,
		    'active' => true,
		    'created_at' => date("Y-m-d H:i:s"),
			'updated_at' => date("Y-m-d H:i:s"),
		]);
		DB::table('loan_grades')->insert([
		    'rank' => 'D',
		    'loan_tenor_id' => 2,
		    'platform_rate' => 1,
		    'borrower_rate' => 0,
		    'lender_rate' => 0,
		    'active' => true,
		    'created_at' => date("Y-m-d H:i:s"),
			'updated_at' => date("Y-m-d H:i:s"),
		]);

		// tenor ID 3 = 6 bulan
		DB::table('loan_grades')->insert([
		    'rank' => 'A',
		    'loan_tenor_id' => 3,
		    'platform_rate' => 1,
		    'borrower_rate' => 1.8,
		    'lender_rate' => 1.25,
		    'active' => true,
		    'created_at' => date("Y-m-d H:i:s"),
			'updated_at' => date("Y-m-d H:i:s"),
		]);
		DB::table('loan_grades')->insert([
		    'rank' => 'B',
		    'loan_tenor_id' => 3,
		    'platform_rate' => 1,
		    'borrower_rate' => 2.2,
		    'lender_rate' => 1.3,
		    'active' => true,
		    'created_at' => date("Y-m-d H:i:s"),
			'updated_at' => date("Y-m-d H:i:s"),
		]);
		DB::table('loan_grades')->insert([
		    'rank' => 'C',
		    'loan_tenor_id' => 3,
		    'platform_rate' => 1,
		    'borrower_rate' => 3,
		    'lender_rate' => 1.5,
		    'active' => true,
		    'created_at' => date("Y-m-d H:i:s"),
			'updated_at' => date("Y-m-d H:i:s"),
		]);
		DB::table('loan_grades')->insert([
		    'rank' => 'D',
		    'loan_tenor_id' => 3,
		    'platform_rate' => 1,
		    'borrower_rate' => 0,
		    'lender_rate' => 0,
		    'active' => true,
		    'created_at' => date("Y-m-d H:i:s"),
			'updated_at' => date("Y-m-d H:i:s"),
		]);

		// tenor ID 4 = 9 bulan
		DB::table('loan_grades')->insert([
		    'rank' => 'A',
		    'loan_tenor_id' => 4,
		    'platform_rate' => 1,
		    'borrower_rate' => 1.8,
		    'lender_rate' => 1.25,
		    'active' => true,
		    'created_at' => date("Y-m-d H:i:s"),
			'updated_at' => date("Y-m-d H:i:s"),
		]);
		DB::table('loan_grades')->insert([
		    'rank' => 'B',
		    'loan_tenor_id' => 4,
		    'platform_rate' => 1,
		    'borrower_rate' => 2.2,
		    'lender_rate' => 1.3,
		    'active' => true,
		    'created_at' => date("Y-m-d H:i:s"),
			'updated_at' => date("Y-m-d H:i:s"),
		]);
		DB::table('loan_grades')->insert([
		    'rank' => 'C',
		    'loan_tenor_id' => 4,
		    'platform_rate' => 1,
		    'borrower_rate' => 3,
		    'lender_rate' => 1.5,
		    'active' => true,
		    'created_at' => date("Y-m-d H:i:s"),
			'updated_at' => date("Y-m-d H:i:s"),
		]);
		DB::table('loan_grades')->insert([
		    'rank' => 'D',
		    'loan_tenor_id' => 4,
		    'platform_rate' => 1,
		    'borrower_rate' => 0,
		    'lender_rate' => 0,
		    'active' => true,
		    'created_at' => date("Y-m-d H:i:s"),
			'updated_at' => date("Y-m-d H:i:s"),
		]);

		// tenor ID 5 = 12 bulan
		DB::table('loan_grades')->insert([
		    'rank' => 'A',
		    'loan_tenor_id' => 5,
		    'platform_rate' => 1,
		    'borrower_rate' => 1.6,
		    'lender_rate' => 1.25,
		    'active' => true,
		    'created_at' => date("Y-m-d H:i:s"),
			'updated_at' => date("Y-m-d H:i:s"),
		]);
		DB::table('loan_grades')->insert([
		    'rank' => 'B',
		    'loan_tenor_id' => 5,
		    'platform_rate' => 1,
		    'borrower_rate' => 2,
		    'lender_rate' => 1.3,
		    'active' => true,
		    'created_at' => date("Y-m-d H:i:s"),
			'updated_at' => date("Y-m-d H:i:s"),
		]);
		DB::table('loan_grades')->insert([
		    'rank' => 'C',
		    'loan_tenor_id' => 5,
		    'platform_rate' => 1,
		    'borrower_rate' => 3,
		    'lender_rate' => 1.5,
		    'active' => true,
		    'created_at' => date("Y-m-d H:i:s"),
			'updated_at' => date("Y-m-d H:i:s"),
		]);
		DB::table('loan_grades')->insert([
		    'rank' => 'D',
		    'loan_tenor_id' => 5,
		    'platform_rate' => 1,
		    'borrower_rate' => 0,
		    'lender_rate' => 0,
		    'active' => true,
		    'created_at' => date("Y-m-d H:i:s"),
			'updated_at' => date("Y-m-d H:i:s"),
		]);

		// tenor ID 6 = 18 bulan
		DB::table('loan_grades')->insert([
		    'rank' => 'A',
		    'loan_tenor_id' => 6,
		    'platform_rate' => 1,
		    'borrower_rate' => 1.6,
		    'lender_rate' => 1.25,
		    'active' => true,
		    'created_at' => date("Y-m-d H:i:s"),
			'updated_at' => date("Y-m-d H:i:s"),
		]);

    }
}
