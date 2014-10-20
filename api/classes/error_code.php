<?php
class Error_Code
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
        '400' => '400 Bad Request'
    );
    
    static public function GetCode($key)
    {
        if(!isset(self::$_errorDictionary[$key]))        
        {
            $key = '400';
        }
        
        return self::$_errorDictionary[$key];
    }
}


    