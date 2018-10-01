<?php

use Faker\Generator as Faker;

$factory->define(App\Loan::class, function (Faker $faker) {

	$provision_rate = 1;
	$interest_rate = 2;
    $invest_rate = 1.5;
	$amount_requested = $faker->numberBetween(1000000,10000000);
	$tenor = $faker->randomElement(array(1,6,9,12,18)); // durasi dari tenor yang tersedia

	$provision_fee = $amount_requested * ($provision_rate/100);
    $interest_fee = ($amount_requested * ($interest_rate/100)) * $tenor;
    $invest_fee = ($amount_requested * ($invest_rate/100)) * $tenor;
    $amount_borrowed = $amount_requested - $provision_fee;
    $amount_total = $amount_requested + $interest_fee;
    $monthly_installments = $amount_total / $tenor;

    return [
        'amount_requested' => $amount_requested,
        'loan_tenor_id' => $faker->numberBetween(1,6), // id dari LoanTenor
        'description' => 'Deskripsi dari pinjaman',
        'date_expired' => \Carbon\Carbon::createFromFormat('Y-m-d', date('Y-m-d'))->addDays(30),
        'provision_rate' => $provision_rate,
        'provision_fee' => $provision_fee,
        'interest_rate' => $interest_rate,
        'interest_fee' => $interest_fee,
        'invest_rate' => $invest_rate,
        'invest_fee' => $invest_fee,
        'amount_borrowed' => $amount_borrowed,
        'amount_total' => $amount_total,
        'loan_grade_id' => NULL, // id dari LoanGrade
        'status_id' => $faker->randomElement(array(1,2)) // Pending, Approve
    ];
});
