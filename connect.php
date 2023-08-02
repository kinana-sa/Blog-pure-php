<?php

class Database
{

    private static $instance;
    private $connection;

    public function __construct()
    {
        $local = "localhost";
        $username = 'root';
        $password = '';
        $database = 'myblog';

        // Create a new database connection
        $this->connection = new mysqli($local, $username, $password, $database);

        // Check if connection was successful
        if ($this->connection->connect_error) {
            die('Database connection failed: ' . $this->connection->connect_error);
        }
    }

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->connection;
    }
}