<?php
class Error_Code
{    
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


    