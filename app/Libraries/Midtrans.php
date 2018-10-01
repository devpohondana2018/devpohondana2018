<?php 
namespace App\Libraries;
/**
 * CREATED BY IRSAL FIRDAUS
 */
class Midtrans 
{
    public $serverKey;
    public $clientKey;
    private $isProduction;
    private $baseUrl;
    private $apiUrl;

    public function __construct()
    {
        $this->serverKey    = env('MIDTRANS_SERVER_KEY');
        $this->clientKey    = env('MIDTRANS_CLIENT_KEY');
        $this->isProduction = env('MIDTRANS_IS_PRODUCTION_');
        $this->baseUrl      = $this->isProduction ? env('MIDTRANS_PRODUCTION') : env('MIDTRANS_SANBOX');
        $this->apiUrl       = $this->isProduction ? 'https://app.midtrans.com/snap/v1/transactions' : 'https://app.sandbox.midtrans.com/snap/v1/transactions';
    }

    public function getClientKey()
    {
        return $this->clientKey;
    }

    public function getServerKey()
    {
        return $this->serverKey;
    }

    public function isProduction()
    {
    	return $this->isProduction;
    }

    public function getBaseUrl($params = null)
    {
        return $this->baseUrl . $params;
    }

    public function remote($url, $body, $method)
    {
        $headers = array(
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Basic ' . base64_encode($this->serverKey . ':')
        );
         
        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL,            $url);
        curl_setopt( $ch,CURLOPT_HTTPHEADER,     $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );

        if ($method == 1) {
            curl_setopt( $ch,CURLOPT_POST,       true );
            curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $body ) );
        }
        return curl_exec($ch );
    }
}