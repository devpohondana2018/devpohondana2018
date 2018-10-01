<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;
use App\Mail\UserVerifiedNotification;
use Encore\Admin\Config\Config;

class SendUserVerifiedEmail
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
        $toEmail = explode(";", config('user_verified_email_notification'));
        /*$ccEmail = explode(";", config('email_admin'));*/
        
        Mail::to($toEmail)
            /*->cc(config('email_admin'))*/
            ->queue(new UserVerifiedNotification($event->user));
    }
}
