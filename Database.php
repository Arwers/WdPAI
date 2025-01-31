<?php

require_once "Config.php";

class Database {
    private $username;
    private $database;
    private $pass;
    private $server;
    private $host;

    public function __construct() {
        $this->username = USERNAME;
        $this->database = DATABASE;
        $this->pass     = PASS;
        $this->server   = SERVER;
        $this->host     = HOST;
    }

    public function connect() {
        try {
            $conn = new PDO(
                "pgsql:host={$this->server};port={$this->host};dbname={$this->database};sslmode=require",
                $this->username,
                $this->pass
            );

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        }
        catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
}
