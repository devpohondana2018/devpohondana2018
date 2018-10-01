<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\User;
use Mail;
use App\Events\UserRegistered;

class UserVerificationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        User::created(function($user) {
            $token = $user->verificationToken()->create([
                'token' => bin2hex(random_bytes(32))
            ]);
            event(new UserRegistered($user));
        });
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}