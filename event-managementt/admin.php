<?php
include 'config.php';

// Redirect if already logged in
if (isset($_SESSION['admin_id'])) {
    header('Location: admin_dashboard.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();
    
    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_username'] = $admin['username'];
        $_SESSION['admin_login'] = true;
        
        // Redirect to admin dashboard
        header('Location: admin_dashboard.php');
        exit();
    } else {
        $error = "Invalid admin credentials!";
    }
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
            --accent-color: #f093fb;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }
        
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            perspective: 1000px;
        }
        
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            transform-style: preserve-3d;
            transition: transform 0.6s ease, box-shadow 0.6s ease;
            width: 100%;
            max-width: 420px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .login-card:hover {
            transform: translateY(-10px) rotateX(5deg);
            box-shadow: 0 35px 70px rgba(0, 0, 0, 0.25);
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 30px;
            transform: translateZ(50px);
        }
        
        .login-header h2 {
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .login-header p {
            color: #666;
            font-size: 14px;
        }
        
        .admin-badge {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 15px;
        }
        
        .form-group {
            margin-bottom: 20px;
            transform: translateZ(30px);
        }
        
        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }
        
        .form-control {
            border: 2px solid #e1e5e9;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.8);
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            transform: translateY(-2px);
        }
        
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #666;
            cursor: pointer;
            transition: color 0.3s ease;
        }
        
        .password-toggle:hover {
            color: var(--primary-color);
        }
        
        .btn-login {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: 12px;
            padding: 12px 30px;
            font-weight: 600;
            color: white;
            width: 100%;
            transition: all 0.3s ease;
            transform: translateZ(40px);
        }
        
        .btn-login:hover {
            transform: translateY(-3px) translateZ(50px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }
        
        .credentials-card {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            border-radius: 15px;
            padding: 20px;
            margin-top: 25px;
            transform: translateZ(45px);
            box-shadow: 0 15px 35px rgba(240, 147, 251, 0.3);
        }
        
        .credentials-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 15px;
        }
        
        .credentials-header h6 {
            margin: 0;
            font-weight: 700;
        }
        
        .credential-item {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
            font-size: 13px;
        }
        
        .credential-item i {
            width: 20px;
            margin-right: 10px;
        }
        
        .floating-elements {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }
        
        .floating-element {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }
        
        .pulse-animation {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        .error-message {
            background: linear-gradient(135deg, #ff6b6b, #ee5a52);
            color: white;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 600;
            transform: translateZ(35px);
            animation: shake 0.5s ease-in-out;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
    </style>
</head>
<body>
    <!-- Floating Background Elements -->
    <div class="floating-elements">
        <div class="floating-element" style="width: 100px; height: 100px; top: 10%; left: 10%; animation-delay: 0s;"></div>
        <div class="floating-element" style="width: 150px; height: 150px; top: 60%; right: 10%; animation-delay: 2s;"></div>
        <div class="floating-element" style="width: 80px; height: 80px; bottom: 20%; left: 20%; animation-delay: 4s;"></div>
    </div>
    
    <div class="login-container">
        <div class="login-card pulse-animation">
            <div class="login-header">
                <div class="admin-badge">
                    <i class="fas fa-shield-alt me-2"></i>ADMIN PANEL
                </div>
                <h2>Event Management</h2>
                <p>Access the administrator dashboard</p>
            </div>
            
            <?php if (isset($error)): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-triangle me-2"></i><?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" id="loginForm">
                <div class="form-group">
                    <label class="form-label">Username</label>
                    <div class="position-relative">
                        <input type="text" name="username" class="form-control" placeholder="Enter admin username" required value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                        <i class="fas fa-user position-absolute" style="right: 15px; top: 50%; transform: translateY(-50%); color: #666;"></i>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div class="position-relative">
                        <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
                        <button type="button" class="password-toggle" id="togglePassword">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-login mb-3">
                    <i class="fas fa-sign-in-alt me-2"></i>Login to Dashboard
                </button>
            </form>
            
            <!-- Demo Credentials Card -->
            <div class="credentials-card">
                <div class="credentials-header">
                    <h6><i class="fas fa-key me-2"></i>Demo Credentials</h6>
                    <i class="fas fa-info-circle"></i>
                </div>
                <div class="credential-item">
                    <i class="fas fa-user"></i>
                    <span>Username: <strong>admin</strong></span>
                </div>
                <div class="credential-item">
                    <i class="fas fa-lock"></i>
                    <span>Password: <strong id="demoPassword">admin123</strong></span>
                    <button type="button" class="btn btn-sm btn-outline-light ms-2" onclick="togglePasswordVisibility()" style="padding: 2px 8px; font-size: 11px;">
                        <i class="fas fa-eye"></i> Show
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Password visibility toggle for login form
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
        
        // Demo password visibility toggle
        function togglePasswordVisibility() {
            const demoPassword = document.getElementById('demoPassword');
            const button = event.currentTarget;
            const icon = button.querySelector('i');
            
            if (demoPassword.textContent === '••••••••') {
                demoPassword.textContent = 'admin123';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
                button.innerHTML = '<i class="fas fa-eye-slash"></i> Hide';
            } else {
                demoPassword.textContent = '••••••••';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
                button.innerHTML = '<i class="fas fa-eye"></i> Show';
            }
        }
        
        // 3D tilt effect
        document.addEventListener('mousemove', function(e) {
            const card = document.querySelector('.login-card');
            const { left, top, width, height } = card.getBoundingClientRect();
            const x = (e.clientX - left) / width - 0.5;
            const y = (e.clientY - top) / height - 0.5;
            
            card.style.transform = `
                perspective(1000px)
                rotateX(${y * 10}deg)
                rotateY(${x * 10}deg)
                translateY(-10px)
            `;
        });
        
        document.querySelector('.login-card').addEventListener('mouseleave', function() {
            this.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) translateY(-10px)';
        });
        
        // Form submission animation
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const btn = this.querySelector('.btn-login');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Authenticating...';
            btn.disabled = true;
        });
    </script>
</body>
</html>