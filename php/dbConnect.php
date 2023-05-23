<?php

class dbConnect
{
    public $serverName = "localhost";
    public $userName = "root";
    public $password = "";
    public $dbName = "test";
    // public $connection;
    function start_connection() 
    {
        try {
            $connection = new PDO("mysql:host=$this->serverName;dbname=$this->dbName", $this->userName, $this->password);
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // echo "CONNECTED";
            return $connection;
        } catch (PDOException $e) {
            echo "Error" .$e->getMessage();
        }
    }
}
