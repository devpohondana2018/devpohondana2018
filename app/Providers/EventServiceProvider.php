<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [

        'App\Events\UserRegistered' => [
            'App\Listeners\SendVerificationEmail',
        ],
        'App\Events\UserRequestedVerificationEmail' => [
            'App\Listeners\SendVerificationEmail',
        ],
        'App\Events\LoanApproved' => [
            'App\Listeners\SendApprovedLoanMail',
        ],
        'App\Events\LoanAccepted' => [
            'App\Listeners\SendAcceptedLoanMail',
        ],
        'App\Events\LoanDeclined' => [
            'App\Listeners\SendLoanDeclinedMail',
        ],
        'App\Events\LoanRejected' => [
            'App\Listeners\SendLoanRejectedMail',
        ],
        'App\Events\InvestmentApproved' => [
            'App\Listeners\SendApprovedInvestmentMail',
        ],
        'App\Events\InvestmentAccepted' => [
            'App\Listeners\SendAcceptedInvestmentMail',
        ],
        'App\Events\InvestmentDeclined' => [
            'App\Listeners\SendInvestmentDeclinedMail',
        ],
        'App\Events\UserVerified' => [
            'App\Listeners\SendUserVerifiedEmail',
        ],
        'App\Events\CashInInstallment' => [
            'App\Listeners\SendCashInInstallmentMail',
        ],
        'App\Events\CashOutInstallment' => [
            'App\Listeners\SendCashOutInstallmentMail',
        ],
        'App\Events\CashOutLoan' => [
            'App\Listeners\SendCashOutLoanMail',
        ],
        'App\Events\CashInInvestment' => [
            'App\Listeners\SendCashInInvestmentMail',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
