<?php

use Illuminate\Database\Seeder;

class CompaniesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $companies = [
    		'PT Rekanan A' => true,
    		'PT Rekanan B' => true,
    		'PT Rekanan C' => true,
    		'PT Bukan Rekanan D' => false,
    		'PT Bukan Rekanan E' => false,
    	];
    	foreach ($companies as $key => $value) {
    		DB::table('companies')->insert([
	            'name' => $key,
                'affiliate' => $value,
	            'created_at' => date("Y-m-d H:i:s"),
	            'updated_at' => date("Y-m-d H:i:s")
	        ]);
    	}
    }
}
