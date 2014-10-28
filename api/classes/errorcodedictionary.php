<?php
class ErrorCodeDictionary
{
    private function __construct()
    {
        //You shall not pass!
    }

    private function __clone()
    {
        //Me not like clones! Me smash clones!
    }
    
    private static $_errorDictionary = array
    (    
        '200' => '200 OK',
        '400' => '400 Bad Request or low access priority',
        '401' => '401 Unauthorized',
        '500' => '500 Internal Server Error',
        '600' => '600 Access Denied',
        '601' => '601 Unknown Error Code',
        '602' => '602 Arguments Not Exists'
    );
    
    static public function GetCode($key)
    {
        if(!isset(self::$_errorDictionary[$key]))        
        {
            $key = '601';
        }
        
        return self::$_errorDictionary[$key];
    }
}