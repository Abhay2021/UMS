<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if(! function_exists('fetchWeatherReport'))
{
    function fetchWeatherReport($city)
    {
        $curl = curl_init();
        $API = '161dd4f51faf20d6cfd1c2f86c6a7079';
        curl_setopt_array($curl, array(
        CURLOPT_URL => "api.openweathermap.org/data/2.5/weather?q=".$city."&APPID=".$API."",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache"
        ),
        ));

        $response = curl_exec($curl);
        
        $err = curl_error($curl);
        curl_close($curl);
        if($response)
        {
            $response = json_decode($response, true);
            // echo "<pre>";
            // print_r($response);exit; 
        return $response;
        }else{
        return FALSE;
        }
    }
}

if(! function_exists('kelvinToCelsius'))
{ 
    function kelvinToCelsius($temp)
    {
        return $temp-273.15;
    }

}