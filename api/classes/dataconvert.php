<?php
class DataConvert 
{
    public static function PdoArrayToArray($data)
    {
        $parsedData = array();
        foreach($data as $key => $value)
        {
            $parsedData[$key] = $value; 
        }
        return $parsedData;
    }
}