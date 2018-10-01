<?php 
namespace App\Libraries\Traits;

/**
 * Created By Irsal Firdaus
 * 11/14/2017
 */
trait ServerErrorResponse
{
    public static function internalServerError($message = null)
    {
    	$defaultMessage = 'Something went wrong. Please try again.';
    	$message = ( empty($message) ? $defaultMessage : (env('API_DEBUG') ? $message : $defaultMessage) );
    	return static::passingVariable($message, 500);
    }
}
