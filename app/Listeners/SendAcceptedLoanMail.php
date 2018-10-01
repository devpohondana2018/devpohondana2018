<?php

namespace App\Listeners;

use App\Events\LoanAccepted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;
use App\Mail\LoanAcceptedEmail;
use Encore\Admin\Config\Config;

class SendAcceptedLoanMail
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
     * @param  LoanAccepted  $event
     * @return void
     */
    public function handle(LoanAccepted $event)
    {
        $toEmail = explode(";", config('loan_accepted_email_notification'));
        //$ccEmail = explode(";", config('email_admin'));

        Mail::to($event->loan->user)
            ->cc($toEmail)
            ->queue(new LoanAcceptedEmail($event->loan));
    }
}
