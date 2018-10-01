<?php

use Illuminate\Database\Seeder;

class LoanTenorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$tenors = [1,3,6,9,12,18];
    	foreach ($tenors as $key => $value) {
    		DB::table('loan_tenors')->insert([
	            'month' => $value,
	            'created_at' => date("Y-m-d H:i:s"),
            	'updated_at' => date("Y-m-d H:i:s"),
	        ]);
    	}
    }
}
