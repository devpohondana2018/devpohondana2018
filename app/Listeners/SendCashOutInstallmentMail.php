<?php

namespace App\Listeners;

use Mail;
use App\Events\CashOutInstallment;
use App\Mail\CashOutInstallmentMail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Encore\Admin\Config\Config;

class SendCashOutInstallmentMail
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
    public function handle(CashOutInstallment $event)
    {
        $configEmail = explode(";", config('cash_out_notification'));
        
        Mail::to($configEmail)
            ->queue(new CashOutInstallmentMail($event->transaction));
    }
}
