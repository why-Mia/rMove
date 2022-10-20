<?php

namespace App\Classes;


class ApiRequest
{
    
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
    }
}
