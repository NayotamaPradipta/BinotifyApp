<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Page</title>
    <link rel="stylesheet" href="public/css/register.css">
</head>
<body>
<?php
    ['connect_db' => $connect_db] = require('./src/db/db_connect.php');
    $db = $connect_db();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        // $email = $_POST['email']; Ini mau pake email atau nggak? Kalo sesuai spek gk perlu 
                                  // Kalo mau pake email sabi databasenya ditambahin kolom email 
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        try { 
            $stmt = $db->prepare("INSERT INTO binotify_user (password, username, isadmin) VALUES (:password, :username, :isadmin)");
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':isadmin', FALSE);
            $stmt->execute();
            echo "Registration successful!";
        } catch (PDOException $e) { 
            echo $e->getMessage();
        }
    }
?>

<?php include 'navbar.php'; ?>
<div class="wrapper">
    <div class="container">
        <h2>Register</h2>
        <form id="registrationForm" action="/register" method="post">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
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
                <a href="Login.php">Login</a>
            </div>
            <button type="submit">Register</button>
        </form>
    </div>
    <script src="script.js"></script>
</body>
</html>
