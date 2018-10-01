<?php

namespace App\Listeners;

use App\Events\InvestmentDeclined;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;
use App\Mail\InvestmentDeclinedEmail;
use Encore\Admin\Config\Config;

class SendInvestmentDeclinedMail
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
    public function handle(InvestmentDeclined $event)
    {
        $configEmail = explode(";", config('investment_declined_email_notification'));
        /*$adminEmail  = explode(";", config('email_admin'));
        $ccEmail     = array_merge($configEmail, $adminEmail);*/

        Mail::to($event->investment->user)
            ->cc($configEmail)
            ->queue(new InvestmentDeclinedEmail($event->investment));
    }
}
