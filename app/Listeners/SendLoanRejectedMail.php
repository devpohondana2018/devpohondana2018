<?php

namespace App\Listeners;

use App\Events\LoanRejected;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;
use App\Mail\LoanRejectedEmail;
use Encore\Admin\Config\Config;

class SendLoanRejectedMail
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
     * @param  LoanRejected  $event
     * @return void
     */
    public function handle(LoanRejected $event)
    {
        $configEmail = explode(";", config('loan_rejected_email_notification'));
        /*$adminEmail  = explode(";", config('email_admin'));
        $ccEmail     = array_merge($configEmail, $adminEmail);*/

        Mail::to($event->loan->user)
            ->cc($configEmail)
            ->queue(new LoanRejectedEmail($event->loan));
    }
}
