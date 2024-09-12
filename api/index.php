<?php
require 'vendor/autoload.php';

// Load env file
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Get origin from env
$allowed_origin = $_ENV['ALLOW_ORIGIN'];

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header("Access-Control-Allow-Origin: $allowed_origin");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PATCH, DELETE");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    http_response_code(200);
    exit();
}

// For actual requests
header("Access-Control-Allow-Origin: $allowed_origin");

// Load database
require_once __DIR__ . '/config/Database.php';

// Initialize database connection
$database = new Database();
$db = $database->getConnection();

// Get the http method and normalize request path
$requestMethod = $_SERVER['REQUEST_METHOD'];
$fullPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$baseDir = dirname($_SERVER['SCRIPT_NAME']);
$path = str_replace($baseDir, '', $fullPath);
$path = trim($path, '/');

// Extract token from Authorization header
$headers = apache_request_headers();
$authHeader = isset($headers['Authorization']) ? $headers['Authorization'] : null;
if ($authHeader) {
    $jwt = str_replace('Bearer ', '', $authHeader);
} else {
    $jwt = null;
}

// Load services
require_once __DIR__ . '/services/Validator.php';

// Initialize services
$validator = new ValidatorService();

// Load controllers
require_once __DIR__ . '/controllers/CustomerController.php';
require_once __DIR__ . '/controllers/AuthController.php';

// Initialize controllers
$customerController = new CustomerController($db, $validator);
$authController = new AuthController($db, $validator);

// Include the router
require_once __DIR__ . '/routes/customerRoutes.php';
require_once __DIR__ . '/routes/userRoutes.php';

// No route matched
http_response_code(404);
echo json_encode([
    "message" => "Not Found"
]);
exit();
