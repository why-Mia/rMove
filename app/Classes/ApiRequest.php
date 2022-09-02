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
        $response = @file_get_contents($request_url);
        if($response !== false){
            return json_decode($response); 
        }
        return false;
    }
}
