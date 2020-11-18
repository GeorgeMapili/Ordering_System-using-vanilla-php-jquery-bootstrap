<?php

class Db
{
    private $DB_HOST = "localhost";
    private $DB_USER = "root";
    private $DB_NAME = "sadproject";
    private $DB_PASS = "";

    public function connect()
    {
        try {
            $con = new PDO("mysql:host=$this->DB_HOST;dbname=$this->DB_NAME", $this->DB_USER, $this->DB_PASS);
            $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Connection Success";
        } catch (PDOException $e) {
            die("Connection Failed " . $e->getMessage());
        }
        return $con;
    }
}
