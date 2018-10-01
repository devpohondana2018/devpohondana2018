<?php 
namespace App\Libraries\Traits;

/**
 * Created By Irsal Firdaus
 * 11/14/2017
 */
trait ClientErrorResponse
{
    public static function badRequest($message = null)
    {
        return static::passingVariable( (empty($message) ? 'Bad request' : $message) , 400);
    }

    public static function unAuthorized($message = null)
    {
        return static::passingVariable( (empty($message) ? 'Unauthorized' : $message) , 401);
    }

    public static function forbidden($message = null)
    {
        return static::passingVariable( (empty($message) ? 'Forbidden' : $message) , 403);
    }

    public static function notFound($message = null)
    {
        return static::passingVariable( (empty($message) ? 'Resources not found' : $message) , 404);
    }

    public static function unprocessableEntity($message = null)
    {
        return static::passingVariable( (empty($message) ? 'Unprocessable entity' : $message) , 422);
    }
}
