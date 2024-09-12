
<?php
require_once __DIR__ . '/../vendor/autoload.php';
use \Firebase\JWT\JWT;

/**
 * User model for handling authentication, user management, and CRUD operations
 * This model uses JWT for authentication
 */
class User {
    private $conn;
    private $table = 'users';
    private $jwt_secret;

    // User properties
    public $id;
    public $name;
    public $email;
    public $password;

    /**
     * Constructor to initialize the database connection
     * @param PDO $db PDO database connection object
     */
    public function __construct($db) {
        $this->conn = $db;
        $this->jwt_secret = $_ENV["JWT_SECRET"];
    }

    /**
     * Registers a new user by inserting their details into the database
     * The password is hashed before storing in the database
     * @return bool Returns true if the user is successfully registered, false otherwise
     */
    public function register() {
        $query = "INSERT INTO " . $this->table . " (name, email, password) VALUES (:name, :email, :password)";
        $stmt = $this->conn->prepare($query);
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $this->password);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * Logs in a user by verifying their email and password
     * If successful, generates a JWT token
     * @return array|bool Returns an array with the JWT token if successful, or false if login fails
     */
    public function login() {
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $this->email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($this->password, $user['password'])) {
            $token = [
                "iss" => "http://localhost",
                "aud" => "http://localhost",
                "iat" => time(),
                "nbf" => time(),
                "data" => [
                    "id" => $user['id'],
                    "name" => $user['name'],
                    "email" => $user['email']
                ]
            ];
            $jwt = JWT::encode($token, $this->jwt_secret, 'HS256');
            return ['token' => $jwt];
        }
        return false;
    }
    

    /**
     * Validates a JWT token and returns the decoded data if the token is valid
     * @param string $jwt The JWT token to validate
     * @return object|bool Returns the decoded token data on success, or false on failure
     */
    public function validateToken($jwt) {
        try {
            $decoded = JWT::decode($jwt, new \Firebase\JWT\Key($this->jwt_secret, 'HS256'));
            return $decoded->data;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Retrieves a user by their ID
     * @param int $id The user ID
     * @return array|bool Returns array with user details or false if no user is found
     */
    public function getUserById($id) {
        $query = "SELECT id, name, email FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            return $user;
        }
        return false;
    }
}
