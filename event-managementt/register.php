<?php
session_start();
require_once __DIR__.'/inc/db.php';
$err = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validate inputs
    if (empty($name) || empty($email) || empty($password)) {
        $err = 'All fields are required.';
    } elseif ($password !== $confirm_password) {
        $err = 'Passwords do not match.';
    } elseif (strlen($password) < 6) {
        $err = 'Password must be at least 6 characters long.';
    } else {
        // Check if email already exists
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email=?');
        $stmt->execute([$email]);
        
        if ($stmt->fetch()) {
            $err = 'Email already registered.';
        } else {
            // Insert new user
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO users (name, email, password) VALUES (?, ?, ?)');
            
            if ($stmt->execute([$name, $email, $hashed_password])) {
                $_SESSION['success'] = 'Registration successful! Please login.';
                header('Location: user_login.php');
                exit;
            } else {
                $err = 'Registration failed. Please try again.';
            }
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Register - EventSphere</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

:root {
    --primary: #ff6b6b;
    --secondary: #6a11cb;
    --accent: #ffd93d;
    --dark: #111;
    --light: #f8f9fa;
    --transition: all 0.3s ease;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    min-height: 100vh;
    overflow-x: hidden;
    background: var(--light);
}

/* Background image with blur */
.bg-image {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 120%;
    z-index: -2;
    background: url('https://images.unsplash.com/photo-1530103862676-de8c9debad1d?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80') center/cover no-repeat;
    filter: blur(12px) brightness(0.8);
    transform: scale(1.1);
}

.bg-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(106, 17, 203, 0.5), rgba(255, 107, 107, 0.5));
    z-index: -1;
    backdrop-filter: blur(3px);
}

/* Main container */
.main-container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    padding: 2rem;
}

/* Translucent Register Container */
.register-container {
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    padding: 3rem 2.5rem;
    border-radius: 20px;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    width: 100%;
    max-width: 450px;
    animation: fadeIn 0.8s ease;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Header Styles */
.register-header {
    text-align: center;
    margin-bottom: 2.5rem;
}

.profile-emoji {
    font-size: 4rem;
    margin-bottom: 1rem;
    display: block;
}

h1 {
    color: #333;
    font-size: 2.2rem;
    margin-bottom: 0.5rem;
    font-weight: 700;
}

.subtitle {
    color: #666;
    font-size: 1rem;
    font-weight: 400;
}

/* Form Styles */
.form-group {
    margin-bottom: 1.5rem;
    position: relative;
}

label {
    display: block;
    margin-bottom: 0.5rem;
    color: #333;
    font-weight: 600;
    font-size: 0.9rem;
}

.input-with-icon {
    position: relative;
}

.input-with-icon i {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #666;
    font-size: 1.1rem;
    z-index: 2;
}

input {
    width: 100%;
    padding: 1rem 1rem 1rem 3rem;
    background: rgba(255, 255, 255, 0.8);
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    color: #333;
    font-size: 1rem;
    transition: var(--transition);
}

input::placeholder {
    color: #999;
}

input:focus {
    outline: none;
    border-color: var(--primary);
    background: white;
    box-shadow: 0 0 0 3px rgba(255, 107, 107, 0.1);
}

/* Clean Button */
.btn-clean {
    width: 100%;
    padding: 1.2rem;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: white;
    border: none;
    border-radius: 10px;
    font-size: 1.1rem;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    margin-top: 1.5rem;
}

.btn-clean:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(255, 107, 107, 0.3);
}

.btn-clean:active {
    transform: translateY(0);
}

/* Message Styles */
.error {
    background: rgba(244, 67, 54, 0.1);
    color: #d32f2f;
    padding: 1rem;
    border-radius: 8px;
    text-align: center;
    border: 1px solid rgba(244, 67, 54, 0.2);
    margin-bottom: 1.5rem;
    font-weight: 500;
}

.success {
    background: rgba(76, 175, 80, 0.1);
    color: #2e7d32;
    padding: 1rem;
    border-radius: 8px;
    text-align: center;
    border: 1px solid rgba(76, 175, 80, 0.2);
    margin-bottom: 1.5rem;
    font-weight: 500;
}

/* Login Link */
.login-link {
    text-align: center;
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid #e0e0e0;
}

.login-link p {
    color: #666;
    margin-bottom: 0.8rem;
    font-size: 0.9rem;
}

.login-link a {
    color: var(--primary);
    text-decoration: none;
    font-weight: 600;
    transition: var(--transition);
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    border-radius: 6px;
    background: rgba(255, 107, 107, 0.1);
}

.login-link a:hover {
    background: rgba(255, 107, 107, 0.2);
    transform: translateY(-1px);
}

/* Password Toggle */
.password-toggle {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #666;
    cursor: pointer;
    padding: 5px;
    width: auto;
    font-size: 1rem;
    transition: var(--transition);
    border-radius: 50%;
    z-index: 2;
}

.password-toggle:hover {
    color: #333;
    background: rgba(0, 0, 0, 0.05);
}

/* Loading Animation */
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.loading {
    animation: spin 1s linear infinite;
}

/* Responsive Design */
@media (max-width: 768px) {
    .register-container {
        padding: 2.5rem 2rem;
        margin: 1rem;
    }
    
    h1 {
        font-size: 2rem;
    }
    
    .profile-emoji {
        font-size: 3.5rem;
    }
    
    input {
        padding: 0.9rem 0.9rem 0.9rem 2.8rem;
    }
    
    .btn-clean {
        padding: 1.1rem;
        font-size: 1rem;
    }
}

@media (max-width: 480px) {
    .register-container {
        padding: 2rem 1.5rem;
    }
    
    h1 {
        font-size: 1.8rem;
    }
    
    .profile-emoji {
        font-size: 3rem;
    }
    
    .btn-clean {
        padding: 1rem;
    }
}
</style>
</head>
<body>
    <!-- Background Elements -->
    <div class="bg-image"></div>
    <div class="bg-overlay"></div>

    <div class="main-container">
        <div class="register-container">
            <div class="register-header">
                <span class="profile-emoji">👤</span>
                <h1>User Registration</h1>
                <p class="subtitle">Join EventSphere and start managing your events today!</p>
            </div>
            
            <?php if($err): ?>
                <div class='error'>
                    <i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($err); ?>
                </div>
            <?php endif; ?>

            <form method="post" id="registerForm">
                <div class="form-group">
                    <label>Full Name</label>
                    <div class="input-with-icon">
                        <i class="fas fa-user"></i>
                        <input type="text" name="name" placeholder="Enter your full name" required 
                               value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Email Address</label>
                    <div class="input-with-icon">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="email" placeholder="Enter your email address" required 
                               value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Password</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" id="password" placeholder="Enter your password (min. 6 characters)" required>
                        <button type="button" class="password-toggle" onclick="togglePassword('password')">
                            <i class="fas fa-eye" id="passwordIcon"></i>
                        </button>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Confirm Password</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm your password" required>
                        <button type="button" class="password-toggle" onclick="togglePassword('confirm_password')">
                            <i class="fas fa-eye" id="confirmPasswordIcon"></i>
                        </button>
                    </div>
                </div>
                
                <button type="submit" class="btn-clean" id="registerButton">
                    <i class="fas fa-user-plus"></i> Register
                </button>
            </form>
            
            <div class="login-link">
                <p>Already have an account?</p>
                <a href="user_login.php">
                    <i class="fas fa-sign-in-alt"></i> Login here
                </a>
            </div>
        </div>
    </div>

    <script>
        // Password visibility toggle
        function togglePassword(fieldId) {
            const passwordInput = document.getElementById(fieldId);
            const passwordIcon = document.getElementById(fieldId === 'password' ? 'passwordIcon' : 'confirmPasswordIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.className = 'fas fa-eye-slash';
            } else {
                passwordInput.type = 'password';
                passwordIcon.className = 'fas fa-eye';
            }
        }

        // Form submission animation
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const button = document.getElementById('registerButton');
            button.innerHTML = '<i class="fas fa-spinner fa-spin loading"></i> Creating Account...';
            
            // Optional: Add a small delay to show the loading animation
            setTimeout(() => {
                button.disabled = true;
            }, 100);
        });

        // Add focus effects to form inputs
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('input');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.style.transform = 'scale(1.02)';
                });
                
                input.addEventListener('blur', function() {
                    this.style.transform = 'scale(1)';
                });
            });
        });

        // Real-time password validation
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('confirm_password');

        function validatePasswords() {
            const password = passwordInput.value;
            const confirmPassword = confirmPasswordInput.value;
            
            if (confirmPassword && password !== confirmPassword) {
                confirmPasswordInput.style.borderColor = '#f44336';
                confirmPasswordInput.style.boxShadow = '0 0 0 3px rgba(244, 67, 54, 0.1)';
            } else {
                confirmPasswordInput.style.borderColor = '#e0e0e0';
                confirmPasswordInput.style.boxShadow = 'none';
            }
            
            if (password && password.length < 6) {
                passwordInput.style.borderColor = '#ff9800';
                passwordInput.style.boxShadow = '0 0 0 3px rgba(255, 152, 0, 0.1)';
            } else if (password) {
                passwordInput.style.borderColor = '#e0e0e0';
                passwordInput.style.boxShadow = 'none';
            }
        }

        passwordInput.addEventListener('input', validatePasswords);
        confirmPasswordInput.addEventListener('input', validatePasswords);
    </script>
</body>
</html>