<?php

use Illuminate\Database\Seeder;

class TransactionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$installments = App\Installment::where('paid',TRUE)->get();
        foreach ($installments as $installment) {
            $transaction = new \App\Transaction;
            $transaction->amount = $installment->amount; 
            $transaction->user_id = rand(1,10);
            $transaction->transactionable_id = $installment->id;
            $transaction->transactionable_type = 'App\Installment';
            $transaction->save();
            // Update installment
            $installment->paid = TRUE;
            $installment->save();
        }
    }
}
