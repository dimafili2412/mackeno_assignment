
<?php
// DB class for creating DB connection object
class Database {
    private $db_host;
    private $db_name;
    private $username;
    private $password;
    public $conn;

    /**
     * Constructor to initialize database credentials from environment variables
     */
    public function __construct() {
        $this->db_host = $_ENV["DB_HOST"];
        $this->db_name = $_ENV["DB_NAME"];
        $this->username = $_ENV["DB_USERNAME"];
        $this->password = $_ENV["DB_PASSWORD"];
    }

    /**
     * Get the PDO connection to the MySQL database
     * @return PDO|null Returns the PDO connection object or null on failure
     */
    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->db_host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
