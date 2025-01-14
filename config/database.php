<?php
class Database
{
    private $host = "localhost";
    private $db_name = "smartrecruit";
    private $username = "root";
    private $password = "";
    private $conn;

    public function getConnection()
    {
        static $pdo = null;
        if ($pdo === null) {
            $host = 'localhost';
            $db   = 'smartrecruit';
            $user = 'root';
            $pass = '';
            $charset = 'utf8mb4';

            $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            try {
                $pdo = new PDO($dsn, $user, $pass, $options);
            } catch (PDOException $e) {
                throw new PDOException($e->getMessage(), (int)$e->getCode());
            }
        }
        return $pdo;
    }
}
