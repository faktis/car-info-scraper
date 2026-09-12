<?php

class Database
{
    private PDO $connection;

    public function __construct()
    {
        $host = getenv('MYSQLHOST') ?: 'localhost';
        $port = getenv('MYSQLPORT') ?: '3306';
        $database = getenv('MYSQLDATABASE') ?: 'car_info_scraper';
        $username = getenv('MYSQLUSER') ?: 'root';
        $password = getenv('MYSQLPASSWORD') ?: '';

        $dsn = "mysql:host={$host};port={$port};dbname={$database};charset=utf8mb4";

        $this->connection = new PDO( $dsn, $username, $password );

        $this->connection->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }
}