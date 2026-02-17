<?php
session_start();

// Check if admin is logged in - if not, redirect to login page
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin_login.php');
    exit();
}

$username = $_SESSION['admin_username'];
$login_time = date('Y-m-d H:i:s', $_SESSION['admin_login_time']);

// Load users data for user management
$users_file = 'data/users.json';
// Create data directory if it doesn't exist
if (!is_dir('data')) {
    mkdir('data', 0777, true);
}

// Initialize users array
$all_users = [];

// Load existing users from JSON file
if (file_exists($users_file)) {
    $users_data = file_get_contents($users_file);
    if (!empty($users_data)) {
        $all_users = json_decode($users_data, true) ?? [];
    }
}

// Add default admin users to the users list if they don't exist
$default_admins = [
    'admin' => ['password' => 'admin123', 'role' => 'admin'],
    'varshan' => ['password' => 'varshan123', 'role' => 'admin'],
    'rohan' => ['password' => 'rohan123', 'role' => 'admin'],
    'nisarga' => ['password' => 'nisarga123', 'role' => 'admin']
];

// User profile data for admin users
$admin_profiles = [
    'admin' => [
        'name' => 'Administrator',
        'email' => 'admin@company.com',
        'role' => 'Super Admin',
        'department' => 'IT Department',
        'phone' => '+1 (555) 123-4567',
        'last_login' => '2025-10-03 15:30:00',
        'join_date' => '2024-01-15',
        'bio' => 'System administrator with full access to all features and settings.',
        'location' => 'New York, USA',
        'skills' => ['System Management', 'Security', 'Database Administration', 'Team Leadership']
    ],
    'varshan' => [
        'name' => 'Varshan Gowda',
        'email' => 'varshan@company.com',
        'role' => 'Manager',
        'department' => 'Operations',
        'phone' => '+1 (555) 123-4568',
        'last_login' => '2025-10-03 16:45:00',
        'join_date' => '2024-03-20',
        'bio' => 'Operations manager responsible for event coordination and team management.',
        'location' => 'Mumbai, India',
        'skills' => ['Team Management', 'Event Planning', 'Operations', 'Client Relations']
    ],
    'rohan' => [
        'name' => 'Rohan.M',
        'email' => 'rohan.m0103@gmail.com',
        'role' => 'Developer',
        'department' => 'Development',
        'phone' => '7022288653',
        'last_login' => '2025-10-03 18:21:00',
        'join_date' => '2024-02-10',
        'bio' => 'Full-stack developer specializing in web applications and system architecture.',
        'location' => 'Bangalore, India',
        'skills' => ['PHP', 'JavaScript', 'Database Design', 'API Development', 'React']
    ],
    'nisarga' => [
        'name' => 'Nisarga Gopal',
        'email' => 'nisarga@company.com',
        'role' => 'Designer',
        'department' => 'Design',
        'phone' => '+1 (555) 123-4570',
        'last_login' => '2025-10-03 14:15:00',
        'join_date' => '2024-04-05',
        'bio' => 'UI/UX designer focused on creating intuitive and beautiful user interfaces.',
        'location' => 'San Francisco, USA',
        'skills' => ['UI/UX Design', 'Figma', 'Adobe Creative Suite', 'Prototyping', 'User Research']
    ]
];

// Function to verify if a password needs rehashing
function password_needs_rehash_check($hash) {
    return password_needs_rehash($hash, PASSWORD_DEFAULT);
}

// Initialize admin users if they don't exist
foreach ($default_admins as $admin_username => $admin_data) {
    $admin_exists = false;
    foreach ($all_users as &$user) {
        if ($user['username'] === $admin_username && $user['role'] === 'admin') {
            $admin_exists = true;
            
            // Update user data with latest profile info
            $user['name'] = $admin_profiles[$admin_username]['name'] ?? ucfirst($admin_username);
            $user['email'] = $admin_profiles[$admin_username]['email'] ?? $admin_username . '@company.com';
            $user['phone'] = $admin_profiles[$admin_username]['phone'] ?? '';
            
            // Check if password needs to be rehashed (for existing users)
            if (isset($user['password']) && password_needs_rehash_check($user['password'])) {
                $user['password'] = password_hash($admin_data['password'], PASSWORD_DEFAULT);
            }
            break;
        }
    }
    
    if (!$admin_exists) {
        $all_users[] = [
            'id' => uniqid('admin_'),
            'username' => $admin_username,
            'name' => $admin_profiles[$admin_username]['name'] ?? ucfirst($admin_username),
            'email' => $admin_profiles[$admin_username]['email'] ?? $admin_username . '@company.com',
            'password' => password_hash($admin_data['password'], PASSWORD_DEFAULT), // ENCRYPTED PASSWORD
            'role' => 'admin',
            'status' => 'active',
            'registration_date' => date('Y-m-d H:i:s'),
            'last_login' => null,
            'login_count' => 0,
            'phone' => $admin_profiles[$admin_username]['phone'] ?? '',
            'profile_picture' => null // Initialize profile picture field
        ];
    }
}

// Function to verify customer passwords (for login system)
function verify_customer_password($input_password, $stored_hash) {
    return password_verify($input_password, $stored_hash);
}

// Function to encrypt customer passwords when creating/updating
function encrypt_customer_password($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// Handle customer password updates if needed
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_customer_password'])) {
    $customer_id = $_POST['customer_id'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    
    if (!empty($customer_id) && !empty($new_password)) {
        foreach ($all_users as &$user) {
            if ($user['id'] === $customer_id && $user['role'] === 'customer') {
                $user['password'] = encrypt_customer_password($new_password);
                break;
            }
        }
        // Save updated users data
        file_put_contents($users_file, json_encode($all_users, JSON_PRETTY_PRINT));
        $_SESSION['success_message'] = 'Customer password updated successfully!';
        header('Location: admin_dashboard.php?view=users');
        exit();
    }
}

// Handle profile picture upload - PERMANENT STORAGE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_picture'])) {
    $upload_dir = 'uploads/profiles/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    $filename = $username . '_' . time() . '.jpg';
    $target_file = $upload_dir . $filename;
    
    if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file)) {
        // Update profile picture in user data (PERMANENT)
        foreach ($all_users as &$user) {
            if ($user['username'] === $username) {
                // Delete old profile picture if exists
                if (!empty($user['profile_picture']) && file_exists($user['profile_picture'])) {
                    unlink($user['profile_picture']);
                }
                $user['profile_picture'] = $target_file;
                break;
            }
        }
        // Save updated users data
        file_put_contents($users_file, json_encode($all_users, JSON_PRETTY_PRINT));
        $_SESSION['profile_picture'] = $target_file; // Also update session
    }
}

// Handle captured photo from camera - PERMANENT STORAGE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['captured_image'])) {
    $upload_dir = 'uploads/profiles/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    $filename = $username . '_camera_' . time() . '.jpg';
    $target_file = $upload_dir . $filename;
    
    $image_data = $_POST['captured_image'];
    $image_data = str_replace('data:image/jpeg;base64,', '', $image_data);
    $image_data = str_replace(' ', '+', $image_data);
    $image_data = base64_decode($image_data);
    
    if (file_put_contents($target_file, $image_data)) {
        // Update profile picture in user data (PERMANENT)
        foreach ($all_users as &$user) {
            if ($user['username'] === $username) {
                // Delete old profile picture if exists
                if (!empty($user['profile_picture']) && file_exists($user['profile_picture'])) {
                    unlink($user['profile_picture']);
                }
                $user['profile_picture'] = $target_file;
                break;
            }
        }
        // Save updated users data
        file_put_contents($users_file, json_encode($all_users, JSON_PRETTY_PRINT));
        $_SESSION['profile_picture'] = $target_file; // Also update session
    }
}

// Save updated users list
file_put_contents($users_file, json_encode($all_users, JSON_PRETTY_PRINT));

// Get current user data
$current_user_data = null;
foreach ($all_users as $user) {
    if ($user['username'] === $username && $user['role'] === 'admin') {
        $current_user_data = $user;
        break;
    }
}

// Get current profile picture - PERMANENT from user data
$profile_picture = null;
if ($current_user_data && !empty($current_user_data['profile_picture']) && file_exists($current_user_data['profile_picture'])) {
    $profile_picture = $current_user_data['profile_picture'];
    $_SESSION['profile_picture'] = $profile_picture; // Update session
} else {
    $profile_picture = isset($_SESSION['profile_picture']) ? $_SESSION['profile_picture'] : 'default';
}

// Merge profile data
$current_user = array_merge(
    $admin_profiles[$username] ?? [
        'name' => $username,
        'email' => $username . '@company.com',
        'role' => 'User',
        'department' => 'General',
        'phone' => '+1 (555) 000-0000',
        'last_login' => $login_time,
        'join_date' => '2024-01-01',
        'bio' => 'System user with administrative privileges.',
        'location' => 'Unknown',
        'skills' => ['General Administration']
    ],
    ['profile_picture' => $profile_picture]
);

// Filter only customers for user management
$customers = array_filter($all_users, function($user) {
    return isset($user['role']) && $user['role'] === 'customer';
});

// Filter only admins for admin management
$admins = array_filter($all_users, function($user) {
    return isset($user['role']) && $user['role'] === 'admin';
});

// Handle user actions (activate/deactivate/delete)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $user_id = $_POST['user_id'] ?? '';
    $action = $_POST['action'] ?? '';
    
    foreach ($all_users as &$user) {
        if ($user['id'] === $user_id) {
            switch ($action) {
                case 'activate':
                    $user['status'] = 'active';
                    break;
                case 'deactivate':
                    $user['status'] = 'inactive';
                    break;
                case 'delete':
                    // Remove user from array
                    $all_users = array_filter($all_users, function($u) use ($user_id) {
                        return $u['id'] !== $user_id;
                    });
                    break;
                case 'reset_password':
                    // Reset customer password to default
                    $default_password = 'customer123'; // You can change this default
                    $user['password'] = encrypt_customer_password($default_password);
                    $_SESSION['success_message'] = 'Password reset successfully! New password: ' . $default_password;
                    break;
                case 'promote_to_admin':
                    $user['role'] = 'admin';
                    $_SESSION['success_message'] = 'User promoted to admin successfully!';
                    break;
                case 'demote_to_customer':
                    $user['role'] = 'customer';
                    $_SESSION['success_message'] = 'Admin demoted to customer successfully!';
                    break;
            }
            break;
        }
    }
    
    // Save updated users data
    file_put_contents($users_file, json_encode(array_values($all_users), JSON_PRETTY_PRINT));
    header('Location: admin_dashboard.php?view=' . ($_POST['view'] ?? 'users'));
    exit();
}

// Check if we're viewing user management
$view = $_GET['view'] ?? 'dashboard';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            background: linear-gradient(-45deg, #667eea, #764ba2, #f093fb, #f5576c);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
            overflow-x: hidden;
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

        /* Floating Background Elements */
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

        .floating-element:nth-child(1) {
            width: 80px;
            height: 80px;
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }

        .floating-element:nth-child(2) {
            width: 120px;
            height: 120px;
            top: 60%;
            left: 80%;
            animation-delay: 2s;
        }

        .floating-element:nth-child(3) {
            width: 60px;
            height: 60px;
            top: 80%;
            left: 20%;
            animation-delay: 4s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        /* Header with Horizontal Navigation */
        .dashboard-header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            z-index: 1000;
            padding: 0 2rem;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1400px;
            margin: 0 auto;
            padding: 1rem 0;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #333;
            font-size: 1.5rem;
            font-weight: bold;
        }

        .logo i {
            font-size: 2rem;
            color: #667eea;
        }

        /* Horizontal Navigation */
        .horizontal-nav {
            display: flex;
            gap: 0;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 50px;
            padding: 5px;
            backdrop-filter: blur(10px);
        }

        .nav-item {
            padding: 12px 25px;
            color: #333;
            text-decoration: none;
            border-radius: 50px;
            transition: all 0.3s ease;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            border: none;
            background: transparent;
            font-size: 14px;
        }

        .nav-item:hover {
            background: rgba(102, 126, 234, 0.1);
            transform: translateY(-2px);
        }

        .nav-item.active {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
            color: #333;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: white;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .user-avatar:hover {
            transform: scale(1.1);
        }

        .logout-btn {
            background: rgba(102, 126, 234, 0.1);
            border: none;
            color: #333;
            padding: 8px 15px;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .logout-btn:hover {
            background: rgba(102, 126, 234, 0.2);
            transform: translateY(-2px);
        }

        /* Main Content Area */
        .main-content {
            margin-top: 100px;
            padding: 2rem;
            min-height: calc(100vh - 100px);
            display: flex;
            justify-content: center;
            align-items: flex-start;
        }

        .content-wrapper {
            width: 100%;
            max-width: 1400px;
        }

        /* White Translucent Glassmorphism Card */
        .glass-card {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            animation: fadeInUp 0.8s ease;
            overflow: hidden;
            width: 100%;
            min-height: 600px;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Welcome Section */
        .welcome-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 600px;
            text-align: center;
            padding: 3rem;
            background: rgba(255, 255, 255, 0.8);
        }

        .welcome-content {
            text-align: center;
            padding: 2rem;
            max-width: 800px;
            width: 100%;
        }

        .welcome-content h1 {
            font-weight: bold;
            color: #333;
            margin-bottom: 1.5rem;
            font-size: 3.5rem;
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
            line-height: 1.2;
        }

        .welcome-content .subtitle {
            color: #666;
            font-size: 1.5rem;
            margin-bottom: 2.5rem;
            font-weight: 300;
        }

        .login-info {
            background: rgba(102, 126, 234, 0.1);
            padding: 1.5rem 2.5rem;
            border-radius: 50px;
            display: inline-block;
            font-size: 1.1rem;
            color: #333;
            font-weight: 500;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(102, 126, 234, 0.2);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .login-info .user-email {
            color: #667eea;
            font-weight: bold;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            padding: 2rem;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 1.5rem;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: transform 0.3s ease;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: #666;
            font-size: 0.9rem;
        }

        /* Section Content */
        .section-content {
            padding: 3rem;
            min-height: 600px;
            color: #333;
        }

        .section-content h1 {
            color: #333;
            margin-bottom: 2rem;
            font-size: 2.5rem;
            text-align: center;
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
        }

        .section-content h2 {
            color: #333;
            margin: 2rem 0 1rem;
            font-size: 1.8rem;
            text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.1);
        }

        .section-content p {
            font-size: 1.1rem;
            color: #666;
            text-align: center;
            line-height: 1.6;
            max-width: 600px;
            margin: 0 auto 2rem;
        }

        /* User Management Styles */
        .user-management-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .users-table-container {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            padding: 2rem;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .search-box {
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(102, 126, 234, 0.3);
            border-radius: 25px;
            padding: 0.8rem 1.5rem;
            color: #333;
            width: 300px;
            backdrop-filter: blur(5px);
        }

        .search-box::placeholder {
            color: #999;
        }

        .users-table {
            width: 100%;
            border-collapse: collapse;
        }

        .users-table th {
            background: rgba(102, 126, 234, 0.1);
            padding: 1rem;
            text-align: left;
            color: #333;
            font-weight: 600;
            border-bottom: 2px solid rgba(102, 126, 234, 0.2);
        }

        .users-table td {
            padding: 1rem;
            border-bottom: 1px solid rgba(102, 126, 234, 0.1);
            color: #666;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-avatar-table {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: white;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            font-size: 0.8rem;
            transition: all 0.3s ease;
            color: white;
            font-weight: 500;
        }

        .btn-view {
            background: #3498db;
        }

        .btn-reset-password {
            background: #9b59b6;
        }

        .btn-deactivate {
            background: #e74c3c;
        }

        .btn-activate {
            background: #2ecc71;
        }

        .btn-delete {
            background: #e67e22;
        }

        .btn-promote {
            background: #27ae60;
        }

        .btn-demote {
            background: #e74c3c;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .status-badge {
            padding: 0.3rem 0.8rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .status-active {
            background: rgba(46, 204, 113, 0.2);
            color: #27ae60;
        }

        .status-inactive {
            background: rgba(231, 76, 60, 0.2);
            color: #c0392b;
        }

        .role-badge {
            padding: 0.3rem 0.8rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .role-admin {
            background: rgba(155, 89, 182, 0.2);
            color: #8e44ad;
        }

        .role-customer {
            background: rgba(52, 152, 219, 0.2);
            color: #2980b9;
        }

        /* New Section Styles */
        .section-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .info-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: transform 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .info-card:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .info-card h3 {
            color: #333;
            margin-bottom: 1rem;
            font-size: 1.3rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .info-card p {
            color: #666;
            line-height: 1.6;
            text-align: left;
            margin: 0;
        }

        .feature-list {
            list-style: none;
            margin-top: 1rem;
        }

        .feature-list li {
            padding: 0.5rem 0;
            color: #666;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .feature-list li i {
            color: #667eea;
        }

        /* Success and Error Messages */
        .success-message {
            background: rgba(46, 204, 113, 0.2);
            color: #27ae60;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1rem;
            text-align: center;
            border: 1px solid rgba(46, 204, 113, 0.3);
            backdrop-filter: blur(10px);
        }

        .error-message {
            background: rgba(231, 76, 60, 0.2);
            color: #c0392b;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1rem;
            text-align: center;
            border: 1px solid rgba(231, 76, 60, 0.3);
            backdrop-filter: blur(10px);
        }

        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin: 2rem 0;
        }

        .action-card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .action-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
            background: rgba(255, 255, 255, 0.95);
        }

        .action-icon {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: #667eea;
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .horizontal-nav {
                flex-wrap: wrap;
                justify-content: center;
                gap: 5px;
            }
            
            .nav-item {
                padding: 10px 15px;
                font-size: 13px;
            }
            
            .welcome-content h1 {
                font-size: 2.5rem;
            }
        }

        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                gap: 1rem;
                padding: 1rem;
            }
            
            .horizontal-nav {
                order: 3;
                width: 100%;
                justify-content: center;
            }
            
            .main-content {
                margin-top: 160px;
                padding: 1rem;
            }
            
            .welcome-content h1 {
                font-size: 2rem;
            }
            
            .welcome-content .subtitle {
                font-size: 1.2rem;
            }
            
            .section-content {
                padding: 2rem 1rem;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
                padding: 1rem;
            }
            
            .table-header {
                flex-direction: column;
                gap: 1rem;
            }
            
            .search-box {
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            .nav-item {
                padding: 8px 12px;
                font-size: 12px;
            }
            
            .nav-item i {
                display: none;
            }
            
            .welcome-content h1 {
                font-size: 1.8rem;
            }
            
            .login-info {
                padding: 1rem 1.5rem;
                font-size: 1rem;
            }
            
            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <!-- Background elements from index page -->
    <div class="bg-image"></div>
    <div class="bg-overlay"></div>
    <div id="particles-js"></div>
    
    <!-- Floating Background Elements -->
    <div class="floating-elements">
        <div class="floating-element"></div>
        <div class="floating-element"></div>
        <div class="floating-element"></div>
    </div>

    <!-- Header with Horizontal Navigation -->
    <header class="dashboard-header">
        <div class="header-content">
            <div class="logo">
                <i class="fas fa-shield-alt"></i>
                <span>Admin Dashboard</span>
            </div>
            
            <!-- Horizontal Navigation -->
            <nav class="horizontal-nav">
                <button class="nav-item <?php echo $view === 'dashboard' ? 'active' : ''; ?>" onclick="showSection('dashboard')">
                    <i class="fas fa-home"></i> Dashboard
                </button>
                <button class="nav-item <?php echo $view === 'users' ? 'active' : ''; ?>" onclick="showSection('users')">
                    <i class="fas fa-users"></i> User Management
                </button>
                <button class="nav-item <?php echo $view === 'admins' ? 'active' : ''; ?>" onclick="showSection('admins')">
                    <i class="fas fa-user-shield"></i> Admin Management
                </button>
                <button class="nav-item <?php echo $view === 'reports' ? 'active' : ''; ?>" onclick="showSection('reports')">
                    <i class="fas fa-chart-bar"></i> Reports
                </button>
                <button class="nav-item <?php echo $view === 'settings' ? 'active' : ''; ?>" onclick="showSection('settings')">
                    <i class="fas fa-cog"></i> Settings
                </button>
            </nav>
            
            <div class="user-info">
                <div class="user-avatar" onclick="openProfileModal()">
                    <?php echo strtoupper(substr($current_user['name'], 0, 1)); ?>
                </div>
                <form method="POST" action="admin_logout.php" style="display: inline;">
                    <button type="submit" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <div class="main-content">
        <div class="content-wrapper">
            <div class="glass-card">
                <?php if (isset($_SESSION['success_message'])): ?>
                    <div class="success-message">
                        <i class="fas fa-check-circle"></i> <?php echo $_SESSION['success_message']; ?>
                    </div>
                    <?php unset($_SESSION['success_message']); ?>
                <?php endif; ?>
                
                <?php if ($view === 'dashboard'): ?>
                    <!-- Dashboard View -->
                    <div class="welcome-container">
                        <div class="welcome-content">
                            <h1>🎉 Welcome back, <?php echo htmlspecialchars($current_user['name']); ?>!</h1>
                            <div class="subtitle">
                                Ready to manage your system today? Here's what's happening right now.
                            </div>
                            <div class="login-info">
                                <strong>🕒 Login Time:</strong> <?php echo $login_time; ?> | 
                                <strong>📧 Email:</strong> <span class="user-email"><?php echo htmlspecialchars($current_user['email']); ?></span>
                            </div>
                            
                            <!-- Quick Actions -->
                            <div class="quick-actions">
                                <div class="action-card" onclick="showSection('users')">
                                    <div class="action-icon"><i class="fas fa-user-plus"></i></div>
                                    <h3>Add New User</h3>
                                    <p>Create new customer accounts</p>
                                </div>
                                <div class="action-card" onclick="showSection('admins')">
                                    <div class="action-icon"><i class="fas fa-user-shield"></i></div>
                                    <h3>Manage Admins</h3>
                                    <p>Admin permissions & roles</p>
                                </div>
                                <div class="action-card" onclick="showSection('reports')">
                                    <div class="action-icon"><i class="fas fa-chart-pie"></i></div>
                                    <h3>View Reports</h3>
                                    <p>System analytics & insights</p>
                                </div>
                                <div class="action-card" onclick="showSection('settings')">
                                    <div class="action-icon"><i class="fas fa-sliders-h"></i></div>
                                    <h3>System Settings</h3>
                                    <p>Configure preferences</p>
                                </div>
                            </div>
                            
                            <div class="stats-grid">
                                <div class="stat-card" onclick="showSection('users')">
                                    <div class="stat-icon"><i class="fas fa-users"></i></div>
                                    <div class="stat-number"><?php echo count($customers); ?></div>
                                    <div class="stat-label">Total Customers</div>
                                    <div style="font-size: 0.8rem; color: #666; margin-top: 0.5rem;">
                                        <?php echo count(array_filter($customers, function($user) { return $user['status'] === 'active'; })); ?> active
                                    </div>
                                </div>
                                <div class="stat-card" onclick="showSection('admins')">
                                    <div class="stat-icon"><i class="fas fa-user-shield"></i></div>
                                    <div class="stat-number"><?php echo count($admins); ?></div>
                                    <div class="stat-label">Administrators</div>
                                    <div style="font-size: 0.8rem; color: #666; margin-top: 0.5rem;">
                                        <?php echo count(array_filter($admins, function($user) { return $user['status'] === 'active'; })); ?> active
                                    </div>
                                </div>
                                <div class="stat-card">
                                    <div class="stat-icon"><i class="fas fa-chart-line"></i></div>
                                    <div class="stat-number">98.7%</div>
                                    <div class="stat-label">System Uptime</div>
                                    <div style="font-size: 0.8rem; color: #666; margin-top: 0.5rem;">
                                        Last 30 days
                                    </div>
                                </div>
                                <div class="stat-card">
                                    <div class="stat-icon"><i class="fas fa-shield-alt"></i></div>
                                    <div class="stat-number">100%</div>
                                    <div class="stat-label">Security Status</div>
                                    <div style="font-size: 0.8rem; color: #666; margin-top: 0.5rem;">
                                        All systems secure
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php elseif ($view === 'users'): ?>
                    <!-- User Management View -->
                    <div class="section-content">
                        <div class="user-management-header">
                            <h1><i class="fas fa-users"></i> Customer Management</h1>
                            <p>Manage customer accounts, permissions, and access controls. Monitor user activity and maintain account security.</p>
                        </div>
                        
                        <!-- Statistics Cards -->
                        <div class="stats-cards">
                            <div class="stat-card">
                                <div class="stat-number"><?php echo count($customers); ?></div>
                                <div class="stat-label">Total Customers</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number">
                                    <?php echo count(array_filter($customers, function($user) { 
                                        return $user['status'] === 'active'; 
                                    })); ?>
                                </div>
                                <div class="stat-label">Active Customers</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number">
                                    <?php echo count(array_filter($customers, function($user) { 
                                        return $user['status'] === 'inactive'; 
                                    })); ?>
                                </div>
                                <div class="stat-label">Inactive Customers</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number">
                                    <?php 
                                    $total_logins = 0;
                                    foreach ($customers as $user) {
                                        $total_logins += $user['login_count'] ?? 0;
                                    }
                                    echo $total_logins;
                                    ?>
                                </div>
                                <div class="stat-label">Total Logins</div>
                            </div>
                        </div>
                        
                        <!-- Users Table -->
                        <div class="users-table-container">
                            <div class="table-header">
                                <h2><i class="fas fa-list"></i> Customer Directory</h2>
                                <input type="text" class="search-box" placeholder="🔍 Search customers..." onkeyup="searchUsers()">
                            </div>
                            
                            <?php if (empty($customers)): ?>
                                <div class="no-users" style="text-align: center; padding: 3rem; color: #666;">
                                    <i class="fas fa-users" style="font-size: 3rem; margin-bottom: 1rem; color: #667eea;"></i>
                                    <h3>No Customers Registered Yet</h3>
                                    <p>Customers will appear here once they register through the customer registration page.</p>
                                    <button class="btn btn-activate" style="margin-top: 1rem;">
                                        <i class="fas fa-user-plus"></i> Add Sample Customer
                                    </button>
                                </div>
                            <?php else: ?>
                                <table class="users-table" id="usersTable">
                                    <thead>
                                        <tr>
                                            <th>Customer Profile</th>
                                            <th>Contact Information</th>
                                            <th>Registration</th>
                                            <th>Last Activity</th>
                                            <th>Login Count</th>
                                            <th>Account Status</th>
                                            <th>Management</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($customers as $user): ?>
                                            <tr>
                                                <td>
                                                    <div class="user-info">
                                                        <div class="user-avatar-table">
                                                            <?php echo strtoupper(substr($user['name'] ?? $user['username'], 0, 1)); ?>
                                                        </div>
                                                        <div class="user-details">
                                                            <h4 style="color: #333; margin: 0;"><?php echo htmlspecialchars($user['name'] ?? $user['username']); ?></h4>
                                                            <p style="color: #666; font-size: 0.8rem; margin: 0;">ID: <?php echo $user['id']; ?></p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div style="font-weight: bold; color: #333;"><?php echo htmlspecialchars($user['email']); ?></div>
                                                    <div style="color: #666; font-size: 0.8rem;"><?php echo htmlspecialchars($user['phone'] ?? 'No phone'); ?></div>
                                                </td>
                                                <td>
                                                    <div style="color: #333; font-weight: 500;"><?php echo $user['registration_date'] ?? 'N/A'; ?></div>
                                                </td>
                                                <td>
                                                    <div style="color: #333;"><?php echo $user['last_login'] ?? 'Never logged in'; ?></div>
                                                </td>
                                                <td>
                                                    <div style="color: #333; font-weight: bold; text-align: center;"><?php echo $user['login_count'] ?? 0; ?></div>
                                                </td>
                                                <td>
                                                    <span class="status-badge <?php echo ($user['status'] ?? 'active') === 'active' ? 'status-active' : 'status-inactive'; ?>">
                                                        <?php echo ucfirst($user['status'] ?? 'active'); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="action-buttons">
                                                        <button class="btn btn-view" onclick="viewUser('<?php echo $user['id']; ?>')">
                                                            <i class="fas fa-eye"></i> View
                                                        </button>
                                                        
                                                        <!-- Password Reset Button -->
                                                        <form method="POST" style="display: inline;">
                                                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                            <input type="hidden" name="action" value="reset_password">
                                                            <input type="hidden" name="view" value="users">
                                                            <button type="submit" class="btn btn-reset-password" 
                                                                    onclick="return confirm('Reset password for <?php echo htmlspecialchars($user['name'] ?? $user['username']); ?>? A default password will be set.')">
                                                                <i class="fas fa-key"></i> Reset PW
                                                            </button>
                                                        </form>
                                                        
                                                        <!-- Promote to Admin Button -->
                                                        <form method="POST" style="display: inline;">
                                                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                            <input type="hidden" name="action" value="promote_to_admin">
                                                            <input type="hidden" name="view" value="users">
                                                            <button type="submit" class="btn btn-promote" 
                                                                    onclick="return confirm('Promote <?php echo htmlspecialchars($user['name'] ?? $user['username']); ?> to administrator?')">
                                                                <i class="fas fa-user-shield"></i> Promote
                                                            </button>
                                                        </form>
                                                        
                                                        <?php if (($user['status'] ?? 'active') === 'active'): ?>
                                                            <form method="POST" style="display: inline;">
                                                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                                <input type="hidden" name="action" value="deactivate">
                                                                <input type="hidden" name="view" value="users">
                                                                <button type="submit" class="btn btn-deactivate" 
                                                                        onclick="return confirm('Deactivate <?php echo htmlspecialchars($user['name'] ?? $user['username']); ?>?')">
                                                                    <i class="fas fa-pause"></i> Deactivate
                                                                </button>
                                                            </form>
                                                        <?php else: ?>
                                                            <form method="POST" style="display: inline;">
                                                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                                <input type="hidden" name="action" value="activate">
                                                                <input type="hidden" name="view" value="users">
                                                                <button type="submit" class="btn btn-activate">
                                                                    <i class="fas fa-play"></i> Activate
                                                                </button>
                                                            </form>
                                                        <?php endif; ?>
                                                        
                                                        <form method="POST" style="display: inline;">
                                                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                            <input type="hidden" name="action" value="delete">
                                                            <input type="hidden" name="view" value="users">
                                                            <button type="submit" class="btn btn-delete" 
                                                                    onclick="return confirm('Permanently delete <?php echo htmlspecialchars($user['name'] ?? $user['username']); ?>? This action cannot be undone.')">
                                                                <i class="fas fa-trash"></i> Delete
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php endif; ?>
                        </div>
                    </div>

                <?php elseif ($view === 'admins'): ?>
                    <!-- Admin Management View -->
                    <div class="section-content">
                        <div class="user-management-header">
                            <h1><i class="fas fa-user-shield"></i> Administrator Management</h1>
                            <p>Manage administrator accounts, permissions, and system access levels. Monitor admin activity and maintain security protocols.</p>
                        </div>
                        
                        <!-- Statistics Cards -->
                        <div class="stats-cards">
                            <div class="stat-card">
                                <div class="stat-number"><?php echo count($admins); ?></div>
                                <div class="stat-label">Total Admins</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number">
                                    <?php echo count(array_filter($admins, function($user) { 
                                        return $user['status'] === 'active'; 
                                    })); ?>
                                </div>
                                <div class="stat-label">Active Admins</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number">
                                    <?php echo count(array_filter($admins, function($user) { 
                                        return $user['status'] === 'inactive'; 
                                    })); ?>
                                </div>
                                <div class="stat-label">Inactive Admins</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number">
                                    <?php 
                                    $total_admin_logins = 0;
                                    foreach ($admins as $user) {
                                        $total_admin_logins += $user['login_count'] ?? 0;
                                    }
                                    echo $total_admin_logins;
                                    ?>
                                </div>
                                <div class="stat-label">Admin Logins</div>
                            </div>
                        </div>
                        
                        <!-- Admins Table -->
                        <div class="users-table-container">
                            <div class="table-header">
                                <h2><i class="fas fa-user-shield"></i> Administrator Directory</h2>
                                <input type="text" class="search-box" placeholder="🔍 Search administrators..." onkeyup="searchAdmins()">
                            </div>
                            
                            <?php if (empty($admins)): ?>
                                <div class="no-users" style="text-align: center; padding: 3rem; color: #666;">
                                    <i class="fas fa-user-shield" style="font-size: 3rem; margin-bottom: 1rem; color: #667eea;"></i>
                                    <h3>No Administrators Found</h3>
                                    <p>Administrator accounts will appear here once they are created or promoted.</p>
                                </div>
                            <?php else: ?>
                                <table class="users-table" id="adminsTable">
                                    <thead>
                                        <tr>
                                            <th>Admin Profile</th>
                                            <th>Contact Information</th>
                                            <th>Registration</th>
                                            <th>Last Login</th>
                                            <th>Login Count</th>
                                            <th>Role & Status</th>
                                            <th>Administration</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($admins as $user): ?>
                                            <tr>
                                                <td>
                                                    <div class="user-info">
                                                        <div class="user-avatar-table" style="background: linear-gradient(135deg, #9b59b6, #8e44ad);">
                                                            <?php echo strtoupper(substr($user['name'] ?? $user['username'], 0, 1)); ?>
                                                        </div>
                                                        <div class="user-details">
                                                            <h4 style="color: #333; margin: 0;">
                                                                <?php echo htmlspecialchars($user['name'] ?? $user['username']); ?>
                                                                <?php if ($user['username'] === $username): ?>
                                                                    <span style="color: #667eea; font-size: 0.7rem;">(You)</span>
                                                                <?php endif; ?>
                                                            </h4>
                                                            <p style="color: #666; font-size: 0.8rem; margin: 0;">ID: <?php echo $user['id']; ?></p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div style="font-weight: bold; color: #333;"><?php echo htmlspecialchars($user['email']); ?></div>
                                                    <div style="color: #666; font-size: 0.8rem;"><?php echo htmlspecialchars($user['phone'] ?? 'No phone'); ?></div>
                                                </td>
                                                <td>
                                                    <div style="color: #333; font-weight: 500;"><?php echo $user['registration_date'] ?? 'N/A'; ?></div>
                                                </td>
                                                <td>
                                                    <div style="color: #333;"><?php echo $user['last_login'] ?? 'Never logged in'; ?></div>
                                                </td>
                                                <td>
                                                    <div style="color: #333; font-weight: bold; text-align: center;"><?php echo $user['login_count'] ?? 0; ?></div>
                                                </td>
                                                <td>
                                                    <div style="display: flex; flex-direction: column; gap: 5px;">
                                                        <span class="role-badge role-admin">
                                                            Administrator
                                                        </span>
                                                        <span class="status-badge <?php echo ($user['status'] ?? 'active') === 'active' ? 'status-active' : 'status-inactive'; ?>">
                                                            <?php echo ucfirst($user['status'] ?? 'active'); ?>
                                                        </span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="action-buttons">
                                                        <button class="btn btn-view" onclick="viewAdmin('<?php echo $user['id']; ?>')">
                                                            <i class="fas fa-eye"></i> View
                                                        </button>
                                                        
                                                        <?php if ($user['username'] !== $username): ?>
                                                            <!-- Demote to Customer Button -->
                                                            <form method="POST" style="display: inline;">
                                                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                                <input type="hidden" name="action" value="demote_to_customer">
                                                                <input type="hidden" name="view" value="admins">
                                                                <button type="submit" class="btn btn-demote" 
                                                                        onclick="return confirm('Demote <?php echo htmlspecialchars($user['name'] ?? $user['username']); ?> to customer?')">
                                                                    <i class="fas fa-user"></i> Demote
                                                                </button>
                                                            </form>
                                                            
                                                            <?php if (($user['status'] ?? 'active') === 'active'): ?>
                                                                <form method="POST" style="display: inline;">
                                                                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                                    <input type="hidden" name="action" value="deactivate">
                                                                    <input type="hidden" name="view" value="admins">
                                                                    <button type="submit" class="btn btn-deactivate" 
                                                                            onclick="return confirm('Deactivate <?php echo htmlspecialchars($user['name'] ?? $user['username']); ?>?')">
                                                                        <i class="fas fa-pause"></i> Deactivate
                                                                    </button>
                                                                </form>
                                                            <?php else: ?>
                                                                <form method="POST" style="display: inline;">
                                                                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                                    <input type="hidden" name="action" value="activate">
                                                                    <input type="hidden" name="view" value="admins">
                                                                    <button type="submit" class="btn btn-activate">
                                                                        <i class="fas fa-play"></i> Activate
                                                                    </button>
                                                                </form>
                                                            <?php endif; ?>
                                                        <?php else: ?>
                                                            <span style="color: #666; font-size: 0.8rem;">Current User</span>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php endif; ?>
                        </div>
                    </div>

                <?php elseif ($view === 'reports'): ?>
                    <!-- Reports View -->
                    <div class="section-content">
                        <h1><i class="fas fa-chart-bar"></i> Analytics & Reports Dashboard</h1>
                        <p>Comprehensive insights and performance metrics for your system. Monitor user activity, system performance, and security events.</p>
                        
                        <div class="section-grid">
                            <div class="info-card">
                                <h3><i class="fas fa-chart-line"></i> Performance Analytics</h3>
                                <p>Track system performance and user engagement with detailed analytics and real-time monitoring.</p>
                                <ul class="feature-list">
                                    <li><i class="fas fa-check"></i> Real-time user activity monitoring</li>
                                    <li><i class="fas fa-check"></i> System performance dashboards</li>
                                    <li><i class="fas fa-check"></i> Custom report generation</li>
                                    <li><i class="fas fa-check"></i> Export data to multiple formats</li>
                                    <li><i class="fas fa-check"></i> Performance trend analysis</li>
                                    <li><i class="fas fa-check"></i> User behavior insights</li>
                                </ul>
                            </div>
                            
                            <div class="info-card">
                                <h3><i class="fas fa-download"></i> Data Export & Integration</h3>
                                <p>Export user data, activity logs, and system metrics for external analysis and reporting.</p>
                                <ul class="feature-list">
                                    <li><i class="fas fa-check"></i> CSV, Excel, and PDF exports</li>
                                    <li><i class="fas fa-check"></i> Scheduled report generation</li>
                                    <li><i class="fas fa-check"></i> Custom date range selection</li>
                                    <li><i class="fas fa-check"></i> Automated email delivery</li>
                                    <li><i class="fas fa-check"></i> API integration capabilities</li>
                                    <li><i class="fas fa-check"></i> Real-time data streaming</li>
                                </ul>
                            </div>
                            
                            <div class="info-card">
                                <h3><i class="fas fa-shield-alt"></i> Security & Compliance</h3>
                                <p>Monitor security events and access patterns to ensure system integrity and compliance.</p>
                                <ul class="feature-list">
                                    <li><i class="fas fa-check"></i> Login attempt tracking</li>
                                    <li><i class="fas fa-check"></i> Suspicious activity alerts</li>
                                    <li><i class="fas fa-check"></i> Access pattern analysis</li>
                                    <li><i class="fas fa-check"></i> Compliance reporting</li>
                                    <li><i class="fas fa-check"></i> Audit trail maintenance</li>
                                    <li><i class="fas fa-check"></i> Security incident reporting</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Quick Stats -->
                        <div class="stats-grid" style="margin-top: 2rem;">
                            <div class="stat-card">
                                <div class="stat-icon"><i class="fas fa-user-clock"></i></div>
                                <div class="stat-number">2,847</div>
                                <div class="stat-label">Active Sessions Today</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-icon"><i class="fas fa-database"></i></div>
                                <div class="stat-number">15.2GB</div>
                                <div class="stat-label">Data Processed</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-icon"><i class="fas fa-exclamation-triangle"></i></div>
                                <div class="stat-number">3</div>
                                <div class="stat-label">Security Alerts</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-icon"><i class="fas fa-chart-pie"></i></div>
                                <div class="stat-number">24</div>
                                <div class="stat-label">Reports Generated</div>
                            </div>
                        </div>
                    </div>

                <?php elseif ($view === 'settings'): ?>
                    <!-- Settings View -->
                    <div class="section-content">
                        <h1><i class="fas fa-cog"></i> System Configuration Center</h1>
                        <p>Configure system preferences, security settings, and administrative options to customize your experience.</p>
                        
                        <div class="section-grid">
                            <div class="info-card">
                                <h3><i class="fas fa-user-shield"></i> Security & Authentication</h3>
                                <p>Configure authentication methods, password policies, and security protocols to protect your system.</p>
                                <ul class="feature-list">
                                    <li><i class="fas fa-check"></i> Two-factor authentication setup</li>
                                    <li><i class="fas fa-check"></i> Password complexity requirements</li>
                                    <li><i class="fas fa-check"></i> Session timeout settings</li>
                                    <li><i class="fas fa-check"></i> IP whitelisting/blacklisting</li>
                                    <li><i class="fas fa-check"></i> API security configuration</li>
                                    <li><i class="fas fa-check"></i> Security audit logging</li>
                                </ul>
                            </div>
                            
                            <div class="info-card">
                                <h3><i class="fas fa-bell"></i> Notifications & Alerts</h3>
                                <p>Manage email notifications, alerts, and system announcements to stay informed about important events.</p>
                                <ul class="feature-list">
                                    <li><i class="fas fa-check"></i> Email notification templates</li>
                                    <li><i class="fas fa-check"></i> Real-time alert configuration</li>
                                    <li><i class="fas fa-check"></i> User preference management</li>
                                    <li><i class="fas fa-check"></i> SMS integration options</li>
                                    <li><i class="fas fa-check"></i> Push notification settings</li>
                                    <li><i class="fas fa-check"></i> Alert escalation policies</li>
                                </ul>
                            </div>
                            
                            <div class="info-card">
                                <h3><i class="fas fa-database"></i> System & Maintenance</h3>
                                <p>Adjust system parameters, backup settings, and maintenance options to optimize performance.</p>
                                <ul class="feature-list">
                                    <li><i class="fas fa-check"></i> Automated backup scheduling</li>
                                    <li><i class="fas fa-check"></i> System maintenance windows</li>
                                    <li><i class="fas fa-check"></i> Performance optimization</li>
                                    <li><i class="fas fa-check"></i> Database management tools</li>
                                    <li><i class="fas fa-check"></i> Cache configuration</li>
                                    <li><i class="fas fa-check"></i> System health monitoring</li>
                                </ul>
                            </div>
                        </div>

                        <!-- System Status -->
                        <div class="users-table-container" style="margin-top: 2rem;">
                            <h2><i class="fas fa-heartbeat"></i> System Status Overview</h2>
                            <table class="users-table">
                                <thead>
                                    <tr>
                                        <th>Service</th>
                                        <th>Status</th>
                                        <th>Response Time</th>
                                        <th>Uptime</th>
                                        <th>Last Check</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Web Server</td>
                                        <td><span class="status-badge status-active">Operational</span></td>
                                        <td>45ms</td>
                                        <td>99.98%</td>
                                        <td>2 minutes ago</td>
                                    </tr>
                                    <tr>
                                        <td>Database</td>
                                        <td><span class="status-badge status-active">Operational</span></td>
                                        <td>12ms</td>
                                        <td>99.99%</td>
                                        <td>1 minute ago</td>
                                    </tr>
                                    <tr>
                                        <td>Authentication</td>
                                        <td><span class="status-badge status-active">Operational</span></td>
                                        <td>28ms</td>
                                        <td>100%</td>
                                        <td>30 seconds ago</td>
                                    </tr>
                                    <tr>
                                        <td>Email Service</td>
                                        <td><span class="status-badge status-active">Operational</span></td>
                                        <td>150ms</td>
                                        <td>99.95%</td>
                                        <td>5 minutes ago</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Particles.js Library -->
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    
    <script>
        // Function to show sections
        function showSection(section) {
            window.location.href = 'admin_dashboard.php?view=' + section;
        }

        // Add active state to navigation items
        document.addEventListener('DOMContentLoaded', function() {
            const navItems = document.querySelectorAll('.nav-item');
            navItems.forEach(item => {
                item.addEventListener('click', function() {
                    navItems.forEach(nav => nav.classList.remove('active'));
                    this.classList.add('active');
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
        });

        // Add parallax effect to floating elements
        document.addEventListener('mousemove', function(e) {
            const floatingElements = document.querySelectorAll('.floating-element');
            floatingElements.forEach(element => {
                const speed = 5;
                const x = (window.innerWidth - e.pageX * speed) / 100;
                const y = (window.innerHeight - e.pageY * speed) / 100;
                element.style.transform = `translateX(${x}px) translateY(${y}px)`;
            });
        });

        // User Management Search Function
        function searchUsers() {
            const input = document.querySelector('.search-box');
            const filter = input.value.toLowerCase();
            const table = document.getElementById('usersTable');
            const tr = table.getElementsByTagName('tr');
            
            for (let i = 1; i < tr.length; i++) {
                const td = tr[i].getElementsByTagName('td');
                let found = false;
                
                for (let j = 0; j < td.length; j++) {
                    if (td[j]) {
                        if (td[j].textContent.toLowerCase().indexOf(filter) > -1) {
                            found = true;
                            break;
                        }
                    }
                }
                
                tr[i].style.display = found ? '' : 'none';
            }
        }

        // Admin Management Search Function
        function searchAdmins() {
            const input = document.querySelector('.search-box');
            const filter = input.value.toLowerCase();
            const table = document.getElementById('adminsTable');
            const tr = table.getElementsByTagName('tr');
            
            for (let i = 1; i < tr.length; i++) {
                const td = tr[i].getElementsByTagName('td');
                let found = false;
                
                for (let j = 0; j < td.length; j++) {
                    if (td[j]) {
                        if (td[j].textContent.toLowerCase().indexOf(filter) > -1) {
                            found = true;
                            break;
                        }
                    }
                }
                
                tr[i].style.display = found ? '' : 'none';
            }
        }

        function viewUser(userId) {
            alert('Viewing user details for ID: ' + userId + '\nThis would open a detailed view in a real application.');
        }

        function viewAdmin(adminId) {
            alert('Viewing admin details for ID: ' + adminId + '\nThis would open admin profile and permissions in a real application.');
        }

        function openProfileModal() {
            alert('Profile modal would open here with user details and settings.');
        }
    </script>
</body>
</html>