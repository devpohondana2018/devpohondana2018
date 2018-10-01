<?php

namespace App\Listeners;

use App\Events\InvestmentApproved;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;
use App\Mail\InvestmentApprovedEmail;
use Encore\Admin\Config\Config;

class SendApprovedInvestmentMail
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
     * @param  InvestmentApproved  $event
     * @return void
     */
    public function handle(InvestmentApproved $event)
    {
        $configEmail = explode(";", config('investment_approved_email_notification'));
        /*$adminEmail  = explode(";", config('email_admin'));
        $ccEmail     = array_merge($configEmail, $adminEmail);*/

        Mail::to($event->investment->user)
            ->cc($configEmail)
            ->queue(new InvestmentApprovedEmail($event->investment));
    }
}
