<?php

// Handle auth and user routes
if ($path === 'auth/register' && $requestMethod === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    echo $authController->register($data);
    exit();
} elseif ($path === 'auth/login' && $requestMethod === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    echo $authController->login($data);
    exit();
} elseif ($path === 'auth/user' && $requestMethod === 'GET') {
    echo $authController->getUserDetails($jwt);
    exit();
}
