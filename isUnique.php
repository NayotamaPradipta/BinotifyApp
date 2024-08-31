<?php 
    ['connect_db' => $connect_db] = require('./src/db/db_connect.php');
    $db = $connect_db();

    if (isset($_POST['username'])) {
        $username = $_POST['username'];
        $stmt = $db->prepare("SELECT COUNT(*) FROM binotify_user WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $count = $stmt->fetchColumn();
        echo json_encode(['exists' => $count > 0]);
        exit();
    }

    if (isset($_POST['email'])) {
        $email = $_POST['email'];
        $stmt = $db->prepare("SELECT COUNT(*) FROM binotify_user WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $count = $stmt->fetchColumn();
        echo json_encode(['exists' => $count > 0]);
        exit();
    }

?>  