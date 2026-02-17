<?php
session_start();

// Redirect to dashboard if already logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: admin_dashboard.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Hardcoded admin credentials
    $admin_username = 'admin';
    $admin_password = 'admin123';
    
    if ($username === $admin_username && $password === $admin_password) {
        // Set session
        $_SESSION['admin_username'] = $username;
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_login_time'] = time();
        
        header('Location: admin_dashboard.php');
        exit();
    } else {
        $error = 'Invalid username or password!';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body { 
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif; 
            background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            overflow: hidden;
        }

        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Background image with parallax - from index page */
        .bg-image {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 120%;
            z-index: -2;
            background: url('https://images.unsplash.com/photo-1530103862676-de8c9debad1d?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80') center/cover no-repeat;
            transform: translateZ(0);
            will-change: transform;
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

        /* Particle Background - from index page */
        #particles-js {
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: -1;
        }

        /* Floating Elements - from index page */
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
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 15s infinite linear;
            box-shadow: 0 0 15px rgba(255, 255, 255, 0.2);
        }

        @keyframes float {
            0% {
                transform: translateY(0) translateX(0) rotate(0deg);
            }
            25% {
                transform: translateY(-15px) translateX(8px) rotate(90deg);
            }
            50% {
                transform: translateY(0) translateX(15px) rotate(180deg);
            }
            75% {
                transform: translateY(15px) translateX(8px) rotate(270deg);
            }
            100% {
                transform: translateY(0) translateX(0) rotate(360deg);
            }
        }

        .container {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 40px 35px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 420px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .container:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }

        .profile-icon {
            text-align: center;
            font-size: 4rem;
            margin-bottom: 15px;
            color: #333;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            font-size: 1.8rem;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
            font-size: 0.95rem;
        }

        input {
            width: 100%;
            padding: 12px 15px;
            background: rgba(255, 255, 255, 0.7);
            border: 1.5px solid rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            font-size: 15px;
            box-sizing: border-box;
            color: #333;
            transition: all 0.3s ease;
        }

        input::placeholder {
            color: rgba(0, 0, 0, 0.4);
        }

        input:focus {
            border-color: rgba(0, 0, 0, 0.3);
            outline: none;
            background: rgba(255, 255, 255, 0.9);
            box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.05);
        }

        .btn {
            width: 100%;
            padding: 13px;
            background: #333;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 15px;
            transition: all 0.3s ease;
        }

        .btn:hover {
            background: #555;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .btn:active {
            transform: translateY(0);
        }

        .error {
            background: rgba(244, 67, 54, 0.1);
            color: #d32f2f;
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            border: 1px solid rgba(244, 67, 54, 0.2);
            font-weight: 500;
        }

        .credentials-hint {
            background: rgba(0, 0, 0, 0.05);
            padding: 15px;
            border-radius: 8px;
            margin-top: 25px;
            text-align: center;
        }

        .credentials-hint h3 {
            color: #333;
            margin-bottom: 10px;
            font-size: 0.95rem;
        }

        .credentials-hint p {
            color: #555;
            font-size: 0.85rem;
            line-height: 1.5;
        }

        .demo-text {
            color: #e73c7e;
            font-weight: bold;
        }

        /* Responsive design */
        @media (max-width: 480px) {
            .container {
                margin: 20px;
                padding: 30px 25px;
            }
            
            h1 {
                font-size: 1.6rem;
            }
            
            .profile-icon {
                font-size: 3.5rem;
            }
            
            input {
                padding: 11px 14px;
            }
            
            .btn {
                padding: 12px;
                font-size: 15px;
            }
        }
    </style>
</head>
<body>
    <!-- Background elements from index page -->
    <div class="bg-image"></div>
    <div class="bg-overlay"></div>
    <div id="particles-js"></div>
    <div class="floating-elements" id="floatingElements"></div>

    <div class="container">
        <div class="profile-icon">
            👨‍💼
        </div>
        
        <h1>Admin Login</h1>
        
        <?php if ($error): ?>
            <div class="error">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label>Username:</label>
                <input type="text" name="username" value="<?php echo htmlspecialchars($_POST['username'] ?? 'admin'); ?>" required placeholder="Enter admin username">
            </div>
            
            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="password" placeholder="Enter admin password" required>
            </div>
            
            <button type="submit" class="btn">
                Login to Admin Dashboard
            </button>
        </form>

        <div class="credentials-hint">
            <h3>Demo Credentials</h3>
            <p>
                <span class="demo-text">Username:</span> admin<br>
                <span class="demo-text">Password:</span> admin123
            </p>
        </div>
    </div>

    <!-- Particles.js Library -->
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    
    <script>
        // Add subtle interaction effects
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

            // Initialize particles.js
            particlesJS('particles-js', {
                particles: {
                    number: { value: 80, density: { enable: true, value_area: 800 } },
                    color: { value: "#ffffff" },
                    shape: { type: "circle" },
                    opacity: { value: 0.5, random: true },
                    size: { value: 3, random: true },
                    line_linked: {
                        enable: true,
                        distance: 150,
                        color: "#ffffff",
                        opacity: 0.4,
                        width: 1
                    },
                    move: {
                        enable: true,
                        speed: 2,
                        direction: "none",
                        random: true,
                        straight: false,
                        out_mode: "out",
                        bounce: false
                    }
                },
                interactivity: {
                    detect_on: "canvas",
                    events: {
                        onhover: { enable: true, mode: "repulse" },
                        onclick: { enable: true, mode: "push" },
                        resize: true
                    }
                },
                retina_detect: true
            });

            // Create floating elements
            function createFloatingElements() {
                const container = document.getElementById('floatingElements');
                const colors = ['#ff6b6b', '#6a11cb', '#ffd93d', '#2575fc'];
                
                for (let i = 0; i < 15; i++) {
                    const element = document.createElement('div');
                    element.classList.add('floating-element');
                    
                    // Random properties
                    const size = Math.random() * 25 + 8;
                    const color = colors[Math.floor(Math.random() * colors.length)];
                    const left = Math.random() * 100;
                    const top = Math.random() * 100;
                    const delay = Math.random() * 15;
                    const duration = Math.random() * 10 + 15;
                    
                    element.style.width = `${size}px`;
                    element.style.height = `${size}px`;
                    element.style.background = color;
                    element.style.left = `${left}%`;
                    element.style.top = `${top}%`;
                    element.style.animationDelay = `${delay}s`;
                    element.style.animationDuration = `${duration}s`;
                    
                    container.appendChild(element);
                }
            }

            createFloatingElements();
        });
    </script>
</body>
</html>