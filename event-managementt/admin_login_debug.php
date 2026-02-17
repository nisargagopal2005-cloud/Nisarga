<?php
include 'config.php';

// Redirect if already logged in
if (isset($_SESSION['admin_id'])) {
    header('Location: admin_dashboard.php');
    exit();
}

$debug_info = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    $debug_info .= "<div class='debug-info'>";
    $debug_info .= "<h5>Debug Information:</h5>";
    $debug_info .= "<p><strong>Username entered:</strong> " . htmlspecialchars($username) . "</p>";
    $debug_info .= "<p><strong>Password entered:</strong> " . htmlspecialchars($password) . "</p>";
    
    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();
    
    if ($admin) {
        $debug_info .= "<p><strong>Admin found in database:</strong> Yes</p>";
        $debug_info .= "<p><strong>Database ID:</strong> " . $admin['id'] . "</p>";
        $debug_info .= "<p><strong>Database username:</strong> " . $admin['username'] . "</p>";
        $debug_info .= "<p><strong>Database password hash:</strong> " . $admin['password'] . "</p>";
        $debug_info .= "<p><strong>Hash length:</strong> " . strlen($admin['password']) . " characters</p>";
        
        // Test password verification
        $password_verify_result = password_verify($password, $admin['password']);
        $debug_info .= "<p><strong>Password verify result:</strong> " . ($password_verify_result ? 'TRUE ✅' : 'FALSE ❌') . "</p>";
        
        // Test with known working hash
        $test_hash = password_hash('admin123', PASSWORD_DEFAULT);
        $debug_info .= "<p><strong>Test hash for 'admin123':</strong> " . $test_hash . "</p>";
        $debug_info .= "<p><strong>Test verify with new hash:</strong> " . (password_verify('admin123', $test_hash) ? 'TRUE ✅' : 'FALSE ❌') . "</p>";
        
        if ($password_verify_result) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            $_SESSION['admin_login'] = true;
            
            // Redirect to admin dashboard
            header('Location: admin_dashboard.php');
            exit();
        } else {
            $error = "Invalid admin credentials! Password verification failed.";
        }
    } else {
        $debug_info .= "<p><strong>Admin found in database:</strong> No ❌</p>";
        $debug_info .= "<p><strong>Error:</strong> Username not found in admins table</p>";
        $error = "Invalid admin credentials! Username not found.";
    }
    
    $debug_info .= "</div>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Event Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 500px;
        }
        
        .debug-info {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 10px;
            padding: 15px;
            margin-top: 20px;
            font-size: 14px;
        }
        
        .debug-info h5 {
            color: #dc3545;
            margin-bottom: 15px;
        }
        
        .error-message {
            background: linear-gradient(135deg, #ff6b6b, #ee5a52);
            color: white;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header text-center mb-4">
                <div class="admin-badge bg-primary text-white d-inline-block px-3 py-1 rounded-pill mb-3">
                    <i class="fas fa-shield-alt me-2"></i>ADMIN PANEL - DEBUG MODE
                </div>
                <h2 class="text-primary">Event Management</h2>
                <p class="text-muted">Access the administrator dashboard</p>
            </div>
            
            <?php if (isset($error)): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-triangle me-2"></i><?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" id="loginForm">
                <div class="form-group mb-3">
                    <label class="form-label fw-bold">Username</label>
                    <input type="text" name="username" class="form-control form-control-lg" placeholder="Enter admin username" required value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : 'admin'; ?>">
                </div>
                
                <div class="form-group mb-3">
                    <label class="form-label fw-bold">Password</label>
                    <input type="password" name="password" class="form-control form-control-lg" placeholder="Enter your password" required value="admin123">
                </div>
                
                <button type="submit" class="btn btn-primary btn-lg w-100">
                    <i class="fas fa-sign-in-alt me-2"></i>Login to Dashboard
                </button>
            </form>
            
            <!-- Debug Information -->
            <?php echo $debug_info; ?>
            
            <!-- Quick Fix Section -->
            <div class="mt-4 p-3 bg-light rounded">
                <h6>Quick Fix Options:</h6>
                <div class="d-grid gap-2">
                    <a href="reset_password.php" class="btn btn-warning btn-sm">
                        <i class="fas fa-key me-1"></i>Reset Password to 'admin123'
                    </a>
                    <a href="check_database.php" class="btn btn-info btn-sm">
                        <i class="fas fa-database me-1"></i>Check Database Contents
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>