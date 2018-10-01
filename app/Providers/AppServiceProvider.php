<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Encore\Admin\Config\Config;
use Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
	public function register()
	{
	    //
	}

	public function boot()
	{
	    Schema::defaultStringLength(191);
	    Config::load();

	    Validator::extend('olderThan', function($attribute, $value, $parameters) {
            $minAge = ( ! empty($parameters)) ? (int) $parameters[0] : 13;
            return \Carbon\Carbon::now()->diff(new \Carbon\Carbon($value))->y >= $minAge;
            // return (new DateTime)->diff(new DateTime($value))->y >= $minAge;
        });
	}
}
