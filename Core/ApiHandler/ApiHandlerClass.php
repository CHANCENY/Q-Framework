<?php

namespace ApiHandler;

class ApiHandlerClass
{
    public static function headersRequest(){
        return http_get_request_headers();

    }

    public static function findHeaderValue($headerType){
        $headers = http_get_request_headers();

        if(empty($headers)){
            return NULL;
        }

        foreach ($headers as $header=>$value){
            if(isset($header[$headerType]) && !empty($header[$headerType])){
                return $value;
            }
        }
    }

    public static function getPostBody($ssoc = true){
            return json_encode(file_get_contents('php://input'), $ssoc);
    }

    public static function paramsQuery(){
        $url = $_SERVER['REQUEST_URI'];
        $line = parse_url($url, PHP_URL_QUERY);
        parse_str($line, $query);

        if(isset($query)){
            return $query;
        }
        return [];
    }

    public static function createParams($data = []){
        $line = "";
        foreach ($data as $datum=>$value){
            $line .= $datum.'='.$value.'&';
        }

        if(empty($line)){
            return NULL;
        }

        $line = substr($line, 0, strlen($line) - 1);
        return trim($line);
    }

    public static function stringfiyData($data){
        return json_encode($data);
    }

}