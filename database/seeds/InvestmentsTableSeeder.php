<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class InvestmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = App\User::role('lender')->get();
        $invest_rate = 1;
        $faker = Faker::create();
        foreach ($users as $user) {
    		$loans = App\Loan::get(); // get approved loans
    		foreach ($loans as $loan) {
    			$max_investment = $loan->amount_requested / 10; // set maximum amount of investment
	            $investment = new \App\Investment;
	            $investment->amount_invested = rand(0,$max_investment);
	            $investment->invest_rate = $invest_rate;
	            $investment->invest_fee = $investment->amount_invested * ($invest_rate/100);
       			$investment->amount_total = $investment->amount_invested + $investment->invest_fee;
	            $investment->loan_id = $loan->id;
	            $investment->user_id = $user->id;
	            $investment->status_id = $faker->randomElement(array(1,2)); // Pending, Approve
	            $investment->save();
    		}
        }
    }
}
