<?php
session_start();

// Check if customer is logged in
if (!isset($_SESSION['customer_logged_in']) || $_SESSION['customer_logged_in'] !== true) {
    header('Location: customer_login.php');
    exit();
}

$customer_id = $_SESSION['customer_id'];
$customer_name = $_SESSION['customer_name'];
$customer_email = $_SESSION['customer_email'];
$login_time = date('Y-m-d H:i:s', $_SESSION['customer_login_time']);

// Load user data to get complete profile
$users_file = 'data/users.json';
if (file_exists($users_file)) {
    $users_data = file_get_contents($users_file);
    $users = !empty($users_data) ? json_decode($users_data, true) : [];
} else {
    $users = [];
}

$current_customer = null;
foreach ($users as $user) {
    if ($user['id'] === $customer_id) {
        $current_customer = $user;
        break;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customer Dashboard</title>
    <style>
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
        
        /* Sidebar Styles */
        .sidebar {
            width: 280px;
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
            padding: 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        
        .profile-section {
            padding: 30px 20px;
            background: rgba(0,0,0,0.2);
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .profile-pic {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            color: #3498db;
            margin: 0 auto 15px;
            border: 3px solid white;
        }
        
        .profile-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .profile-email {
            color: #ecf0f1;
            font-size: 14px;
            margin-bottom: 8px;
        }
        
        .profile-role {
            background: #e74c3c;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            display: inline-block;
        }
        
        .menu-section {
            padding: 20px 0;
        }
        
        .menu-item {
            padding: 15px 25px;
            display: flex;
            align-items: center;
            text-decoration: none;
            color: #ecf0f1;
            transition: all 0.3s;
            border-left: 4px solid transparent;
        }
        
        .menu-item:hover {
            background: rgba(255,255,255,0.1);
            border-left: 4px solid #e74c3c;
            color: white;
        }
        
        .menu-item i {
            font-size: 18px;
            margin-right: 15px;
            width: 20px;
            text-align: center;
        }
        
        .logout-item {
            margin-top: 20px;
            border-top: 1px solid rgba(255,255,255,0.1);
            padding-top: 20px;
        }
        
        .logout-item .menu-item {
            color: #e74c3c;
        }
        
        .logout-item .menu-item:hover {
            background: rgba(231, 76, 60, 0.1);
            border-left: 4px solid #e74c3c;
        }
        
        /* Main Content Styles */
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
        }
        
        .welcome-message h1 {
            color: #2c3e50;
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .welcome-message p {
            color: #7f8c8d;
            font-size: 16px;
        }
        
        .login-time {
            background: #ecf0f1;
            padding: 8px 15px;
            border-radius: 20px;
            display: inline-block;
            margin-top: 10px;
            font-size: 14px;
            color: #34495e;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        
        .stat-number {
            font-size: 2.5em;
            font-weight: bold;
            color: #3498db;
            margin-bottom: 10px;
        }
        
        .stat-label {
            color: #7f8c8d;
            font-size: 14px;
            font-weight: 600;
        }
        
        .recent-activity {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .recent-activity h3 {
            color: #2c3e50;
            margin-bottom: 20px;
            font-size: 20px;
        }
        
        .activity-item {
            display: flex;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid #ecf0f1;
        }
        
        .activity-item:last-child {
            border-bottom: none;
        }
        
        .activity-icon {
            font-size: 20px;
            margin-right: 15px;
            width: 40px;
            height: 40px;
            background: #ecf0f1;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .activity-content {
            flex: 1;
        }
        
        .activity-title {
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        
        .activity-time {
            color: #7f8c8d;
            font-size: 12px;
        }
        
        .profile-info {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .profile-info h3 {
            color: #2c3e50;
            margin-bottom: 20px;
            font-size: 20px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        
        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #ecf0f1;
        }
        
        .info-item:last-child {
            border-bottom: none;
        }
        
        .info-label {
            font-weight: bold;
            color: #2c3e50;
        }
        
        .info-value {
            color: #7f8c8d;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="profile-section">
            <div class="profile-pic">
                <?php echo strtoupper(substr($customer_name, 0, 1)); ?>
            </div>
            <div class="profile-name"><?php echo htmlspecialchars($customer_name); ?></div>
            <div class="profile-email"><?php echo htmlspecialchars($customer_email); ?></div>
            <div class="profile-role">Customer</div>
        </div>
        
        <div class="menu-section">
            <a href="customer_dashboard.php" class="menu-item">
                <i>🏠</i> Dashboard
            </a>
            <a href="#" class="menu-item">
                <i>📋</i> My Orders
            </a>
            <a href="#" class="menu-item">
                <i>👤</i> Profile Settings
            </a>
            <a href="#" class="menu-item">
                <i>🔒</i> Change Password
            </a>
            <a href="#" class="menu-item">
                <i>📞</i> Support
            </a>
            
            <div class="logout-item">
                <a href="customer_logout.php" class="menu-item">
                    <i>🚪</i> Logout
                </a>
            </div>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <div class="header">
            <div class="welcome-message">
                <h1>🎉 Welcome back, <?php echo htmlspecialchars($customer_name); ?>!</h1>
                <p>Here's your personalized customer dashboard.</p>
                <div class="login-time">
                    <strong>🕒 Login Time:</strong> <?php echo $login_time; ?>
                </div>
            </div>
        </div>
        
        <!-- Profile Information -->
        <div class="profile-info">
            <h3>👤 Your Profile Information</h3>
            <div class="info-grid">
                <div>
                    <div class="info-item">
                        <span class="info-label">Full Name:</span>
                        <span class="info-value"><?php echo htmlspecialchars($current_customer['name'] ?? $customer_name); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Email:</span>
                        <span class="info-value"><?php echo htmlspecialchars($customer_email); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Phone:</span>
                        <span class="info-value"><?php echo htmlspecialchars($current_customer['phone'] ?? 'Not provided'); ?></span>
                    </div>
                </div>
                <div>
                    <div class="info-item">
                        <span class="info-label">Member Since:</span>
                        <span class="info-value"><?php echo $current_customer['registration_date'] ?? 'Recent'; ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Last Login:</span>
                        <span class="info-value"><?php echo $current_customer['last_login'] ?? 'First time'; ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Total Logins:</span>
                        <span class="info-value"><?php echo $current_customer['login_count'] ?? 1; ?> times</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">0</div>
                <div class="stat-label">📦 Active Orders</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">0</div>
                <div class="stat-label">✅ Completed Orders</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">0</div>
                <div class="stat-label">⭐ Your Reviews</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">🎯</div>
                <div class="stat-label">New Customer</div>
            </div>
        </div>
        
        <!-- Recent Activity -->
        <div class="recent-activity">
            <h3>📋 Recent Activity</h3>
            <div class="activity-item">
                <div class="activity-icon">🔐</div>
                <div class="activity-content">
                    <div class="activity-title">Account Login</div>
                    <div class="activity-time">You logged in successfully - <?php echo $login_time; ?></div>
                </div>
            </div>
            <div class="activity-item">
                <div class="activity-icon">👤</div>
                <div class="activity-content">
                    <div class="activity-title">Profile Viewed</div>
                    <div class="activity-time">You checked your profile information - Today</div>
                </div>
            </div>
            <div class="activity-item">
                <div class="activity-icon">🎯</div>
                <div class="activity-content">
                    <div class="activity-title">Welcome Bonus</div>
                    <div class="activity-time">You're eligible for new customer benefits - Today</div>
                </div>
            </div>
            <div class="activity-item">
                <div class="activity-icon">📧</div>
                <div class="activity-content">
                    <div class="activity-title">Account Verified</div>
                    <div class="activity-time">Your email address has been verified - During registration</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>