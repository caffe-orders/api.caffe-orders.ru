<?php
class DbWorker
{
    Public  $db;  
    
    public function __construct()
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
    
    public function insert($str,$array)
    {  
        $sth = $this->db->prepare($str);
        $sth->execute($array);
        return $this->db->lastInsertId();
    }
    
    public function select($str,$array=null)
    {
        $sth = $this->db->prepare($str);
        $sth->execute($array);
        return $sth->fetchAll();
    }
    
    public function delete($str,$array)
    {
        $sth = $this->db->prepare($str);
        $sth->execute($array);
    }
    
    public function update($str,$array)
    {
        $sth = $this->db->prepare($str);
        $sth->execute($array);
    }
}
?>