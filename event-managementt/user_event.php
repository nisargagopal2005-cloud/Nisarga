<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['username'] === 'user' && $_POST['password'] === 'password') {
    $_SESSION['user_id'] = 1;
    $_SESSION['username'] = 'Test User';
    header("Location: index_event.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Event Login</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; justify-content: center; align-items: center; }
        .login-container { background: white; padding: 40px; border-radius: 20px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); width: 400px; text-align: center; }
        .login-container h2 { margin-bottom: 30px; color: #333; }
        input { width: 100%; padding: 12px; margin: 10px 0; border: 2px solid #ddd; border-radius: 10px; font-size: 1em; }
        button { width: 100%; padding: 12px; background: #4CAF50; color: white; border: none; border-radius: 10px; font-size: 1.1em; cursor: pointer; margin-top: 10px; }
        .demo-credentials { background: #f8f9fa; padding: 15px; border-radius: 10px; margin-top: 20px; font-size: 0.9em; }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>🔐 Event Login</h2>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <div class="demo-credentials">
            <strong>Demo Credentials:</strong><br>
            Username: <code>user</code><br>
            Password: <code>password</code>
        </div>
    </div>
</body>
</html>