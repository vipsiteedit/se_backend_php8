<?php
class paypal{
    public $request_method;
    public $_errors = array();
    protected $_credentials;
    protected $_endPoint = 'https://api-3t.sandbox.paypal.com/nvp';
    protected $_version = '98.0';

    public function __construct($user, $pass, $signature, $paypal_server = 'sandbox', $request_method = 'file_get_contents')
    {
        $this->_credentials = array(
            'USER' => $user,
            'PWD' => $pass,
            'SIGNATURE' => $signature
        );
        $this->request_method = $request_method;
        if($paypal_server == 'live'){
            $this->_endPoint = 'https://api-3t.paypal.com/nvp';
        }
    }

    public function request($method, $params = array())
    {
        $this->_errors = array();
        if(empty($method)){
            $this->_errors = array('There is no API Method');
            return false;
        }
        $requestParams = array('METHOD' => $method, 'VERSION' => $this->_version) + $this->_credentials;
        $request = http_build_query($requestParams + $params); 	//build a query string based on the array of request parameters
        if($this->request_method == 'curl'){
            //build the HTTP header required by Paypal
            $http_header = array(
                'X-PAYPAL-SECURITY-USERID' => $this->_credentials['USER'],
                'X-PAYPAL-SECURITY-PASSWORD' => $this->_credentials['PWD'],
                'X-PAYPAL-SECURITY-SIGNATURE' => $this->_credentials['SIGNATURE'],
                'X-PAYPAL-REQUEST-DATA-FORMAT' => 'JSON',
                'X-PAYPAL-RESPONSE-DATA-FORMAT' => 'JSON'
            );
            //set options for CURL
            $curlOptions = array (
                CURLOPT_HTTPHEADER => $http_header,
                CURLOPT_URL => $this->_endPoint,
                CURLOPT_VERBOSE => 1,
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_SSL_VERIFYHOST => 2,
                CURLOPT_CAINFO => dirname(__FILE__) . '/cert/cacert.pem', //CA cert file
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => $request
            );
            $ch = curl_init();
            curl_setopt_array($ch, $curlOptions);
            $response = curl_exec($ch); //make the request
            if(curl_errno($ch)){
                $this->_errors = curl_error($ch);
                curl_close($ch);
                return false;
            } else {
                curl_close($ch);
                $responseArray = array();
                parse_str($response, $responseArray);   //convert the response string to an array
                return $responseArray;
            }
        } else if($this->request_method == 'file_get_contents'){
            //build the HTTP header required by Paypal
            $context_options = array(
                "http" => array(
                    "method" => "POST",
                    "header"  => "Content-type: application/x-www-form-urlencoded\r\n" .
                        "X-PAYPAL-SECURITY-USERID: " . $this->_credentials['USER'] . "\r\n" .
                        "X-PAYPAL-SECURITY-PASSWORD: " . $this->_credentials['PWD'] . "\r\n" .
                        "X-PAYPAL-SECURITY-SIGNATURE: " . $this->_credentials['SIGNATURE'] . "\r\n" .
                        "X-PAYPAL-REQUEST-DATA-FORMAT: JSON\r\n" .
                        "X-PAYPAL-RESPONSE-DATA-FORMAT: JSON\r\n",
                    "content" => $request
                )
            );
            $context = stream_context_create($context_options); //create context for file_get_contents
            $response = file_get_contents($this->_endPoint, false, $context); //make the request
            $responseArray = array();
            parse_str($response, $responseArray); //convert the response string to an array
            return $responseArray;
        }
    }
}
