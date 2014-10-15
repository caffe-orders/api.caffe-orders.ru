<?php
class DbWorker
{
    private static $instance = null;

    private function __construct()
    {
        //You shall not pass!
    }

    private function __clone()
    {
        //Me not like clones! Me smash clones!
    }

    public static function GetInstance()
    {
        if (!isset(self::$instance)) 
        {
            $dsn =  DB_TYPE.":dbname=" . DB_NAME . ";host=" . DB_HOST;
            
            try
            {
                self::$instance = new PDO($dsn, DB_USER, DB_PASS);
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$instance->query("set names utf8");
            }
            catch (PDOException $e)
            {            
                echo 'Error : '.$e->getMessage();
                exit();            
            }
        }
        return self::$instance;
    }
}
?>
