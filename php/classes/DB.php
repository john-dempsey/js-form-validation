<?php

class DB {
    private $conn;

    public function __construct() {
        $this->conn = null;
    }

    public function open() {
        if ($this->conn === null) {
            $server = 'localhost';
            $database = 'test';
            $username = 'root';
            $password = '';

            $dsn = "mysql:host={$server};dbname={$database}";

            $this->conn = new PDO($dsn, $username,$password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );
        }
        return $this->conn;
    }

    public function isOpen() {
        return $this->conn !== null;
    }

    public function close() {
        $this->conn = null;
    }
}