<?php

namespace App\Listeners;

use App\Events\LoanDeclined;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;
use App\Mail\LoanDeclinedEmail;
use Encore\Admin\Config\Config;

class SendLoanDeclinedMail
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
     * @param  object  $event
     * @return void
     */
    public function handle(LoanDeclined $event)
    {
        $configEmail = explode(";", config('loan_declined_email_notification'));
        /*$adminEmail  = explode(";", config('email_admin'));
        $ccEmail     = array_merge($configEmail, $adminEmail);*/
        
        Mail::to($event->loan->user)
            ->cc($configEmail)
            ->queue(new LoanDeclinedEmail($event->loan));
    }
}
