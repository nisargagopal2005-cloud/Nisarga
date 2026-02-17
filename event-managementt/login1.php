<?php
session_start();

// Define users array
$users = [
    [
        'id' => 1,
        'username' => 'rohan',
        'password' => 'rohan123',
        'name' => 'Rohan Sharma',
        'role' => 'System Administrator',
        'email' => 'rohan.sharma@company.com',
        'phone' => '+1 (555) 123-4567',
        'department' => 'IT Administration',
        'profilePic' => 'https://randomuser.me/api/portraits/men/32.jpg'
    ],
    [
        'id' => 2,
        'username' => 'varshan',
        'password' => 'varshan123',
        'name' => 'Varshan Patel',
        'role' => 'Project Manager',
        'email' => 'varshan.patel@company.com',
        'phone' => '+1 (555) 234-5678',
        'department' => 'Project Management',
        'profilePic' => 'https://randomuser.me/api/portraits/men/44.jpg'
    ],
    [
        'id' => 3,
        'username' => 'nisarga',
        'password' => 'nisarga123',
        'name' => 'Nisarga Reddy',
        'role' => 'Senior Developer',
        'email' => 'nisarga.reddy@company.com',
        'phone' => '+1 (555) 345-6789',
        'department' => 'Software Development',
        'profilePic' => 'https://randomuser.me/api/portraits/women/67.jpg'
    ],
    [
        'id' => 4,
        'username' => 'sathya',
        'password' => 'sathya123',
        'name' => 'Sathya Kumar',
        'role' => 'UI/UX Designer',
        'email' => 'sathya.kumar@company.com',
        'phone' => '+1 (555) 456-7890',
        'department' => 'Design Team',
        'profilePic' => 'https://randomuser.me/api/portraits/women/68.jpg'
    ]
];

// Handle AJAX login request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    $user = null;
    foreach ($users as $u) {
        if ($u['username'] === $username && $u['password'] === $password) {
            $user = $u;
            break;
        }
    }
    
    if ($user) {
        $_SESSION['user'] = $user;
        $_SESSION['login_time'] = time();
        $_SESSION['logged_in'] = true;
        
        echo json_encode([
            'success' => true,
            'user' => $user
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid username or password'
        ]);
    }
    exit;
}

// Handle logout request
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    echo json_encode(['success' => true]);
    exit;
}

// Handle get users request
if (isset($_GET['action']) && $_GET['action'] === 'getUsers') {
    echo json_encode($users);
    exit;
}
?>