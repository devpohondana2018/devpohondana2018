<?php

namespace App\Listeners;

use Mail;
use App\Events\CashInInstallment;
use App\Mail\CashInInstallmentMail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Encore\Admin\Config\Config;

class SendCashInInstallmentMail
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
    public function handle(CashInInstallment $event)
    {
        $configEmail = explode(";", config('cash_in_notification'));
        
        Mail::to($configEmail)
            ->queue(new CashInInstallmentMail($event->transaction));
    }
}
