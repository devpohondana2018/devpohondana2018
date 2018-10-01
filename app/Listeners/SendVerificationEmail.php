<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;
use App\Mail\SendVerificationToken;
use App\Mail\SendNewUserRegistration;
use Encore\Admin\Config\Config;
use Illuminate\Support\Facades\Log;

class SendVerificationEmail
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
    public function handle($event)
    {
        try {
            $toEmail = explode(";", config('user_unverified_email_notification'));
            //$ccEmail = explode(";", config('email_admin'));

            Mail::to($event->user)->queue(new SendVerificationToken($event->user));
            Mail::to($toEmail)/*->cc($ccEmail)*/->queue(new SendNewUserRegistration($event->user));
        } catch(Exception $e) {
            Log::error($e);
        }
    }
}
