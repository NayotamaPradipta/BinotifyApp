<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="public/css/login.css">
</head>
<body>
<?php
// Koneksi ke database PostgreSQL
$conn = pg_connect("host=localhost dbname=Binotify user=postgres password=farhan123");

if (!$conn) {
    die("Connection failed: " . pg_last_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Mengecek apakah username ada di database
    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = pg_query($conn, $sql);

    if (pg_num_rows($result) > 0) {
        $row = pg_fetch_assoc($result);
        if (password_verify($password, $row['password'])) {
            echo "Login successful!";
            // Set session atau redirect ke halaman lain
        } else {
            echo "Invalid password!";
        }
    } else {
        echo "No user found with that username!";
    }
}

pg_close($conn);
?>

<?php include 'navbar.php'; ?>
<div class="wrapper">
    <div class="login-container">
        <h2>Login</h2>
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
                <!-- 
                TODO: 
                1. Create register.php and change href
                2. Create a link from login to home page
                -->
                <a href="register.php">Register</a> 
            </div>
        </form>
</div>
    </div>
    
</body>
</html>
