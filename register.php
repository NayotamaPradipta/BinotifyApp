<?php
    ['connect_db' => $connect_db] = require('./src/db/db_connect.php');
    $db = $connect_db();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        try { 
            $stmt = $db->prepare("INSERT INTO binotify_user (email, password, username, isadmin) VALUES (:email, :password, :username, :isadmin)");
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':username', $username);
            $stmt->bindValue(':isadmin', FALSE, PDO::PARAM_BOOL);
            $stmt->execute();
            header("Location: login.php");
            exit();
        } catch (PDOException $e) { 
            echo $e->getMessage();
        }
    }
    include 'navbar.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Page</title>
    <link rel="stylesheet" href="public/css/register.css">


</head>
<body>

<div class="wrapper">
    <div class="container">
        <h2>Register</h2>
        <form id="registrationForm" action="register.php" method="post" onsubmit="return validatePassword()">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
                <span id="usernameMessage"></span>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
                <span id="emailMessage"></span>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirm-password">Confirm Password</label>
                <input type="password" id="confirm-password" name="confirm-password" required>
                <span id="message" style="color: red;"></span>
            </div>
            <div class="back-login">
                <p>Already have an account?</p>
                <a href="login.php">Login</a>
            </div>
            <button type="submit">Register</button>
        </form>
    </div>
    <script src="script.js"></script>
</body>
</html>
