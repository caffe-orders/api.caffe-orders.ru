<?php
Class Validation
{
    public function CheckLogin($String)
    {
        if(preg_match("/^[a-z0-9_-]{3,16}$/",$String))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    public function CheckPassword($String)
    {
        if(preg_match("/^[a-z0-9_-]{3,16}$/",$String))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    public function IsEmpty($String)
    {
        If(trim($String, ' ') == '')
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}
