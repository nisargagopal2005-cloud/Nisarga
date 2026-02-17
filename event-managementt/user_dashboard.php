<?php
// Turn off error display for production
error_reporting(0);
ini_set('display_errors', 0);

session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    // Check if we're already on login page to avoid loop
    $current_page = basename($_SERVER['PHP_SELF']);
    if ($current_page != 'user_login.php') {
        header('Location: user_login.php');
        exit;
    }
}

require_once __DIR__.'/inc/db.php';

// Fetch user details from database
$stmt = $pdo->prepare('SELECT name, email, created_at FROM users WHERE id = ? LIMIT 1');
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user) {
    // User not found in database, logout
    session_destroy();
    header('Location: user_login.php');
    exit;
}

$success = '';
$error = '';

// Determine which section to show
$current_section = 'welcome';
if (isset($_GET['section'])) {
    $current_section = $_GET['section'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>User Dashboard - EventSphere</title>
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

/* Top Navigation Bar */
.top-nav {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    z-index: 1000;
    padding: 1rem 2rem;
    box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
}

.nav-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    max-width: 1400px;
    margin: 0 auto;
}

.logo {
    display: flex;
    align-items: center;
    gap: 10px;
    color: #333;
    font-size: 1.8rem;
    font-weight: 700;
}

.logo i {
    color: var(--primary);
    font-size: 2rem;
}

/* Top Navigation Menu */
.top-nav-menu {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-left: 3rem;
}

.top-nav-item {
    padding: 8px 16px;
    background: transparent;
    border: none;
    color: #555;
    text-decoration: none;
    font-weight: 600;
    transition: var(--transition);
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    border-radius: 20px;
    position: relative;
}

.top-nav-item:hover {
    background: rgba(255, 107, 107, 0.1);
    color: var(--primary);
    transform: translateY(-2px);
}

.top-nav-item.active {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: white;
    box-shadow: 0 4px 15px rgba(255, 107, 107, 0.3);
}

.top-nav-item i {
    font-size: 0.9rem;
}

.user-menu {
    display: flex;
    align-items: center;
    gap: 20px;
}

.user-info {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 8px 16px;
    background: rgba(255, 107, 107, 0.1);
    border-radius: 25px;
    border: 1px solid rgba(255, 107, 107, 0.2);
}

.user-avatar {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    color: white;
    font-size: 1rem;
}

.user-name {
    color: #333;
    font-weight: 600;
    font-size: 0.9rem;
}

.logout-btn {
    background: var(--primary);
    border: none;
    color: white;
    padding: 8px 16px;
    border-radius: 20px;
    cursor: pointer;
    transition: var(--transition);
    display: flex;
    align-items: center;
    gap: 6px;
    font-weight: 600;
    font-size: 0.9rem;
}

.logout-btn:hover {
    background: #ff5252;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255, 107, 107, 0.3);
}

/* Main container */
.main-container {
    margin-top: 80px;
    padding: 2rem;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    min-height: calc(100vh - 80px);
}

/* Enhanced Dashboard Container */
.dashboard-container {
    background: rgba(255, 255, 255, 0.92);
    backdrop-filter: blur(15px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    padding: 3rem;
    border-radius: 25px;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
    width: 100%;
    max-width: 1300px;
    animation: fadeInUp 0.8s ease;
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

/* Header Styles */
.dashboard-header {
    text-align: center;
    margin-bottom: 3rem;
    padding-bottom: 2rem;
    border-bottom: 2px solid rgba(0, 0, 0, 0.08);
}

.profile-emoji {
    font-size: 5rem;
    margin-bottom: 1.5rem;
    display: block;
    animation: bounce 2s ease-in-out infinite;
}

@keyframes bounce {
    0%, 100% { transform: translateY(0) scale(1); }
    50% { transform: translateY(-10px) scale(1.05); }
}

h1 {
    color: #222;
    font-size: 3rem;
    margin-bottom: 0.8rem;
    font-weight: 800;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
}

.subtitle {
    color: #666;
    font-size: 1.3rem;
    font-weight: 400;
    line-height: 1.6;
}

/* Content Sections */
.content-section {
    display: none;
    animation: fadeIn 0.6s ease;
}

.content-section.active {
    display: block;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Enhanced Welcome Section */
.welcome-content {
    text-align: center;
    padding: 2rem;
}

.welcome-content h2 {
    color: #222;
    font-size: 2.5rem;
    margin-bottom: 1.5rem;
    font-weight: 700;
}

.welcome-content .subtitle {
    color: #666;
    font-size: 1.4rem;
    margin-bottom: 3rem;
    max-width: 700px;
    margin-left: auto;
    margin-right: auto;
}

.login-info {
    background: linear-gradient(135deg, rgba(255, 107, 107, 0.1), rgba(106, 17, 203, 0.1));
    padding: 2rem 3rem;
    border-radius: 20px;
    display: inline-block;
    font-size: 1.2rem;
    color: #333;
    font-weight: 600;
    margin-bottom: 3rem;
    border: 1px solid rgba(255, 107, 107, 0.2);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    transition: var(--transition);
}

.login-info:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
}

.login-info .user-email {
    color: var(--primary);
    font-weight: bold;
}

.user-stats {
    display: flex;
    justify-content: center;
    gap: 20px;
    margin-bottom: 3rem;
    flex-wrap: wrap;
}

.user-stat {
    background: white;
    padding: 1.5rem 2rem;
    border-radius: 15px;
    text-align: center;
    border: 1px solid #e8e8e8;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.06);
    transition: var(--transition);
}

.user-stat:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.user-stat .label {
    color: #666;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
}

.user-stat .value {
    color: #222;
    font-size: 1.3rem;
    font-weight: 700;
}

/* Enhanced Stats Cards */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 2rem;
    margin-top: 3rem;
}

.stat-card {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(255, 255, 255, 0.85));
    border-radius: 20px;
    padding: 2.5rem 2rem;
    text-align: center;
    border: 1px solid rgba(255, 255, 255, 0.5);
    transition: var(--transition);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    position: relative;
    overflow: hidden;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 4px;
    background: linear-gradient(90deg, var(--primary), var(--secondary));
}

.stat-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.stat-icon {
    font-size: 3rem;
    margin-bottom: 1.5rem;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
}

.stat-number {
    font-size: 3rem;
    font-weight: 800;
    color: #222;
    margin-bottom: 0.8rem;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
}

.stat-label {
    color: #666;
    font-size: 1.1rem;
    font-weight: 500;
    line-height: 1.4;
}

/* Enhanced Section Content */
.section-content {
    padding: 3rem;
    text-align: center;
}

.section-content h2 {
    color: #222;
    margin-bottom: 2rem;
    font-size: 2.5rem;
    font-weight: 700;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
}

.section-content p {
    font-size: 1.2rem;
    color: #666;
    text-align: center;
    line-height: 1.7;
    max-width: 700px;
    margin: 0 auto 3rem;
}

.feature-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    margin-top: 3rem;
}

.feature-card {
    background: white;
    padding: 2.5rem 2rem;
    border-radius: 20px;
    text-align: center;
    border: 1px solid #e8e8e8;
    transition: var(--transition);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
    cursor: pointer;
    position: relative;
    overflow: hidden;
}

.feature-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
}

.feature-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 4px;
    background: linear-gradient(90deg, var(--primary), var(--secondary));
}

.feature-icon {
    font-size: 3rem;
    margin-bottom: 1.5rem;
    color: var(--primary);
}

.feature-title {
    font-size: 1.4rem;
    font-weight: 700;
    color: #222;
    margin-bottom: 1rem;
}

.feature-description {
    color: #666;
    line-height: 1.6;
    margin-bottom: 1.5rem;
}

.feature-action {
    background: var(--primary);
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 25px;
    cursor: pointer;
    transition: var(--transition);
    font-weight: 600;
}

.feature-action:hover {
    background: #ff5252;
    transform: translateY(-2px);
}

/* Success and Error Messages */
.success {
    margin: 2rem 0;
    color: #4CAF50;
    font-weight: bold;
    padding: 1.5rem;
    background: rgba(76, 175, 80, 0.1);
    border-radius: 15px;
    text-align: center;
    border: 1px solid rgba(76, 175, 80, 0.2);
    box-shadow: 0 5px 20px rgba(76, 175, 80, 0.1);
}

.error {
    margin: 2rem 0;
    color: #f44336;
    font-weight: bold;
    padding: 1.5rem;
    background: rgba(244, 67, 54, 0.1);
    border-radius: 15px;
    text-align: center;
    border: 1px solid rgba(244, 67, 54, 0.2);
    box-shadow: 0 5px 20px rgba(244, 67, 54, 0.1);
}

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    z-index: 2000;
    backdrop-filter: blur(5px);
    opacity: 0;
    transition: opacity 0.5s ease;
}

.modal.active {
    display: flex;
    opacity: 1;
}

.modal-content {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(255, 255, 255, 0.9));
    margin: auto;
    padding: 2.5rem;
    border-radius: 20px;
    max-width: 800px;
    width: 90%;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
    position: relative;
    transform: scale(0.8);
    transition: transform 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    border: 1px solid rgba(255, 255, 255, 0.4);
    backdrop-filter: blur(10px);
    max-height: 80vh;
    overflow-y: auto;
}

.modal.active .modal-content {
    transform: scale(1);
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding-bottom: 1.5rem;
    border-bottom: 2px solid rgba(0, 0, 0, 0.1);
}

.modal-title {
    font-size: 2rem;
    background: linear-gradient(90deg, var(--primary), var(--secondary));
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    margin: 0;
    font-weight: 700;
}

.close-modal {
    background: none;
    border: none;
    font-size: 2rem;
    cursor: pointer;
    color: var(--dark);
    transition: var(--transition);
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
}

.close-modal:hover {
    background: var(--primary);
    color: white;
    transform: rotate(90deg);
}

.modal-body {
    font-size: 1.1rem;
    line-height: 1.7;
    color: #555;
}

.modal-section {
    margin-bottom: 2rem;
    padding: 1.5rem;
    background: rgba(255, 255, 255, 0.6);
    border-radius: 15px;
    border-left: 4px solid var(--primary);
}

.modal-section h3 {
    color: #222;
    margin-bottom: 1rem;
    font-size: 1.4rem;
    font-weight: 600;
}

.modal-section p {
    margin-bottom: 1rem;
    color: #666;
}

.modal-features {
    list-style: none;
    margin: 1.5rem 0;
}

.modal-features li {
    padding: 0.8rem 0;
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    position: relative;
    padding-left: 2rem;
    font-size: 1rem;
}

.modal-features li:last-child {
    border-bottom: none;
}

.modal-features li::before {
    content: '✓';
    position: absolute;
    left: 0;
    color: var(--accent);
    font-weight: bold;
    font-size: 1.2rem;
}

/* Enhanced Bookings Section */
.bookings-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 2rem;
    margin-top: 2rem;
}

.booking-card {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    border: 1px solid #e8e8e8;
    transition: var(--transition);
    position: relative;
    overflow: hidden;
    cursor: pointer;
}

.booking-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
}

.booking-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 4px;
    background: linear-gradient(90deg, var(--primary), var(--secondary));
}

.booking-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
}

.booking-title {
    font-size: 1.4rem;
    font-weight: 700;
    color: #222;
}

.booking-status {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-confirmed {
    background: rgba(76, 175, 80, 0.1);
    color: #4CAF50;
    border: 1px solid rgba(76, 175, 80, 0.3);
}

.status-pending {
    background: rgba(255, 193, 7, 0.1);
    color: #FFC107;
    border: 1px solid rgba(255, 193, 7, 0.3);
}

.status-cancelled {
    background: rgba(244, 67, 54, 0.1);
    color: #f44336;
    border: 1px solid rgba(244, 67, 54, 0.3);
}

.booking-details {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.booking-detail {
    display: flex;
    flex-direction: column;
    gap: 0.3rem;
}

.detail-label {
    font-size: 0.8rem;
    color: #666;
    font-weight: 500;
}

.detail-value {
    font-size: 1rem;
    color: #222;
    font-weight: 600;
}

.booking-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
}

.booking-action {
    padding: 8px 16px;
    border-radius: 20px;
    border: none;
    cursor: pointer;
    font-weight: 600;
    font-size: 0.9rem;
    transition: var(--transition);
}

.action-primary {
    background: var(--primary);
    color: white;
}

.action-primary:hover {
    background: #ff5252;
    transform: translateY(-2px);
}

.action-secondary {
    background: transparent;
    color: #666;
    border: 1px solid #ddd;
}

.action-secondary:hover {
    background: #f5f5f5;
    transform: translateY(-2px);
}

/* Responsive Design */
@media (max-width: 1200px) {
    .top-nav-menu {
        display: none;
    }
}

@media (max-width: 1024px) {
    h1 {
        font-size: 2.5rem;
    }
    
    .stats-grid {
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    }
    
    .bookings-container {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .top-nav {
        padding: 1rem;
    }
    
    .nav-content {
        flex-direction: column;
        gap: 1rem;
    }
    
    .main-container {
        margin-top: 120px;
        padding: 1rem;
    }
    
    .dashboard-container {
        padding: 2rem 1.5rem;
    }
    
    h1 {
        font-size: 2rem;
    }
    
    .profile-emoji {
        font-size: 4rem;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .user-stats {
        flex-direction: column;
        align-items: center;
    }
    
    .feature-grid {
        grid-template-columns: 1fr;
    }
    
    .booking-details {
        grid-template-columns: 1fr;
    }
    
    .modal-content {
        padding: 1.5rem;
        margin: 1rem;
    }
}

@media (max-width: 480px) {
    .dashboard-container {
        padding: 1.5rem 1rem;
    }
    
    h1 {
        font-size: 1.8rem;
    }
    
    .profile-emoji {
        font-size: 3rem;
    }
    
    .login-info {
        padding: 1.5rem 2rem;
        font-size: 1rem;
    }
    
    .user-info {
        flex-direction: column;
        gap: 8px;
        text-align: center;
    }
    
    .booking-actions {
        flex-direction: column;
    }
}
</style>
</head>
<body>
    <!-- Background Elements -->
    <div class="bg-image"></div>
    <div class="bg-overlay"></div>

    <!-- Top Navigation Bar -->
    <nav class="top-nav">
        <div class="nav-content">
            <div class="logo">
                <i class="fas fa-calendar-alt"></i>
                <span>EventSphere</span>
            </div>
            
            <!-- Top Navigation Menu -->
            <div class="top-nav-menu">
                <button class="top-nav-item <?php echo $current_section === 'welcome' ? 'active' : ''; ?>" onclick="showSection('welcome')">
                    <i class="fas fa-home"></i> Dashboard
                </button>
                <button class="top-nav-item <?php echo $current_section === 'profile' ? 'active' : ''; ?>" onclick="showSection('profile')">
                    <i class="fas fa-user-cog"></i> Profile
                </button>
                <button class="top-nav-item <?php echo $current_section === 'booked_events' ? 'active' : ''; ?>" onclick="showSection('booked_events')">
                    <i class="fas fa-calendar-check"></i> Bookings
                </button>
                <button class="top-nav-item <?php echo $current_section === 'payments' ? 'active' : ''; ?>" onclick="showSection('payments')">
                    <i class="fas fa-credit-card"></i> Payments
                </button>
                <button class="top-nav-item <?php echo $current_section === 'notifications' ? 'active' : ''; ?>" onclick="showSection('notifications')">
                    <i class="fas fa-bell"></i> Notifications
                </button>
                <button class="top-nav-item <?php echo $current_section === 'settings' ? 'active' : ''; ?>" onclick="showSection('settings')">
                    <i class="fas fa-cog"></i> Settings
                </button>
            </div>

            <div class="user-menu">
                <div class="user-info">
                    <div class="user-avatar">
                        <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
                    </div>
                    <div class="user-name">
                        <?php echo htmlspecialchars($user['name']); ?>
                    </div>
                </div>
                <form method="POST" action="user_logout.php" style="display: inline;">
                    <button type="submit" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="main-container">
        <div class="dashboard-container">
            <!-- Header -->
            <div class="dashboard-header">
                <span class="profile-emoji">👤</span>
                <h1>User Dashboard</h1>
                <p class="subtitle">Welcome to your personalized EventSphere dashboard. Manage your events, bookings, and preferences all in one place.</p>
            </div>

            <!-- Messages -->
            <?php
            if (isset($_SESSION['success'])) {
                echo '<div class="success">' . $_SESSION['success'] . '</div>';
                unset($_SESSION['success']);
            }
            if (isset($_SESSION['error'])) {
                echo '<div class="error">' . $_SESSION['error'] . '</div>';
                unset($_SESSION['error']);
            }
            ?>

            <!-- Content Sections -->
            <div class="content-sections">
                <!-- Welcome Section -->
                <div id="welcome-section" class="content-section <?php echo $current_section === 'welcome' ? 'active' : ''; ?>">
                    <div class="welcome-content">
                        <h2>Welcome back, <?php echo htmlspecialchars($user['name']); ?>! 🎉</h2>
                        <div class="subtitle">Your EventSphere dashboard is ready for amazing experiences! Here's your personalized overview.</div>
                        
                        <div class="login-info">
                            <strong>📧 Account Details:</strong><br>
                            <span class="user-email"><?php echo htmlspecialchars($user['email']); ?></span><br>
                            <small>Member since: <?php echo date('F Y', strtotime($user['created_at'])); ?></small>
                        </div>

                        <div class="user-stats">
                            <div class="user-stat">
                                <div class="label">Member Since</div>
                                <div class="value"><?php echo date('M Y', strtotime($user['created_at'])); ?></div>
                            </div>
                            <div class="user-stat">
                                <div class="label">Account Status</div>
                                <div class="value">Active ✅</div>
                            </div>
                            <div class="user-stat">
                                <div class="label">User Level</div>
                                <div class="value">Premium 🏆</div>
                            </div>
                        </div>

                        <div class="stats-grid">
                            <div class="stat-card">
                                <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
                                <div class="stat-number">3</div>
                                <div class="stat-label">Upcoming Events You're Attending</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-icon"><i class="fas fa-ticket-alt"></i></div>
                                <div class="stat-number">8</div>
                                <div class="stat-label">Total Event Bookings This Year</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-icon"><i class="fas fa-star"></i></div>
                                <div class="stat-number">4.8</div>
                                <div class="stat-label">Your Average Event Rating</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-icon"><i class="fas fa-users"></i></div>
                                <div class="stat-number">15</div>
                                <div class="stat-label">EventSphere Friends & Connections</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Profile Section -->
                <div id="profile-section" class="content-section <?php echo $current_section === 'profile' ? 'active' : ''; ?>">
                    <div class="section-content">
                        <h2>👤 Profile Management</h2>
                        <p>Update your personal information and preferences to enhance your EventSphere experience</p>
                        
                        <div class="feature-grid">
                            <div class="feature-card" onclick="openModal('personal-info')">
                                <div class="feature-icon"><i class="fas fa-user-edit"></i></div>
                                <div class="feature-title">Personal Information</div>
                                <div class="feature-description">Update your name, contact details, and personal preferences to keep your profile current.</div>
                                <button class="feature-action">View Details</button>
                            </div>
                            <div class="feature-card" onclick="openModal('security-settings')">
                                <div class="feature-icon"><i class="fas fa-shield-alt"></i></div>
                                <div class="feature-title">Security Settings</div>
                                <div class="feature-description">Manage your password, two-factor authentication, and account security preferences.</div>
                                <button class="feature-action">View Details</button>
                            </div>
                            <div class="feature-card" onclick="openModal('notification-prefs')">
                                <div class="feature-icon"><i class="fas fa-bell"></i></div>
                                <div class="feature-title">Notification Preferences</div>
                                <div class="feature-description">Customize how and when you receive notifications about events and updates.</div>
                                <button class="feature-action">View Details</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Booked Events Section -->
                <div id="booked-events-section" class="content-section <?php echo $current_section === 'booked_events' ? 'active' : ''; ?>">
                    <div class="section-content">
                        <h2>🎫 My Booked Events</h2>
                        <p>Manage your event registrations, view upcoming events, and track your booking history</p>
                        
                        <div class="bookings-container">
                            <div class="booking-card" onclick="openModal('summer-festival')">
                                <div class="booking-header">
                                    <div class="booking-title">Summer Music Festival 2024</div>
                                    <div class="booking-status status-confirmed">Confirmed</div>
                                </div>
                                <div class="booking-details">
                                    <div class="booking-detail">
                                        <div class="detail-label">Date & Time</div>
                                        <div class="detail-value">June 15-17, 2024 • 2:00 PM</div>
                                    </div>
                                    <div class="booking-detail">
                                        <div class="detail-label">Location</div>
                                        <div class="detail-value">Central Park, New York</div>
                                    </div>
                                    <div class="booking-detail">
                                        <div class="detail-label">Ticket Type</div>
                                        <div class="detail-value">VIP Pass (3 Days)</div>
                                    </div>
                                    <div class="booking-detail">
                                        <div class="detail-label">Total Amount</div>
                                        <div class="detail-value">$299.00</div>
                                    </div>
                                </div>
                                <div class="booking-actions">
                                    <button class="booking-action action-primary">View Details</button>
                                    <button class="booking-action action-secondary">Download Ticket</button>
                                </div>
                            </div>
                            
                            <div class="booking-card" onclick="openModal('tech-conference')">
                                <div class="booking-header">
                                    <div class="booking-title">Tech Innovation Summit 2024</div>
                                    <div class="booking-status status-confirmed">Confirmed</div>
                                </div>
                                <div class="booking-details">
                                    <div class="booking-detail">
                                        <div class="detail-label">Date & Time</div>
                                        <div class="detail-value">July 22-24, 2024 • 9:00 AM</div>
                                    </div>
                                    <div class="booking-detail">
                                        <div class="detail-label">Location</div>
                                        <div class="detail-value">Convention Center, San Francisco</div>
                                    </div>
                                    <div class="booking-detail">
                                        <div class="detail-label">Ticket Type</div>
                                        <div class="detail-value">Professional Pass</div>
                                    </div>
                                    <div class="booking-detail">
                                        <div class="detail-label">Total Amount</div>
                                        <div class="detail-value">$450.00</div>
                                    </div>
                                </div>
                                <div class="booking-actions">
                                    <button class="booking-action action-primary">View Details</button>
                                    <button class="booking-action action-secondary">Download Ticket</button>
                                </div>
                            </div>
                            
                            <div class="booking-card" onclick="openModal('food-festival')">
                                <div class="booking-header">
                                    <div class="booking-title">International Food Festival</div>
                                    <div class="booking-status status-pending">Pending</div>
                                </div>
                                <div class="booking-details">
                                    <div class="booking-detail">
                                        <div class="detail-label">Date & Time</div>
                                        <div class="detail-value">August 5, 2024 • 11:00 AM</div>
                                    </div>
                                    <div class="booking-detail">
                                        <div class="detail-label">Location</div>
                                        <div class="detail-value">Waterfront Park, Chicago</div>
                                    </div>
                                    <div class="booking-detail">
                                        <div class="detail-label">Ticket Type</div>
                                        <div class="detail-value">General Admission</div>
                                    </div>
                                    <div class="booking-detail">
                                        <div class="detail-label">Total Amount</div>
                                        <div class="detail-value">$75.00</div>
                                    </div>
                                </div>
                                <div class="booking-actions">
                                    <button class="booking-action action-primary">View Details</button>
                                    <button class="booking-action action-secondary">Payment Pending</button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="feature-grid" style="margin-top: 3rem;">
                            <div class="feature-card" onclick="openModal('event-history')">
                                <div class="feature-icon"><i class="fas fa-history"></i></div>
                                <div class="feature-title">Event History</div>
                                <div class="feature-description">Review your past event attendance and relive your favorite EventSphere moments.</div>
                                <button class="feature-action">View History</button>
                            </div>
                            <div class="feature-card" onclick="openModal('rate-events')">
                                <div class="feature-icon"><i class="fas fa-star"></i></div>
                                <div class="feature-title">Rate Events</div>
                                <div class="feature-description">Share your experience by rating events you've attended and help others choose.</div>
                                <button class="feature-action">Rate Events</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payments Section -->
                <div id="payments-section" class="content-section <?php echo $current_section === 'payments' ? 'active' : ''; ?>">
                    <div class="section-content">
                        <h2>💳 Payment History</h2>
                        <p>View and manage your payment transactions, invoices, and billing information</p>
                        
                        <div class="feature-grid">
                            <div class="feature-card" onclick="openModal('transaction-history')">
                                <div class="feature-icon"><i class="fas fa-receipt"></i></div>
                                <div class="feature-title">Transaction History</div>
                                <div class="feature-description">View detailed records of all your EventSphere payments and transactions.</div>
                                <button class="feature-action">View Details</button>
                            </div>
                            <div class="feature-card" onclick="openModal('payment-methods')">
                                <div class="feature-icon"><i class="fas fa-credit-card"></i></div>
                                <div class="feature-title">Payment Methods</div>
                                <div class="feature-description">Manage your saved payment methods for faster and more secure bookings.</div>
                                <button class="feature-action">View Details</button>
                            </div>
                            <div class="feature-card" onclick="openModal('invoices-receipts')">
                                <div class="feature-icon"><i class="fas fa-file-invoice"></i></div>
                                <div class="feature-title">Invoices & Receipts</div>
                                <div class="feature-description">Download and manage your event invoices and payment receipts.</div>
                                <button class="feature-action">View Details</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notifications Section -->
                <div id="notifications-section" class="content-section <?php echo $current_section === 'notifications' ? 'active' : ''; ?>">
                    <div class="section-content">
                        <h2>🔔 Notifications</h2>
                        <p>Stay updated with your event alerts, reminders, and important announcements</p>
                        
                        <div class="feature-grid">
                            <div class="feature-card" onclick="openModal('message-center')">
                                <div class="feature-icon"><i class="fas fa-envelope"></i></div>
                                <div class="feature-title">Message Center</div>
                                <div class="feature-description">Read and manage your EventSphere messages and communications.</div>
                                <button class="feature-action">View Details</button>
                            </div>
                            <div class="feature-card" onclick="openModal('event-reminders')">
                                <div class="feature-icon"><i class="fas fa-calendar-day"></i></div>
                                <div class="feature-title">Event Reminders</div>
                                <div class="feature-description">Configure how you receive reminders for your upcoming events.</div>
                                <button class="feature-action">View Details</button>
                            </div>
                            <div class="feature-card" onclick="openModal('announcements')">
                                <div class="feature-icon"><i class="fas fa-bullhorn"></i></div>
                                <div class="feature-title">Announcements</div>
                                <div class="feature-description">Stay informed with the latest EventSphere news and updates.</div>
                                <button class="feature-action">View Details</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Settings Section -->
                <div id="settings-section" class="content-section <?php echo $current_section === 'settings' ? 'active' : ''; ?>">
                    <div class="section-content">
                        <h2>⚙️ Settings</h2>
                        <p>Customize your EventSphere experience with personalized settings and preferences</p>
                        
                        <div class="feature-grid">
                            <div class="feature-card" onclick="openModal('appearance')">
                                <div class="feature-icon"><i class="fas fa-palette"></i></div>
                                <div class="feature-title">Appearance</div>
                                <div class="feature-description">Customize the look and feel of your EventSphere dashboard and interface.</div>
                                <button class="feature-action">View Details</button>
                            </div>
                            <div class="feature-card" onclick="openModal('language-region')">
                                <div class="feature-icon"><i class="fas fa-language"></i></div>
                                <div class="feature-title">Language & Region</div>
                                <div class="feature-description">Set your preferred language, timezone, and regional preferences.</div>
                                <button class="feature-action">View Details</button>
                            </div>
                            <div class="feature-card" onclick="openModal('privacy-data')">
                                <div class="feature-icon"><i class="fas fa-database"></i></div>
                                <div class="feature-title">Privacy & Data</div>
                                <div class="feature-description">Manage your privacy settings and data sharing preferences.</div>
                                <button class="feature-action">View Details</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Popups -->
    <!-- Profile Modals -->
    <div id="personal-info-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Personal Information</h2>
                <button class="close-modal" onclick="closeModal('personal-info')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="modal-section">
                    <h3>Account Details</h3>
                    <p><strong>Full Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
                    <p><strong>Email Address:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                    <p><strong>Member Since:</strong> <?php echo date('F j, Y', strtotime($user['created_at'])); ?></p>
                    <p><strong>Account Type:</strong> Premium User</p>
                </div>
                
                <div class="modal-section">
                    <h3>Contact Information</h3>
                    <p><strong>Phone Number:</strong> +1 (555) 123-4567</p>
                    <p><strong>Address:</strong> 123 Main Street, New York, NY 10001</p>
                    <p><strong>Emergency Contact:</strong> Jane Smith - +1 (555) 987-6543</p>
                </div>
                
                <div class="modal-section">
                    <h3>Preferences</h3>
                    <ul class="modal-features">
                        <li>Receive event recommendations based on interests</li>
                        <li>Email notifications for new events in your area</li>
                        <li>SMS reminders for upcoming events</li>
                        <li>Personalized event suggestions</li>
                        <li>Monthly newsletter subscription</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div id="security-settings-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Security Settings</h2>
                <button class="close-modal" onclick="closeModal('security-settings')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="modal-section">
                    <h3>Account Security</h3>
                    <p><strong>Last Login:</strong> Today, 2:30 PM from New York, NY</p>
                    <p><strong>Password Last Changed:</strong> 3 months ago</p>
                    <p><strong>Two-Factor Authentication:</strong> Enabled</p>
                    <p><strong>Security Questions:</strong> Configured</p>
                </div>
                
                <div class="modal-section">
                    <h3>Security Features</h3>
                    <ul class="modal-features">
                        <li>Two-factor authentication enabled</li>
                        <li>Login notifications active</li>
                        <li>Secure password requirements enforced</li>
                        <li>Session timeout: 30 minutes</li>
                        <li>Device management enabled</li>
                        <li>Automatic logout on suspicious activity</li>
                    </ul>
                </div>
                
                <div class="modal-section">
                    <h3>Active Sessions</h3>
                    <p><strong>Current Device:</strong> Chrome on Windows - Active</p>
                    <p><strong>Mobile App:</strong> iOS App - Last used 2 days ago</p>
                    <p><strong>Tablet:</strong> iPad - Last used 1 week ago</p>
                </div>
            </div>
        </div>
    </div>

    <div id="notification-prefs-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Notification Preferences</h2>
                <button class="close-modal" onclick="closeModal('notification-prefs')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="modal-section">
                    <h3>Email Notifications</h3>
                    <ul class="modal-features">
                        <li>Event reminders - 24 hours before</li>
                        <li>New event announcements</li>
                        <li>Special offers and discounts</li>
                        <li>Monthly event digest</li>
                        <li>Booking confirmations</li>
                    </ul>
                </div>
                
                <div class="modal-section">
                    <h3>Push Notifications</h3>
                    <ul class="modal-features">
                        <li>Event starting soon alerts</li>
                        <li>Important event updates</li>
                        <li>Friend activity notifications</li>
                        <li>Last-minute ticket availability</li>
                    </ul>
                </div>
                
                <div class="modal-section">
                    <h3>SMS Alerts</h3>
                    <ul class="modal-features">
                        <li>Critical event changes</li>
                        <li>Emergency announcements</li>
                        <li>Weather-related updates</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Bookings Modals -->
    <div id="summer-festival-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Summer Music Festival 2024</h2>
                <button class="close-modal" onclick="closeModal('summer-festival')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="modal-section">
                    <h3>Event Details</h3>
                    <p><strong>Date:</strong> June 15-17, 2024</p>
                    <p><strong>Time:</strong> 2:00 PM - 11:00 PM Daily</p>
                    <p><strong>Location:</strong> Central Park, New York</p>
                    <p><strong>Venue:</strong> Great Lawn Area</p>
                    <p><strong>Organizer:</strong> Music Festivals Inc.</p>
                </div>
                
                <div class="modal-section">
                    <h3>Booking Information</h3>
                    <p><strong>Ticket Type:</strong> VIP Pass (3 Days)</p>
                    <p><strong>Seat/Section:</strong> VIP Area A, Row 5</p>
                    <p><strong>Booking Reference:</strong> ES-MF2024-VIP-789123</p>
                    <p><strong>Booking Date:</strong> March 15, 2024</p>
                    <p><strong>Guests:</strong> 2 Adults</p>
                </div>
                
                <div class="modal-section">
                    <h3>What's Included</h3>
                    <ul class="modal-features">
                        <li>Access to all 3 days of the festival</li>
                        <li>VIP lounge access with complimentary drinks</li>
                        <li>Priority entry and separate security line</li>
                        <li>Commemorative festival merchandise pack</li>
                        <li>Meet & greet opportunities with artists</li>
                        <li>Premium viewing areas at all stages</li>
                        <li>Dedicated VIP parking</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div id="tech-conference-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Tech Innovation Summit 2024</h2>
                <button class="close-modal" onclick="closeModal('tech-conference')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="modal-section">
                    <h3>Event Details</h3>
                    <p><strong>Date:</strong> July 22-24, 2024</p>
                    <p><strong>Time:</strong> 9:00 AM - 6:00 PM Daily</p>
                    <p><strong>Location:</strong> Moscone Center, San Francisco</p>
                    <p><strong>Venue:</strong> South Hall, Room 201</p>
                    <p><strong>Organizer:</strong> Tech Events Global</p>
                </div>
                
                <div class="modal-section">
                    <h3>Booking Information</h3>
                    <p><strong>Ticket Type:</strong> Professional Pass</p>
                    <p><strong>Access Level:</strong> Full Conference + Workshops</p>
                    <p><strong>Booking Reference:</strong> ES-TIS2024-PRO-456789</p>
                    <p><strong>Booking Date:</strong> April 10, 2024</p>
                    <p><strong>Workshops Selected:</strong> AI Integration, Cloud Security</p>
                </div>
                
                <div class="modal-section">
                    <h3>Conference Benefits</h3>
                    <ul class="modal-features">
                        <li>Access to all keynote sessions</li>
                        <li>Networking events with industry leaders</li>
                        <li>Workshop participation (2 included)</li>
                        <li>Conference materials and swag bag</li>
                        <li>Lunch and refreshments provided</li>
                        <li>Access to exclusive after-parties</li>
                        <li>Digital certificate of participation</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div id="food-festival-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">International Food Festival</h2>
                <button class="close-modal" onclick="closeModal('food-festival')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="modal-section">
                    <h3>Event Details</h3>
                    <p><strong>Date:</strong> August 5, 2024</p>
                    <p><strong>Time:</strong> 11:00 AM - 9:00 PM</p>
                    <p><strong>Location:</strong> Navy Pier, Chicago</p>
                    <p><strong>Venue:</strong> Festival Grounds</p>
                    <p><strong>Organizer:</strong> Chicago Food Events</p>
                </div>
                
                <div class="modal-section">
                    <h3>Booking Information</h3>
                    <p><strong>Ticket Type:</strong> General Admission</p>
                    <p><strong>Status:</strong> Payment Pending</p>
                    <p><strong>Booking Reference:</strong> ES-IFF2024-GA-123456</p>
                    <p><strong>Booking Date:</strong> May 20, 2024</p>
                    <p><strong>Payment Due:</strong> June 15, 2024</p>
                </div>
                
                <div class="modal-section">
                    <h3>Festival Highlights</h3>
                    <ul class="modal-features">
                        <li>Tastings from 50+ international cuisines</li>
                        <li>Cooking demonstrations by celebrity chefs</li>
                        <li>Live music and entertainment</li>
                        <li>Food competitions and contests</li>
                        <li>Family-friendly activities</li>
                        <li>Beer and wine garden access</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div id="event-history-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Event History</h2>
                <button class="close-modal" onclick="closeModal('event-history')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="modal-section">
                    <h3>2024 Events Attended</h3>
                    <ul class="modal-features">
                        <li>Spring Jazz Festival - April 12, 2024</li>
                        <li>Digital Marketing Conference - March 8, 2024</li>
                        <li>Wine & Cheese Expo - February 14, 2024</li>
                        <li>New Year's Eve Gala - December 31, 2023</li>
                    </ul>
                </div>
                
                <div class="modal-section">
                    <h3>Event Statistics</h3>
                    <p><strong>Total Events Attended:</strong> 12</p>
                    <p><strong>Favorite Event Type:</strong> Music Festivals</p>
                    <p><strong>Average Rating Given:</strong> 4.7/5</p>
                    <p><strong>Most Active Month:</strong> June (3 events)</p>
                </div>
                
                <div class="modal-section">
                    <h3>Upcoming Events</h3>
                    <ul class="modal-features">
                        <li>Summer Music Festival - June 15-17, 2024</li>
                        <li>Tech Innovation Summit - July 22-24, 2024</li>
                        <li>International Food Festival - August 5, 2024</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div id="rate-events-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Rate Events</h2>
                <button class="close-modal" onclick="closeModal('rate-events')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="modal-section">
                    <h3>Pending Reviews</h3>
                    <ul class="modal-features">
                        <li>Spring Jazz Festival - Rate your experience</li>
                        <li>Digital Marketing Conference - Share feedback</li>
                        <li>Wine & Cheese Expo - Tell us what you think</li>
                    </ul>
                </div>
                
                <div class="modal-section">
                    <h3>Rating Guidelines</h3>
                    <ul class="modal-features">
                        <li>Rate events within 30 days of attendance</li>
                        <li>Provide detailed feedback for better events</li>
                        <li>Your ratings help other users choose events</li>
                        <li>Earn reward points for quality reviews</li>
                    </ul>
                </div>
                
                <div class="modal-section">
                    <h3>Review Statistics</h3>
                    <p><strong>Reviews Submitted:</strong> 8</p>
                    <p><strong>Helpful Votes Received:</strong> 24</p>
                    <p><strong>Reviewer Level:</strong> Top Contributor</p>
                    <p><strong>Reward Points Earned:</strong> 1,200</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Payments Modals -->
    <div id="transaction-history-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Transaction History</h2>
                <button class="close-modal" onclick="closeModal('transaction-history')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="modal-section">
                    <h3>Recent Transactions</h3>
                    <ul class="modal-features">
                        <li>Summer Music Festival - $299.00 - March 15, 2024</li>
                        <li>Tech Innovation Summit - $450.00 - April 10, 2024</li>
                        <li>International Food Festival - $75.00 - May 20, 2024</li>
                        <li>Spring Jazz Festival - $120.00 - February 28, 2024</li>
                    </ul>
                </div>
                
                <div class="modal-section">
                    <h3>Payment Statistics</h3>
                    <p><strong>Total Spent This Year:</strong> $944.00</p>
                    <p><strong>Average Transaction:</strong> $236.00</p>
                    <p><strong>Successful Payments:</strong> 12</p>
                    <p><strong>Refunds Processed:</strong> 1 ($50.00)</p>
                </div>
                
                <div class="modal-section">
                    <h3>Payment Methods Used</h3>
                    <ul class="modal-features">
                        <li>Visa ending in 4567 (Primary)</li>
                        <li>PayPal (john.doe@email.com)</li>
                        <li>Apple Pay</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div id="payment-methods-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Payment Methods</h2>
                <button class="close-modal" onclick="closeModal('payment-methods')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="modal-section">
                    <h3>Saved Payment Methods</h3>
                    <ul class="modal-features">
                        <li>Visa ending in 4567 - Expires 12/2025 (Primary)</li>
                        <li>MasterCard ending in 8912 - Expires 08/2024</li>
                        <li>PayPal - john.doe@email.com</li>
                        <li>Apple Pay - iPhone 14 Pro</li>
                    </ul>
                </div>
                
                <div class="modal-section">
                    <h3>Security Features</h3>
                    <ul class="modal-features">
                        <li>All payments are PCI DSS compliant</li>
                        <li>Tokenized payment storage</li>
                        <li>3D Secure authentication enabled</li>
                        <li>Real-time fraud monitoring</li>
                    </ul>
                </div>
                
                <div class="modal-section">
                    <h3>Billing Preferences</h3>
                    <ul class="modal-features">
                        <li>Automatic payment receipts</li>
                        <li>Email notifications for all transactions</li>
                        <li>Monthly spending reports</li>
                        <li>Tax document storage</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div id="invoices-receipts-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Invoices & Receipts</h2>
                <button class="close-modal" onclick="closeModal('invoices-receipts')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="modal-section">
                    <h3>Recent Documents</h3>
                    <ul class="modal-features">
                        <li>Summer Music Festival Invoice - ES-INV-789123</li>
                        <li>Tech Innovation Summit Receipt - ES-RCP-456789</li>
                        <li>International Food Festival Booking Confirmation</li>
                        <li>Q1 2024 Tax Summary Document</li>
                    </ul>
                </div>
                
                <div class="modal-section">
                    <h3>Document Management</h3>
                    <ul class="modal-features">
                        <li>Automatic document storage for 7 years</li>
                        <li>Download in PDF format</li>
                        <li>Email forwarding capability</li>
                        <li>Print-friendly versions available</li>
                    </ul>
                </div>
                
                <div class="modal-section">
                    <h3>Tax Information</h3>
                    <ul class="modal-features">
                        <li>Annual spending reports available</li>
                        <li>Tax-deductible event tracking</li>
                        <li>Business expense categorization</li>
                        <li>Export to accounting software</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Notifications Modals -->
    <div id="message-center-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Message Center</h2>
                <button class="close-modal" onclick="closeModal('message-center')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="modal-section">
                    <h3>Recent Messages</h3>
                    <ul class="modal-features">
                        <li>EventSphere Team - Welcome to Premium!</li>
                        <li>Summer Festival Organizer - Important Update</li>
                        <li>Tech Summit - Workshop Schedule Confirmed</li>
                        <li>EventSphere - New Features Available</li>
                    </ul>
                </div>
                
                <div class="modal-section">
                    <h3>Message Preferences</h3>
                    <ul class="modal-features">
                        <li>Receive organizer messages</li>
                        <li>Event updates and changes</li>
                        <li>Special offers and promotions</li>
                        <li>System announcements</li>
                    </ul>
                </div>
                
                <div class="modal-section">
                    <h3>Message Statistics</h3>
                    <p><strong>Unread Messages:</strong> 2</p>
                    <p><strong>Total Messages:</strong> 47</p>
                    <p><strong>Organizer Messages:</strong> 15</p>
                    <p><strong>System Messages:</strong> 32</p>
                </div>
            </div>
        </div>
    </div>

    <div id="event-reminders-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Event Reminders</h2>
                <button class="close-modal" onclick="closeModal('event-reminders')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="modal-section">
                    <h3>Upcoming Reminders</h3>
                    <ul class="modal-features">
                        <li>Summer Music Festival - 3 days before</li>
                        <li>Tech Innovation Summit - 1 week before</li>
                        <li>International Food Festival - Payment due</li>
                    </ul>
                </div>
                
                <div class="modal-section">
                    <h3>Reminder Settings</h3>
                    <ul class="modal-features">
                        <li>Email reminders - 7 days, 3 days, 1 day before</li>
                        <li>Push notifications - 1 day, 2 hours before</li>
                        <li>SMS alerts - Critical changes only</li>
                        <li>Calendar integration - Auto-sync enabled</li>
                    </ul>
                </div>
                
                <div class="modal-section">
                    <h3>Smart Reminders</h3>
                    <ul class="modal-features">
                        <li>Weather alerts for outdoor events</li>
                        <li>Traffic updates for event locations</li>
                        <li>Last-minute ticket availability</li>
                        <li>Friend attendance notifications</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div id="announcements-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Announcements</h2>
                <button class="close-modal" onclick="closeModal('announcements')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="modal-section">
                    <h3>Latest Updates</h3>
                    <ul class="modal-features">
                        <li>New: EventSphere Mobile App v2.0 Released</li>
                        <li>Feature: Enhanced Event Recommendations</li>
                        <li>Update: Improved Booking Process</li>
                        <li>News: Summer Event Lineup Announced</li>
                    </ul>
                </div>
                
                <div class="modal-section">
                    <h3>System Announcements</h3>
                    <ul class="modal-features">
                        <li>Scheduled maintenance - June 10, 2-4 AM EST</li>
                        <li>New privacy features available</li>
                        <li>Updated terms of service</li>
                        <li>Mobile app performance improvements</li>
                    </ul>
                </div>
                
                <div class="modal-section">
                    <h3>Community News</h3>
                    <ul class="modal-features">
                        <li>User meetup scheduled for July 15</li>
                        <li>Featured event organizer of the month</li>
                        <li>Success stories from EventSphere users</li>
                        <li>Upcoming feature voting now open</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Settings Modals -->
    <div id="appearance-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Appearance Settings</h2>
                <button class="close-modal" onclick="closeModal('appearance')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="modal-section">
                    <h3>Theme Options</h3>
                    <ul class="modal-features">
                        <li>Light Theme (Current)</li>
                        <li>Dark Theme</li>
                        <li>Auto (System Preference)</li>
                        <li>High Contrast Mode</li>
                    </ul>
                </div>
                
                <div class="modal-section">
                    <h3>Display Preferences</h3>
                    <ul class="modal-features">
                        <li>Font Size: Medium</li>
                        <li>Animation: Reduced Motion</li>
                        <li>Layout: Compact</li>
                        <li>Color Blind Mode: Off</li>
                    </ul>
                </div>
                
                <div class="modal-section">
                    <h3>Customization</h3>
                    <ul class="modal-features">
                        <li>Custom accent color available</li>
                        <li>Background image selection</li>
                        <li>Card layout preferences</li>
                        <li>Navigation style options</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div id="language-region-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Language & Region</h2>
                <button class="close-modal" onclick="closeModal('language-region')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="modal-section">
                    <h3>Language Settings</h3>
                    <ul class="modal-features">
                        <li>Interface Language: English (US)</li>
                        <li>Content Language: Auto-detect</li>
                        <li>Translation: Enabled for event descriptions</li>
                        <li>Spell Check: Enabled</li>
                    </ul>
                </div>
                
                <div class="modal-section">
                    <h3>Regional Preferences</h3>
                    <ul class="modal-features">
                        <li>Time Zone: Eastern Time (ET)</li>
                        <li>Date Format: MM/DD/YYYY</li>
                        <li>Time Format: 12-hour clock</li>
                        <li>Currency: US Dollar ($)</li>
                    </ul>
                </div>
                
                <div class="modal-section">
                    <h3>Location Services</h3>
                    <ul class="modal-features">
                        <li>Current Location: New York, NY</li>
                        <li>Event Recommendations: Based on location</li>
                        <li>Weather Integration: Enabled</li>
                        <li>Local Events Priority: High</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div id="privacy-data-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Privacy & Data Settings</h2>
                <button class="close-modal" onclick="closeModal('privacy-data')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="modal-section">
                    <h3>Privacy Controls</h3>
                    <ul class="modal-features">
                        <li>Profile Visibility: Friends Only</li>
                        <li>Event Activity: Visible to Friends</li>
                        <li>Search Engine Indexing: Disabled</li>
                        <li>Data Sharing: Limited with Partners</li>
                    </ul>
                </div>
                
                <div class="modal-section">
                    <h3>Data Management</h3>
                    <ul class="modal-features">
                        <li>Data Export: Available on request</li>
                        <li>Auto-delete: After 3 years of inactivity</li>
                        <li>Cookies: Essential only</li>
                        <li>Analytics: Anonymized participation</li>
                    </ul>
                </div>
                
                <div class="modal-section">
                    <h3>Security & Permissions</h3>
                    <ul class="modal-features">
                        <li>Location Access: When using app</li>
                        <li>Camera Access: For event check-ins</li>
                        <li>Notifications: Customized per event</li>
                        <li>Third-party Integrations: Review required</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Function to show sections
        function showSection(section) {
            // Hide all sections
            document.querySelectorAll('.content-section').forEach(sec => {
                sec.classList.remove('active');
            });
            
            // Remove active class from all nav items
            document.querySelectorAll('.top-nav-item').forEach(item => {
                item.classList.remove('active');
            });
            
            // Show selected section and activate nav item
            document.getElementById(section + '-section').classList.add('active');
            event.target.classList.add('active');
            
            // Update URL without page reload
            history.pushState(null, null, 'user_dashboard.php?section=' + section);
        }

        // Modal functionality
        function openModal(type) {
            const modal = document.getElementById(`${type}-modal`);
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(type) {
            const modal = document.getElementById(`${type}-modal`);
            modal.classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking outside content
        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    const modalId = this.id;
                    const type = modalId.replace('-modal', '');
                    closeModal(type);
                }
            });
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('.modal.active').forEach(modal => {
                    const modalId = modal.id;
                    const type = modalId.replace('-modal', '');
                    closeModal(type);
                });
            }
        });

        // Handle browser back/forward buttons
        window.addEventListener('popstate', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const section = urlParams.get('section') || 'welcome';
            showSection(section);
        });

        // Add enhanced hover effects
        document.addEventListener('DOMContentLoaded', function() {
            const navItems = document.querySelectorAll('.top-nav-item');
            navItems.forEach(item => {
                item.addEventListener('mouseenter', function() {
                    if (!this.classList.contains('active')) {
                        this.style.transform = 'translateY(-2px)';
                    }
                });
                
                item.addEventListener('mouseleave', function() {
                    if (!this.classList.contains('active')) {
                        this.style.transform = 'translateY(0)';
                    }
                });
            });

            // Add staggered animation to stat cards
            const statCards = document.querySelectorAll('.stat-card');
            statCards.forEach((card, index) => {
                card.style.animationDelay = (index * 0.1) + 's';
                card.style.opacity = '0';
                card.style.animation = 'fadeInUp 0.6s ease ' + (index * 0.1) + 's forwards';
            });

            // Add animation to feature cards
            const featureCards = document.querySelectorAll('.feature-card');
            featureCards.forEach((card, index) => {
                card.style.animationDelay = (index * 0.1) + 's';
                card.style.opacity = '0';
                card.style.animation = 'fadeInUp 0.6s ease ' + (index * 0.1) + 's forwards';
            });

            // Add animation to booking cards
            const bookingCards = document.querySelectorAll('.booking-card');
            bookingCards.forEach((card, index) => {
                card.style.animationDelay = (index * 0.1) + 's';
                card.style.opacity = '0';
                card.style.animation = 'fadeInUp 0.6s ease ' + (index * 0.1) + 's forwards';
            });
        });

        // Add scroll animation for elements
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.animationPlayState = 'running';
                }
            });
        }, observerOptions);

        // Observe all animated elements
        document.querySelectorAll('.stat-card, .feature-card, .booking-card').forEach(el => {
            observer.observe(el);
        });
    </script>
</body>
</html>