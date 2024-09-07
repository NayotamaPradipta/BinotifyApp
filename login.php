<?php
    ob_start();
    ['connect_db' => $connect_db] = require('./src/db/db_connect.php');
    $db = $connect_db();
    $error_message = '';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $password = $_POST['password'];
        if ($username === 'admin') { 
            if ($password === 'admin') { 
                session_start();
                $_SESSION['username'] = $username;
                header("Location: index.php");
                exit();
            } else { 
                $error_message = "Invalid password for admin!";
            }
        } 
        else { 
            try {   
                $stmt = $db->prepare("SELECT * FROM binotify_user WHERE username= :username");
                $stmt->bindParam(':username', $username);
                $stmt->execute();
        
                $row = $stmt->fetch(PDO::FETCH_ASSOC);


                if ($row) {
                    if (password_verify($password, $row['password'])) {
                        session_start();
                        $_SESSION['username'] = $username;
                        header("Location: index.php");
                        exit();
                    } else {
                        $error_message = "Invalid password!";
                    }
                } else {
                    $error_message = "No user found with that username!";
                }
            } catch (PDOException $e) { 
                $error_message = $e->getMessage();
            }
        }
    }
    include 'navbar.php';
    ob_end_flush();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="public/css/login.css">
</head>
<body>

<div class="wrapper">
    <div class="login-container">
        <h2>Login</h2>
        <?php if (!empty($error_message)): ?>
            <p id="error-message"><?php echo htmlspecialchars($error_message); ?></p>
        <?php endif; ?>
        <form action="login.php" method="post">
            <div class="input-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required >
            </div>
            <div class="input-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="forgot">
                <section>
                    <input type="checkbox" id="check">
                    <label for="check">Remember me</label></label>
                </section>
                <section>
                    <a href="#">Forgot password?</a>
                </section>
            </div>
            <div>
                <button type="submit">Login</button>
            </div>
            <div class="input-register">
                <p>Don't have an account?</p>
                <a href="register.php">Register</a> 
            </div>
        </form>
</div>
    </div>
    
</body>
</html>
