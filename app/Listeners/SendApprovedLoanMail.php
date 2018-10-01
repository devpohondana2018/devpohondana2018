<?php

namespace App\Listeners;

use App\Events\LoanApproved;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;
use App\Mail\LoanApprovedEmail;
use Encore\Admin\Config\Config;

class SendApprovedLoanMail
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
    public function handle(LoanApproved $event)
    {
        $configEmail = explode(";", config('loan_approved_email_notification'));
        /*$adminEmail  = explode(";", config('email_admin'));
        $ccEmail     = array_merge($configEmail, $adminEmail);*/
        
        Mail::to($event->loan->user)
            ->cc($configEmail)
            ->queue(new LoanApprovedEmail($event->loan));
    }
}
