<?php

namespace App\Listeners;

use Mail;
use App\Events\CashOutLoan;
use App\Mail\CashOutLoanMail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Encore\Admin\Config\Config;

class SendCashOutLoanMail
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
    public function handle(CashOutLoan $event)
    {
        $configEmail = explode(";", config('cash_out_notification'));
        
        Mail::to($configEmail)
            ->queue(new CashOutLoanMail($event->transaction));
    }
}
