<?php
session_start();
require_once 'db_config.php';

// Handle admin login
$login_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username']) && isset($_POST['password'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE username = ? AND password = ?");
        $stmt->execute([$username, $password]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            $_SESSION['user'] = $user;
            $_SESSION['logged_in'] = true;
            $_SESSION['login_time'] = time();
            
            // Update last login
            $update_stmt = $pdo->prepare("UPDATE admin_users SET last_login = NOW() WHERE id = ?");
            $update_stmt->execute([$user['id']]);
            
            header("Location: ".$_SERVER['PHP_SELF']);
            exit();
        } else {
            $login_error = 'Invalid username or password!';
        }
    } catch(PDOException $e) {
        $login_error = 'Database error: ' . $e->getMessage();
    }
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}

// Get all admin users for display
$admin_users = [];
try {
    $stmt = $pdo->query("SELECT * FROM admin_users ORDER BY name");
    $admin_users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $admin_users_error = 'Failed to load admin users';
}

// Get events from database
$events = [];
try {
    $stmt = $pdo->query("SELECT * FROM events ORDER BY event_date");
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $events_error = 'Failed to load events';
}

// Handle new event creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_event'])) {
    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
        $event_name = trim($_POST['event_name']);
        $event_date = $_POST['event_date'];
        $event_time = $_POST['event_time'];
        $venue = trim($_POST['venue']);
        $description = trim($_POST['description']);
        
        try {
            $stmt = $pdo->prepare("INSERT INTO events (event_name, event_date, event_time, venue, description, created_by) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$event_name, $event_date, $event_time, $venue, $description, $_SESSION['user']['id']]);
            header("Location: ".$_SERVER['PHP_SELF']);
            exit();
        } catch(PDOException $e) {
            $event_error = 'Failed to create event: ' . $e->getMessage();
        }
    }
}

// Check if user is logged in
$logged_in = isset($_SESSION['logged_in']) && $_SESSION['logged_in'];
$current_user = $logged_in ? $_SESSION['user'] : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Header Styles */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(255, 255, 255, 0.95);
            padding: 20px 40px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .logo h1 {
            color: #333;
            font-size: 2.2rem;
        }

        .welcome-text {
            color: #666;
            font-size: 1.1rem;
            text-align: center;
        }

        .admin-panel-toggle {
            background: #667eea;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 25px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s;
        }

        .admin-panel-toggle:hover {
            background: #5a6fd8;
        }

        /* Main Content Grid */
        .main-content {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
        }

        /* Event Management Section */
        .event-management {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .section-title {
            color: #333;
            margin-bottom: 25px;
            font-size: 1.8rem;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
        }

        /* Event Cards Grid */
        .event-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .event-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            border-left: 5px solid #667eea;
            transition: transform 0.3s;
        }

        .event-card:hover {
            transform: translateY(-5px);
        }

        .event-card h3 {
            color: #333;
            margin-bottom: 15px;
            font-size: 1.3rem;
        }

        .event-card p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 10px;
        }

        .event-date {
            color: #667eea;
            font-weight: bold;
            font-size: 0.9rem;
        }

        /* Database Events Section */
        .db-events {
            margin-top: 40px;
        }

        .events-list {
            display: grid;
            gap: 15px;
        }

        .db-event-item {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            border-left: 4px solid #ff6b6b;
        }

        .db-event-item h4 {
            color: #333;
            margin-bottom: 10px;
            font-size: 1.2rem;
        }

        .event-meta {
            display: flex;
            gap: 15px;
            margin-bottom: 10px;
            font-size: 0.9rem;
            color: #666;
        }

        .event-description {
            color: #777;
            line-height: 1.5;
        }

        /* Create Event Form */
        .create-event-form {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-top: 30px;
        }

        .create-event-form h3 {
            color: #333;
            margin-bottom: 20px;
            font-size: 1.4rem;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            color: #333;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        .form-group textarea {
            height: 80px;
            resize: vertical;
        }

        .create-btn {
            background: #667eea;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s;
        }

        .create-btn:hover {
            background: #5a6fd8;
        }

        .explore-btn {
            display: block;
            width: 200px;
            margin: 40px auto 0;
            padding: 12px 25px;
            background: #667eea;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            transition: background 0.3s;
        }

        .explore-btn:hover {
            background: #5a6fd8;
        }

        /* Admin Panel Section */
        .admin-section {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            transform-style: preserve-3d;
            transition: transform 0.5s;
            border: 1px solid rgba(255,255,255,0.2);
        }

        .login-title {
            color: white;
            font-size: 1.6rem;
            margin-bottom: 20px;
            text-align: center;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .login-form {
            width: 100%;
        }

        .form-group-admin {
            margin-bottom: 15px;
        }

        .form-group-admin label {
            display: block;
            color: white;
            margin-bottom: 5px;
            font-weight: bold;
            font-size: 0.9rem;
        }

        .form-group-admin input {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            font-size: 14px;
        }

        .form-group-admin input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .login-btn {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            background: linear-gradient(45deg, #ff6b6b, #ee5a24);
            color: white;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.3s;
        }

        .login-btn:hover {
            transform: translateY(-2px);
        }

        .error-message {
            color: #ff6b6b;
            background: rgba(255, 255, 255, 0.2);
            padding: 8px;
            border-radius: 5px;
            margin-top: 10px;
            text-align: center;
            font-size: 12px;
        }

        /* Profile Section */
        .profile-section {
            color: white;
        }

        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(255,255,255,0.3);
        }

        .profile-pic {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            border: 3px solid white;
            margin-right: 15px;
        }

        .profile-info h2 {
            font-size: 1.2rem;
            margin-bottom: 5px;
        }

        .profile-info p {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .profile-details {
            margin-bottom: 20px;
        }

        .detail-item {
            margin-bottom: 8px;
            padding: 6px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 6px;
        }

        .detail-label {
            font-size: 0.7rem;
            color: #ff6b6b;
            margin-bottom: 2px;
        }

        .detail-value {
            font-size: 0.8rem;
        }

        .users-section h3 {
            text-align: center;
            margin-bottom: 15px;
            font-size: 1rem;
        }

        .users-list {
            max-height: 150px;
            overflow-y: auto;
        }

        .user-item {
            display: flex;
            align-items: center;
            padding: 6px;
            margin-bottom: 6px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 6px;
        }

        .user-pic {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            margin-right: 8px;
        }

        .user-info {
            flex: 1;
        }

        .user-name {
            font-size: 0.8rem;
            margin-bottom: 2px;
        }

        .user-role {
            font-size: 0.6rem;
            color: #ff6b6b;
        }

        .status-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            margin-left: 5px;
        }

        .status-online {
            background: #2ecc71;
        }

        .status-offline {
            background: #e74c3c;
        }

        .logout-btn {
            display: block;
            width: 100%;
            padding: 8px;
            margin-top: 15px;
            background: #e74c3c;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 12px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .logout-btn:hover {
            background: #c0392b;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .main-content {
                grid-template-columns: 1fr;
            }
            
            .header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
            
            .event-management {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="logo">
                <h1>Event Management</h1>
            </div>
            <div class="welcome-text">
                <?php if ($logged_in): ?>
                    Welcome, <strong><?php echo $current_user['name']; ?></strong> | 
                    Discover upcoming events and manage them easily.
                <?php else: ?>
                    Welcome, <strong>Guest</strong> | 
                    Discover upcoming events and manage them easily.
                <?php endif; ?>
            </div>
            <button class="admin-panel-toggle" onclick="scrollToAdmin()">
                <?php echo $logged_in ? 'Admin Panel' : 'Admin Login'; ?>
            </button>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Event Management Section -->
            <div class="event-management">
                <h2 class="section-title">Event Management Features</h2>
                
                <div class="event-grid">
                    <div class="event-card">
                        <h3>Event Scheduling</h3>
                        <p>Plan dates and times for your events with our intuitive scheduling system. Manage multiple events simultaneously with real-time updates.</p>
                        <p class="event-date">Real-time calendar integration</p>
                    </div>
                    
                    <div class="event-card">
                        <h3>Ticketing Management</h3>
                        <p>Manage bookings and tickets efficiently. Track attendance, generate reports, and handle ticket sales with our comprehensive system.</p>
                        <p class="event-date">Digital ticketing solutions</p>
                    </div>
                    
                    <div class="event-card">
                        <h3>Payment Processing</h3>
                        <p>Secure payment gateways integrated for smooth transactions. Support for multiple payment methods including cards and digital wallets.</p>
                        <p class="event-date">PCI DSS compliant</p>
                    </div>
                    
                    <div class="event-card">
                        <h3>Venue Management</h3>
                        <p>Find and manage event venues efficiently. Integrated with maps for easy location tracking and capacity management.</p>
                        <p class="event-date">Google Maps integrated</p>
                    </div>
                </div>

                <!-- Database Events Section -->
                <div class="db-events">
                    <h2 class="section-title">Upcoming Events</h2>
                    <div class="events-list">
                        <?php if (!empty($events)): ?>
                            <?php foreach ($events as $event): ?>
                                <div class="db-event-item">
                                    <h4><?php echo htmlspecialchars($event['event_name']); ?></h4>
                                    <div class="event-meta">
                                        <span><strong>Date:</strong> <?php echo date('M j, Y', strtotime($event['event_date'])); ?></span>
                                        <span><strong>Time:</strong> <?php echo $event['event_time']; ?></span>
                                        <span><strong>Venue:</strong> <?php echo htmlspecialchars($event['venue']); ?></span>
                                    </div>
                                    <p class="event-description"><?php echo htmlspecialchars($event['description']); ?></p>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="db-event-item">
                                <p>No upcoming events found. <?php echo $logged_in ? 'Create your first event!' : 'Please login to create events.'; ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Create Event Form (Only for logged-in admins) -->
                <?php if ($logged_in): ?>
                <div class="create-event-form">
                    <h3>Create New Event</h3>
                    <form method="POST">
                        <input type="hidden" name="create_event" value="1">
                        <div class="form-group">
                            <label for="event_name">Event Name</label>
                            <input type="text" id="event_name" name="event_name" required>
                        </div>
                        <div class="form-group">
                            <label for="event_date">Event Date</label>
                            <input type="date" id="event_date" name="event_date" required>
                        </div>
                        <div class="form-group">
                            <label for="event_time">Event Time</label>
                            <input type="time" id="event_time" name="event_time" required>
                        </div>
                        <div class="form-group">
                            <label for="venue">Venue</label>
                            <input type="text" id="venue" name="venue" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" required></textarea>
                        </div>
                        <button type="submit" class="create-btn">Create Event</button>
                    </form>
                </div>
                <?php endif; ?>
                
                <a href="#" class="explore-btn">Explore All Events</a>
            </div>

            <!-- Admin Panel Section -->
            <div class="admin-section" id="adminPanel">
                <?php if (!$logged_in): ?>
                    <!-- Login Form -->
                    <h2 class="login-title">Admin Login</h2>
                    <form class="login-form" method="POST">
                        <div class="form-group-admin">
                            <label for="username">Username</label>
                            <input type="text" id="username" name="username" placeholder="Enter username" required>
                        </div>
                        <div class="form-group-admin">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" placeholder="Enter password" required>
                        </div>
                        <button type="submit" class="login-btn">Login to Admin Panel</button>
                        <?php if ($login_error): ?>
                            <div class="error-message"><?php echo $login_error; ?></div>
                        <?php endif; ?>
                    </form>
                <?php else: ?>
                    <!-- Profile Section -->
                    <div class="profile-section">
                        <div class="profile-header">
                            <img src="<?php echo $current_user['profile_pic']; ?>" alt="Profile" class="profile-pic">
                            <div class="profile-info">
                                <h2><?php echo $current_user['name']; ?></h2>
                                <p><?php echo $current_user['role']; ?></p>
                            </div>
                        </div>

                        <div class="profile-details">
                            <div class="detail-item">
                                <div class="detail-label">EMAIL</div>
                                <div class="detail-value"><?php echo $current_user['email']; ?></div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">PHONE</div>
                                <div class="detail-value"><?php echo $current_user['phone']; ?></div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">DEPARTMENT</div>
                                <div class="detail-value"><?php echo $current_user['department']; ?></div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">LAST LOGIN</div>
                                <div class="detail-value">
                                    <?php echo $current_user['last_login'] ? date('M j, Y g:i A', strtotime($current_user['last_login'])) : 'First login'; ?>
                                </div>
                            </div>
                        </div>

                        <div class="users-section">
                            <h3>Admin Team Members</h3>
                            <div class="users-list">
                                <?php foreach ($admin_users as $user): ?>
                                    <div class="user-item">
                                        <img src="<?php echo $user['profile_pic']; ?>" alt="<?php echo $user['name']; ?>" class="user-pic">
                                        <div class="user-info">
                                            <div class="user-name"><?php echo $user['name']; ?></div>
                                            <div class="user-role"><?php echo $user['role']; ?></div>
                                        </div>
                                        <div class="status-dot <?php echo ($user['username'] === $current_user['username']) ? 'status-online' : 'status-offline'; ?>"></div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <a href="?logout=true" class="logout-btn">Logout from Admin Panel</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        // 3D Effect for Admin Panel
        const adminPanel = document.getElementById('adminPanel');
        
        document.addEventListener('mousemove', (e) => {
            const x = (window.innerWidth - e.pageX) / 50;
            const y = (window.innerHeight - e.pageY) / 50;
            adminPanel.style.transform = `rotateY(${x}deg) rotateX(${y}deg)`;
        });

        document.addEventListener('mouseleave', () => {
            adminPanel.style.transform = 'rotateY(0deg) rotateX(0deg)';
        });

        // Scroll to admin panel function
        function scrollToAdmin() {
            document.getElementById('adminPanel').scrollIntoView({ 
                behavior: 'smooth' 
            });
        }

        // Set minimum date for event creation to today
        document.addEventListener('DOMContentLoaded', function() {
            const dateInput = document.getElementById('event_date');
            if (dateInput) {
                const today = new Date().toISOString().split('T')[0];
                dateInput.min = today;
            }
        });
    </script>
</body>
</html>