<?php 
    require_once '/var/www/src/app/controllers/UserController.php';
    require_once '/var/www/src/app/helpers/response.php';

    ['connect_db' => $connect_db] = require '/var/www/src/config/db_connect.php';
    $pdo = $connect_db();
    $userController = new UserController($pdo);

    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

    $data = $userController->getPaginatedUsers($page);

    sendJSONResponse($data);
