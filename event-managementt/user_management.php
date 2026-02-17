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

// Filter only customers
$customers = array_filter($users, function($user) {
    return $user['role'] === 'customer';
});

// Handle user actions (activate/deactivate/delete)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'] ?? '';
    $action = $_POST['action'] ?? '';
    
    foreach ($users as &$user) {
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
                    $users = array_filter($users, function($u) use ($user_id) {
                        return $u['id'] !== $user_id;
                    });
                    break;
            }
            break;
        }
    }
    
    // Save updated users data
    file_put_contents($users_file, json_encode(array_values($users), JSON_PRETTY_PRINT));
    header('Location: user_management.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Management</title>
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
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
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
            font-size: 60px;
            margin-bottom: 15px;
        }
        
        .profile-name {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .profile-email {
            color: #bdc3c7;
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
        
        .menu-item.active {
            background: rgba(255,255,255,0.1);
            border-left: 4px solid #3498db;
        }
        
        .menu-item:hover {
            background: rgba(255,255,255,0.1);
            border-left: 4px solid #3498db;
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
            display: flex;
            justify-content: between;
            align-items: center;
        }
        
        .header h1 {
            color: #2c3e50;
            font-size: 28px;
        }
        
        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .stat-number {
            font-size: 2em;
            font-weight: bold;
            color: #3498db;
            margin-bottom: 5px;
        }
        
        .stat-label {
            color: #7f8c8d;
            font-size: 14px;
        }
        
        /* Users Table */
        .users-table-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .table-header {
            padding: 25px 30px;
            border-bottom: 1px solid #ecf0f1;
            display: flex;
            justify-content: between;
            align-items: center;
        }
        
        .table-header h2 {
            color: #2c3e50;
            font-size: 22px;
        }
        
        .search-box {
            padding: 10px 15px;
            border: 2px solid #ecf0f1;
            border-radius: 8px;
            width: 300px;
        }
        
        .users-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .users-table th {
            background: #f8f9fa;
            padding: 15px 20px;
            text-align: left;
            font-weight: bold;
            color: #2c3e50;
            border-bottom: 2px solid #ecf0f1;
        }
        
        .users-table td {
            padding: 15px 20px;
            border-bottom: 1px solid #ecf0f1;
        }
        
        .users-table tr:hover {
            background: #f8f9fa;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #3498db;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }
        
        .user-details h4 {
            color: #2c3e50;
            margin-bottom: 5px;
        }
        
        .user-details p {
            color: #7f8c8d;
            font-size: 12px;
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
        
        .action-buttons {
            display: flex;
            gap: 8px;
        }
        
        .btn {
            padding: 6px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 12px;
            font-weight: bold;
            transition: all 0.3s;
        }
        
        .btn-view {
            background: #3498db;
            color: white;
            text-decoration: none;
        }
        
        .btn-view:hover {
            background: #2980b9;
        }
        
        .btn-activate {
            background: #28a745;
            color: white;
        }
        
        .btn-activate:hover {
            background: #218838;
        }
        
        .btn-deactivate {
            background: #ffc107;
            color: #212529;
        }
        
        .btn-deactivate:hover {
            background: #e0a800;
        }
        
        .btn-delete {
            background: #dc3545;
            color: white;
        }
        
        .btn-delete:hover {
            background: #c82333;
        }
        
        .no-users {
            text-align: center;
            padding: 50px;
            color: #7f8c8d;
        }
        
        .no-users i {
            font-size: 50px;
            margin-bottom: 20px;
            display: block;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
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
            <h1>👥 User Management</h1>
        </div>
        
        <!-- Statistics Cards -->
        <div class="stats-cards">
            <div class="stat-card">
                <div class="stat-number"><?php echo count($customers); ?></div>
                <div class="stat-label">Total Users</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">
                    <?php echo count(array_filter($customers, function($user) { 
                        return $user['status'] === 'active'; 
                    })); ?>
                </div>
                <div class="stat-label">Active Users</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">
                    <?php echo count(array_filter($customers, function($user) { 
                        return $user['status'] === 'inactive'; 
                    })); ?>
                </div>
                <div class="stat-label">Inactive Users</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">
                    <?php echo array_sum(array_column($customers, 'login_count')); ?>
                </div>
                <div class="stat-label">Total Logins</div>
            </div>
        </div>
        
        <!-- Users Table -->
        <div class="users-table-container">
            <div class="table-header">
                <h2>Registered Customers</h2>
                <input type="text" class="search-box" placeholder="🔍 Search users..." onkeyup="searchUsers()">
            </div>
            
            <?php if (empty($customers)): ?>
                <div class="no-users">
                    <i>👥</i>
                    <h3>No Customers Registered Yet</h3>
                    <p>Customers will appear here once they register.</p>
                </div>
            <?php else: ?>
                <table class="users-table" id="usersTable">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Contact</th>
                            <th>Registration Date</th>
                            <th>Last Login</th>
                            <th>Login Count</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($customers as $user): ?>
                            <tr>
                                <td>
                                    <div class="user-info">
                                        <div class="user-avatar">
                                            <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
                                        </div>
                                        <div class="user-details">
                                            <h4><?php echo htmlspecialchars($user['name']); ?></h4>
                                            <p>ID: <?php echo $user['id']; ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div style="font-weight: bold;"><?php echo htmlspecialchars($user['email']); ?></div>
                                    <div style="color: #7f8c8d; font-size: 12px;"><?php echo htmlspecialchars($user['phone'] ?? 'N/A'); ?></div>
                                </td>
                                <td><?php echo $user['registration_date']; ?></td>
                                <td><?php echo $user['last_login'] ?? 'Never'; ?></td>
                                <td><?php echo $user['login_count']; ?></td>
                                <td>
                                    <span class="status-badge <?php echo $user['status'] === 'active' ? 'status-active' : 'status-inactive'; ?>">
                                        <?php echo ucfirst($user['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="user_details.php?id=<?php echo $user['id']; ?>" class="btn btn-view">
                                            👁️ View
                                        </a>
                                        
                                        <?php if ($user['status'] === 'active'): ?>
                                            <form method="POST" style="display: inline;">
                                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                <input type="hidden" name="action" value="deactivate">
                                                <button type="submit" class="btn btn-deactivate" 
                                                        onclick="return confirm('Deactivate this user?')">
                                                    ⏸️ Deactivate
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <form method="POST" style="display: inline;">
                                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                <input type="hidden" name="action" value="activate">
                                                <button type="submit" class="btn btn-activate">
                                                    ▶️ Activate
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                        
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                            <input type="hidden" name="action" value="delete">
                                            <button type="submit" class="btn btn-delete" 
                                                    onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                                                🗑️ Delete
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

    <script>
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
    </script>
</body>
</html>