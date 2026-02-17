<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin_login.php');
    exit();
}

// Load users data
$users_file = 'data/users.json';
if (file_exists($users_file)) {
    $users = json_decode(file_get_contents($users_file), true);
} else {
    $users = [];
}

// Get user ID from URL
$user_id = $_GET['id'] ?? '';
$user = null;

foreach ($users as $u) {
    if ($u['id'] === $user_id) {
        $user = $u;
        break;
    }
}

// Redirect if user not found
if (!$user) {
    header('Location: user_management.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Details - <?php echo htmlspecialchars($user['name']); ?></title>
    <style>
        /* Same styles as user_management.php for consistency */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body { 
            font-family: 'Arial', sans-serif; 
            background: #f8f9fa;
            display: flex;
            min-height: 100vh;
        }
        
        .sidebar {
            width: 280px;
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            padding: 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }
        
        /* ... (same sidebar styles as user_management.php) ... */
        
        .main-content {
            flex: 1;
            margin-left: 280px;
            padding: 30px;
        }
        
        .header {
            background: white;
            padding: 25px 30px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            display: flex;
            justify-content: between;
            align-items: center;
        }
        
        .back-btn {
            background: #6c757d;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
        
        .back-btn:hover {
            background: #5a6268;
        }
        
        .user-details-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            padding: 30px;
        }
        
        .user-header {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #ecf0f1;
        }
        
        .user-avatar-large {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: #3498db;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 40px;
            font-weight: bold;
        }
        
        .user-info h1 {
            color: #2c3e50;
            margin-bottom: 10px;
        }
        
        .user-info p {
            color: #7f8c8d;
            margin-bottom: 5px;
        }
        
        .details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        
        .detail-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
        }
        
        .detail-section h3 {
            color: #2c3e50;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #dee2e6;
        }
        
        .detail-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #ecf0f1;
        }
        
        .detail-item:last-child {
            border-bottom: none;
        }
        
        .detail-label {
            font-weight: bold;
            color: #2c3e50;
        }
        
        .detail-value {
            color: #7f8c8d;
        }
        
        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        
        .status-active {
            background: #d4edda;
            color: #155724;
        }
        
        .status-inactive {
            background: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <!-- Sidebar (same as user_management.php) -->
    <div class="sidebar">
        <div class="profile-section">
            <div class="profile-pic">👨‍💼</div>
            <div class="profile-name"><?php echo htmlspecialchars($_SESSION['admin_username']); ?></div>
            <div class="profile-email">Admin Panel</div>
            <div class="profile-role">Administrator</div>
        </div>
        
        <div class="menu-section">
            <a href="admin_dashboard.php" class="menu-item">
                <i>📊</i> Dashboard
            </a>
            <a href="user_management.php" class="menu-item active">
                <i>👥</i> User Management
            </a>
            <a href="#" class="menu-item">
                <i>⚙️</i> System Settings
            </a>
            <a href="#" class="menu-item">
                <i>📈</i> Reports
            </a>
            
            <div class="logout-item">
                <a href="admin_logout.php" class="menu-item">
                    <i>🚪</i> Logout
                </a>
            </div>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <div class="header">
            <h1>👤 User Details</h1>
            <a href="user_management.php" class="back-btn">← Back to Users</a>
        </div>
        
        <div class="user-details-card">
            <div class="user-header">
                <div class="user-avatar-large">
                    <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
                </div>
                <div class="user-info">
                    <h1><?php echo htmlspecialchars($user['name']); ?></h1>
                    <p>📧 <?php echo htmlspecialchars($user['email']); ?></p>
                    <p>🆔 User ID: <?php echo $user['id']; ?></p>
                    <span class="status-badge <?php echo $user['status'] === 'active' ? 'status-active' : 'status-inactive'; ?>">
                        <?php echo ucfirst($user['status']); ?>
                    </span>
                </div>
            </div>
            
            <div class="details-grid">
                <div class="detail-section">
                    <h3>📋 Personal Information</h3>
                    <div class="detail-item">
                        <span class="detail-label">Full Name:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($user['name']); ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Email:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($user['email']); ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Phone:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($user['phone'] ?? 'Not provided'); ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Role:</span>
                        <span class="detail-value"><?php echo ucfirst($user['role']); ?></span>
                    </div>
                </div>
                
                <div class="detail-section">
                    <h3>📊 Account Information</h3>
                    <div class="detail-item">
                        <span class="detail-label">Status:</span>
                        <span class="detail-value">
                            <span class="status-badge <?php echo $user['status'] === 'active' ? 'status-active' : 'status-inactive'; ?>">
                                <?php echo ucfirst($user['status']); ?>
                            </span>
                        </span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Registration Date:</span>
                        <span class="detail-value"><?php echo $user['registration_date']; ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Last Login:</span>
                        <span class="detail-value"><?php echo $user['last_login'] ?? 'Never logged in'; ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Login Count:</span>
                        <span class="detail-value"><?php echo $user['login_count']; ?> times</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>