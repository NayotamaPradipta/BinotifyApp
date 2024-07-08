<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
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
            <label for"check">Remember me</label></label>
        </section>
        <section>
            <a href="#">Forgot password?</a>
        </section>
    </div>
    <div class="input-submit">
        <p>Don't have account?<p>
    </div>
            <button type="submit">Login</button>
        </form>
    </div>
    
</body>
</html>
