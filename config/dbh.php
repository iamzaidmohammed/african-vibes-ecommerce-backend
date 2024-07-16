<?php
class Dbh
{
    private $host = 'localhost';
    private $dbname = 'african_vibes_ecommerce';
    private $username = 'root';
    private $password = '';
    private $pdo;

    // Constructor to initialize the database connection
    public function __construct()
    {
        $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4";

        try {
            $this->pdo = new PDO($dsn, $this->username, $this->password);
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
