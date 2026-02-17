<?php
session_start();

// Redirect to dashboard if already logged in
if (isset($_SESSION['customer_logged_in']) && $_SESSION['customer_logged_in'] === true) {
    header('Location: customer_dashboard.php');
    exit();
}

// Load users data
$users_file = 'data/users.json';
if (file_exists($users_file)) {
    $users_data = file_get_contents($users_file);
    $users = !empty($users_data) ? json_decode($users_data, true) : [];
} else {
    $users = [];
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Find user by email
    $user = null;
    foreach ($users as $u) {
        if ($u['email'] === $email && $u['role'] === 'customer') {
            $user = $u;
            break;
        }
    }
    
    if ($user && password_verify($password, $user['password'])) {
        if ($user['status'] === 'active') {
            // Update last login and login count
            foreach ($users as &$u) {
                if ($u['id'] === $user['id']) {
                    $u['last_login'] = date('Y-m-d H:i:s');
                    $u['login_count'] = ($u['login_count'] ?? 0) + 1;
                    break;
                }
            }
            file_put_contents($users_file, json_encode($users, JSON_PRETTY_PRINT));
            
            // Set session
            $_SESSION['customer_id'] = $user['id'];
            $_SESSION['customer_name'] = $user['name'];
            $_SESSION['customer_email'] = $user['email'];
            $_SESSION['customer_logged_in'] = true;
            $_SESSION['customer_login_time'] = time();
            
            header('Location: customer_dashboard.php');
            exit();
        } else {
            $error = 'Your account has been deactivated. Please contact administrator.';
        }
    } else {
        $error = 'Invalid email or password!';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customer Login</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }
        input {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
        }
        input:focus {
            border-color: #667eea;
            outline: none;
        }
        .btn {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }
        .btn:hover {
            opacity: 0.9;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        .register-link {
            text-align: center;
            margin-top: 20px;
        }
        .register-link a {
            color: #667eea;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔐 Customer Login</h1>
        
        <?php if ($error): ?>
            <div class="error">❌ <?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label>📧 Email:</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
            </div>
            
            <div class="form-group">
                <label>🔒 Password:</label>
                <input type="password" name="password" required>
            </div>
            
            <button type="submit" class="btn">🚀 Login to Dashboard</button>
        </form>

        <div class="register-link">
            <p>Don't have an account? <a href="customer_register.php">Register here</a></p>
        </div>
    </div>
</body>
</html>