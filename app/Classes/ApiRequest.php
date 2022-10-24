<?php

namespace App\Classes;


class ApiRequest
{
    private $url;
    private $data;
    private $headers;
    private $api_key;

    private $response_data;
    private $response_headers;
    /**
    * Create API Request Instance
    * @param string $url The API Request URL
    */

    function __construct($url){
        $this->url = $url;
        $this->data = [];
        $this->headers = array( //default headers
            "Accept: application/json",
            "Content-Type: application/json"
         );
         $this->response_data = false;
         $this->response_headers = false;
         $this->api_key = false;
    }
        /**
    * @param string $url The API Request URL
    */
    public function setUrl($url){
        $this->url = $url;
        return $this;
    }

    /**
    * @param array $data The API request data to be sent. Will be parsed into URL for get requests.
    */
    public function setData($data){
        $this->data = $data;
        return $this;
    }
    public function getData(){
        return $this->data;
    }
    /**
     * @default ["Accept: application/json", "Content-Type: application/json"];
    * @param array $headers The API request data to be sent.
    */
    public function setHeaders($headers){
        $this->headers = $headers;
        return $this;
    }

        /**
    * @return bool|object  Get the response data from the API request. False if none.
    */
    public function getResponseData(){
        return $this->response_data;
    }
    /**
    * @return bool|object  Get the response headers from the API request. False if none.
    */
    public function getResponseHeaders(){
        return $this->response_headers;
    }
    /**
     * Add the strafes.net API Key to the headers
    */
    public function useStrafesNetApiKey(){
        $this->api_key = "api-key: ".env('STRAFES_NET_API_KEY');
        return $this;
    }

    public function sendGetRequest(){
        $curl = curl_init();
        $data = http_build_query($this->data);
        $url = $this->url . $data;
        $headers = $this->headers;
        $headers[] = $this->api_key;
        
        
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers );
        $response_headers = [];
        curl_setopt($curl, CURLOPT_HEADERFUNCTION,
        function($curl, $header) use (&$response_headers)
        {
            $len = strlen($header);
            $header = explode(':', $header, 2);
            if (count($header) < 2) // ignore invalid headers
            return $len;

            $response_headers[strtolower(trim($header[0]))][] = trim($header[1]);
            
            return $len;
        });
        $response_data = curl_exec( $curl );
        curl_close($curl);
        $this->response_headers = $response_headers;
        $this->response_data = json_decode($response_data);
        return $this;
    }

    public function sendPostRequest(){
        $curl = curl_init();
        $data = http_build_query($this->data);
        $url = $this->url;
        $headers = $this->headers;
        if($this->api_key){
            $headers[] = $this->api_key;
        }
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        //curl_setopt($curl, CURLOPT_HTTPHEADER, $headers );
        $response_headers = $this->response_headers_function($curl);
        //curl_setopt($curl, CURLOPT_FRESH_CONNECT, TRUE);
        $response_data = curl_exec($curl);
        curl_close($curl);
        $this->response_headers = $response_headers;
        $this->response_data = json_decode($response_data);
        return $this;
    }

    private function filter_response_data($data){
        if($data !== false){
            $data = json_decode($data);
        }
        else{
            $data = [];
        }
        return $data;
    }

    private function response_headers_function($curl){
        
    }

    /*protected function sendRequest(){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $request_url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $headers = array(
            "Accept: application/json",
            "Content-Type: application/json",
         );
        $data = http_build_query($request_data);

        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        $data_response = curl_exec($curl);
        curl_close($curl);
        if($data_response !== false){
            $data_response = json_decode($data_response);
            return $data_response;
        }
        return false;
    }
    
    public function request($type, $request_url, $request_data){
        if(is_string($type) && isset($request_url)){
            if(strtolower($type)==='post' && isset($request_data)){
                return $this->post($request_url,$request_data);
            }
            elseif(strtolower($type)==='get'){
                return $this->get($request_url);
            }
        }
        return false;
    }



    public function post($request_url, $request_data){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $request_url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $headers = array(
            "Accept: application/json",
            "Content-Type: application/json",
         );
        $data = http_build_query($request_data);

        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        $data_response = curl_exec($curl);
        curl_close($curl);
        if($data_response !== false){
            $data_response = json_decode($data_response);
            return $data_response;
        }
        return false;
    }
    public function get($request_url){
        $headers = array(
            "Accept: application/json"
        );
        $curl = curl_init();
        $headers_response = [];
        if(parse_url($request_url, PHP_URL_HOST)==='api.strafes.net'){
            $headers[] = "api-key: ".env('STRAFES_NET_API_KEY');
            //curl_setopt( $curl , CURLOPT_HEADER , true );
            curl_setopt($curl, CURLOPT_HEADERFUNCTION,
                function($curl, $header) use (&$headers_response)
                {
                    $len = strlen($header);
                    $header = explode(':', $header, 2);
                    if (count($header) < 2) // ignore invalid headers
                    return $len;

                    $headers_response[strtolower(trim($header[0]))][] = trim($header[1]);
                    
                    return $len;
                }
                );
        }
        curl_setopt($curl, CURLOPT_URL, $request_url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers );
        $data_response = curl_exec( $curl );
        curl_close($curl);
        if(parse_url($request_url, PHP_URL_HOST)==='api.strafes.net'){
            return ['headers' => $headers_response, 'data' => json_decode($data_response)];
        }
        if($data_response !== false){
            return json_decode($data_response); 
        }
        return false;
    }

    public function get_with_headers($request_url,$headers){
        $headers[] = "Accept: application/json";
        $curl = curl_init();
        $headers_response = [];
        if(parse_url($request_url, PHP_URL_HOST)==='api.strafes.net'){
            $headers[] = "api-key: ".env('STRAFES_NET_API_KEY');
            //curl_setopt( $curl , CURLOPT_HEADER , true );
            curl_setopt($curl, CURLOPT_HEADERFUNCTION,
                function($curl, $header) use (&$headers_response)
                {
                    $len = strlen($header);
                    $header = explode(':', $header, 2);
                    if (count($header) < 2) // ignore invalid headers
                    return $len;

                    $headers_response[strtolower(trim($header[0]))][] = trim($header[1]);
                    
                    return $len;
                }
                );
        }
        curl_setopt($curl, CURLOPT_URL, $request_url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers );
        $data_response = curl_exec( $curl );
        curl_close($curl);
        if(parse_url($request_url, PHP_URL_HOST)==='api.strafes.net'){
            return ['headers' => $headers_response, 'data' => json_decode($data_response)];
        }
        if($data_response !== false){
            return json_decode($data_response); 
        }
        return false;
    }

    public function strafes_net_get($request_url){
        $headers = array(
            "Accept: application/json",
            "api-key: ".env('STRAFES_NET_API_KEY')
        );
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $request_url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers );
        $data_response = curl_exec( $curl );
        curl_close($curl);
        if($data_response !== false){
            return json_decode($data_response); 
        }
        return false;
    }*/
}
