<?php 
namespace App\Libraries\Traits;

/**
 * Created By Irsal Firdaus
 * 11/14/2017
 */
trait SuccessResponse
{
    /**
     * summary
     */
    public static function ok($message = null)
    {
        return static::passingVariable( (empty($message) ? 'Ok' : $message) , 200);
    }

    public static function created($message = null)
    {
        return static::passingVariable( (empty($message) ? 'Created' : $message) , 201);
    }
}
