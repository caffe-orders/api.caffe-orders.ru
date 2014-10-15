<?php
class DbWorker extends PDO
{
    Public  $db;   
    function __construct()
    {
        $dsn =  DB_TYPE.":dbname=" . DB_NAME . ";host=" . DB_HOST;
        try
        {
            $this->db = new PDO($dsn, DB_USER, DB_PASS);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->query("set names utf8");
        } 
        catch (PDOException $e)
        {            
            echo 'Error : '.$e->getMessage();
            exit();            
        }
    }
}
?>