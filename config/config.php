<?php 
define('BASE_URL', '/PHP-sample-code/public/index.php');

class Database {
    private $host = 'localhost';
    private $db_name = 'laravel_sample_code';
    private $username = 'root';
    private $password = 'root';
    public $conn;


    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Check for connection issues
            if (!$this->conn) {
               // echo "Failed to connect to the database.";
            } else {
               // echo "Connection successful.";
            }
    
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
    
}
?>
