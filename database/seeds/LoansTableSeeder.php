<?php

use Illuminate\Database\Seeder;

class LoansTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = App\User::role('borrower')->get();
        foreach ($users as $user) {
        	for($i=1;$i<5;$i++) {
        		$user->loans()->save(factory(App\Loan::class)->make());
        	}
        }
    }
}
