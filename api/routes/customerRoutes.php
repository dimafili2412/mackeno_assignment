<?php

// Handle customer routes
if ($path === 'customers') {
    // Authenticate JWT token before accessing customer routes - only registered user can access all following routes
    $headers = getallheaders();
    if (!isset($headers['Authorization'])) {
        echo json_encode(['message' => 'Unauthorized']);
        exit();
    }

    $jwt = str_replace('Bearer ', '', $headers['Authorization']);
    require_once __DIR__ . '/../controllers/AuthController.php';
    $authController = new AuthController($db, $validator);
    $userData = $authController->getUser()->validateToken($jwt);
    
    if (!$userData) {
        echo json_encode(['message' => 'Invalid token']);
        exit();
    }

    // Customer CRUD routes
    if ($path === 'customers' && $requestMethod === 'GET') {
        $perPage = isset($_GET['perPage']) ? $_GET['perPage'] : 5;
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        echo $customerController->getCustomers($page, $perPage);
        exit();
    } elseif ($path === 'customers' && $requestMethod === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        echo $customerController->createCustomer($data);
        exit();
    } elseif ($path === 'customers' && $requestMethod === 'PATCH') {
        $data = json_decode(file_get_contents('php://input'), true);
        echo $customerController->updateCustomer($data);
        exit();
    } elseif ($path === 'customers' && $requestMethod === 'DELETE') {
        $customerId = isset($_GET['id']) ? $_GET['id'] : null;
        echo $customerController->deleteCustomer($customerId);
        exit();
    }
}