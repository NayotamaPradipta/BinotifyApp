<?php 
require_once '/var/www/src/app/controllers/UserController.php';
require_once '/var/www/src/app/helpers/response.php';

$type = $_POST['type'];
$value = $_POST['value'];

if (!in_array($type, ['username', 'email'])) { 
    sendJSONResponse(['exists' => false], 400);
    exit;
}

try { 
    ['connect_db' => $connect_db] = require '/var/www/src/config/db_connect.php';
    $pdo = $connect_db(); 
    $userController = new UserController($pdo);
    $exists = $userController->checkAvailability($type, $value);
    sendJSONResponse(['exists'=> $exists]);
} catch (Exception $e) {
    sendJSONResponse(['exists'=> false, 'message' => $e->getMessage()],500);
}