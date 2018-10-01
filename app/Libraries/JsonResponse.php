<?php 
namespace App\Libraries;
/**
 * Created By Irsal Firdaus
 * 11/14/2017
 */

use App\Libraries\Traits\InformationalResponse;
use App\Libraries\Traits\SuccessResponse;
use App\Libraries\Traits\RedirectionResponse;
use App\Libraries\Traits\ClientErrorResponse;
use App\Libraries\Traits\ServerErrorResponse;

class JsonResponse
{
	use InformationalResponse;
	use SuccessResponse;
	use RedirectionResponse;
	use ClientErrorResponse;
	use ServerErrorResponse;

    public function __construct()
    {
        
    }

    public static function passingVariable($message, $code)
    {
    	$response['message']     = $message;
    	$response['status_code'] = $code;
    	return static::printJson($response);
    }

    public static function printJson($response)
    {
    	$code = (array_key_exists('status_code', $response) ? $response['status_code'] : 200);
    	return response()->json($response, $code);
    }
}
