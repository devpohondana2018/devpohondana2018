<?php

namespace App\Listeners;

use App\Events\InvestmentAccepted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;
use App\Mail\InvestmentAcceptedEmail;
use Encore\Admin\Config\Config;

class SendAcceptedInvestmentMail
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
     * @param  InvestmentAccepted  $event
     * @return void
     */
    public function handle(InvestmentAccepted $event)
    {
        $toEmail = explode(";", config('investment_accepted_email_notification'));
        //$ccEmail = explode(";", config('email_admin'));

        Mail::to($toEmail)
            /*->cc(config('email_admin'))*/
            ->queue(new InvestmentAcceptedEmail($event->investment));
    }
}
