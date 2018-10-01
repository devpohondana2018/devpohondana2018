<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class InstallmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $loans = App\Loan::where('status_id',2)->get(); // only create installments for approved loans
        foreach ($loans as $loan) {
            $amount = $loan->amount_total / $loan->tenor->month;
            for ($i=1; $i < ($loan->tenor->month)+1; $i++) {
                $installment = new \App\Installment;
                $installment->amount = $amount;
                $installment->tenor = $i;
                $installment->balance = $loan->amount_total - ($installment->amount * $i);
                $installment->due_date = $loan->created_at->addDays($i * 30);
                $installment->installmentable_id = $loan->id;
                $installment->installmentable_type = 'App\Loan';
                $installment->paid = $faker->randomElement(array(1,0));
                $installment->save();
            }
        }
        $investments = App\Investment::where('status_id',2)->get(); // only create installments for active investments
        foreach ($investments as $investment) {
            $amount = $investment->amount_total/$investment->loan->tenor->month;
            for ($i=1; $i < ($investment->loan->tenor->month)+1; $i++) {
                $installment = new \App\Installment;
                $installment->amount = $amount; 
                $installment->tenor = $i;
                $installment->balance = $investment->amount_invested - ($installment->amount * $i);
                $installment->due_date = $investment->created_at->addDays($i * 30);
                $installment->installmentable_id = $investment->id;
                $installment->installmentable_type = 'App\Investment';
                $installment->paid = $faker->randomElement(array(1,0));
                $installment->save();
            }
        }
    }
}
