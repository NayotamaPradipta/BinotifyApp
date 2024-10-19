<?php
require_once '/var/www/src/app/controllers/UserController.php';
require_once '/var/www/src/app/helpers/response.php';

header('Content-Type: application/json'); 

try {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['username'], $data['email'], $data['password'])) {
        sendJSONResponse(['success' => false, 'message' => 'Missing required fields'], 400); 
        exit;
    }

    ['connect_db' => $connect_db] = require '/var/www/src/config/db_connect.php';
    $pdo = $connect_db();

    $userController = new UserController($pdo);
    $result = $userController->registerUser($data['username'], $data['email'], $data['password']);

    if ($result) {
        sendJSONResponse(['success' => true, 'message' => 'Registration successful']);
    } else {
        sendJSONResponse(['success' => false, 'message' => 'Registration failed']);
    }
} catch (Exception $e) {
    sendJSONResponse(['success' => false, 'message' => 'Server error: ' . $e->getMessage()], 500);
}