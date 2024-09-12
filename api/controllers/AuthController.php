<?php
require_once __DIR__ . '/../models/User.php';

/**
 * Authentication controller for handling user actions and fetching user details. It interacts with the User model for db operations
 */
class AuthController {
    private $db;
    private $user;
    private $validator;

    /**
     * Constructor that initializes the controller with the database connection and User model
     * @param PDO $db A PDO connection object
     * @param ValidatorService $validator An instance of the ValidatorService for validating inputs
     */
    public function __construct($db, $validator) {
        $this->db = $db;
        $this->user = new User($db);
        $this->validator = $validator;
    }

    /**
     * Get the User model instance
     * @return User The User model instance
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * Registers a new user with the provided data
     * @param array $data Array containing the user's details
     * @return string A JSON response with a success or failure message
     */
    public function register($data) {
        // Validate inputs
        $errors = [];
        if ($error = $this->validator->empty($data['name'], "Name")) $errors[] = $error;
        if ($error = $this->validator->email($data['email'], true)) $errors[] = $error;
        if ($error = $this->validator->password($data['password'])) $errors[] = $error;
        if (!empty($errors)) {
            http_response_code(400); 
            return json_encode(['message' => 'Validation failed', 'errors' => $errors]);
        }
        // Create user
        $this->user->name = $data['name'];
        $this->user->email = $data['email'];
        $this->user->password = $data['password'];
        if ($this->user->register()) {
            http_response_code(201); 
            return json_encode(['message' => 'User registered successfully']);
        } else {
            http_response_code(500); 
            return json_encode(['message' => 'User registration failed']);
        }
    }

    /**
     * Handles the user login process.
     * @param array $data An associative array containing login credentials
     * @return string A JSON response containing a JWT token if successful or an error message
     */
    public function login($data) {
        $this->user->email = $data['email'];
        $this->user->password = $data['password'];
        $jwt = $this->user->login();
        if ($jwt) {
            return json_encode(['token' => $jwt['token']]);
        } else {
            http_response_code(401);
            return json_encode(['message' => 'Login failed']);
        }
    }

    /**
     * Fetches the user details based on the provided JWT token
     * @param string $jwt The JSON Web Token used to validate the user's session
     * @return string A JSON response containing the user details or an error message
     */
    public function getUserDetails($jwt) {
        if ($jwt) {
            $userData = $this->user->validateToken($jwt);
            if ($userData) {
                // Fetch the user details from the database using the ID from the token
                return json_encode($this->user->getUserById($userData->id));
            } else {
                http_response_code(401);
                return json_encode(['message' => 'Invalid token']);
            }
        } else {
            http_response_code(400);
            return json_encode(['message' => 'No token provided']);
        }
    }
}
