<?php

namespace App\Listeners;

use Mail;
use App\Events\CashInInvestment;
use App\Mail\CashInInvestmentMail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Encore\Admin\Config\Config;

class SendCashInInvestmentMail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  LoanApproved  $event
     * @return void
     */
    public function handle(CashInInvestment $event)
    {
        $configEmail = explode(";", config('cash_in_notification'));
        
        Mail::to($configEmail)
            ->queue(new CashInInvestmentMail($event->transaction));
    }
}
