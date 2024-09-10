<?php

namespace app\Config;

use PDO;
use PDOException;

require_once __DIR__ . '/../init.php';

class Dbh
{
    // protected $host = $_ENV['DB_HOST'];
    // protected $dbname = $_ENV['DB_NAME'];
    // protected $username = $_ENV['DB_USER'];
    // protected $password = $_ENV['DB_PASSWORD'];
    protected $pdo;

    // Constructor to initialize the database connection
    public function __construct()
    {
        // echo $this->host;
        $dsn = "mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']};charset=utf8mb4";

        try {
            $this->pdo = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Set error mode to exception
        } catch (PDOException $e) {
            $this->logError($e->getMessage());
            die("Connection failed: Please check the log file for more details.");
        }
    }

    // Method to execute SQL queries
    public function execute($sql, $params = [])
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($params);
            // return $stmt->rowCount();
        } catch (PDOException $e) {
            $this->logError($e->getMessage());
            return false;
        }
    }

    // Method to fetch single row
    public function fetch($sql, $params = [])
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->logError($e->getMessage());
            return false;
        }
    }

    // Method to fetch all rows
    public function fetchAll($sql, $params = [])
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->logError($e->getMessage());
            return false;
        }
    }

    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }

    // Method to log errors to a file
    private function logError($message)
    {
        $errorLog = 'error_log.txt';
        $current = file_get_contents($errorLog);
        $current .= date('Y-m-d H:i:s') . " - " . $message . "\n";
        file_put_contents($errorLog, $current);
    }
}

// Instantiate dbh:
$dbh = new Dbh();
