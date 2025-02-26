<?php

namespace Src\System;

class DatabaseConnector
{

    private $dbConnection = null;

    public function __construct()
    {
        $host = '127.0.0.1';
        $port = '3306';
        $db   = 'api_php_test';
        $user = 'orbita_user';
        $pass = 'Orbita123';

        try {
            $this->dbConnection = new \PDO(
                "mysql:host=$host;port=$port;charset=utf8mb4;dbname=$db",
                $user,
                $pass
            );
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function getConnection()
    {
        return $this->dbConnection;
    }
}
