<?php

class Connection {
    private $host = "localhost";
    private $dbname = "asystem";
    private $username = "root";
    private $password = "";
    private $port = "3306";

    public function connect() {
        try {
            $connection = new PDO(
                "mysql:host=$this->host;dbname=$this->dbname;port=$this->port;charset=utf8",
                $this->username,
                $this->password
            );
            $connection->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );
            return $connection;
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }
}
