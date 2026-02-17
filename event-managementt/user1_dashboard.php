<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: user_event.php");
    exit();
}

require_once 'config/database.php';

$user_id = $_SESSION['user_id'];
$section = isset($_GET['section']) ? $_GET['section'] : 'booked_events';

// Get user's booked events
$booked_events_sql = "SELECT b.*, e.event_name, e.event_date, e.event_time, e.event_location, e.event_price 
                     FROM bookings b 
                     JOIN events e ON b.event_id = e.id 
                     WHERE b.user_id = ? 
                     ORDER BY b.booking_date DESC";
$booked_stmt = $pdo->prepare($booked_events_sql);
$booked_stmt->execute([$user_id]);
$booked_events = $booked_stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }

        .dashboard-header {
            background: linear-gradient(135deg, #4CAF50, #45a049);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .dashboard-header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }

        .dashboard-nav {
            background: #f8f9fa;
            padding: 0 30px;
            border-bottom: 1px solid #e9ecef;
        }

        .nav-tabs {
            display: flex;
            list-style: none;
            gap: 20px;
        }

        .nav-tabs a {
            display: block;
            padding: 20px 0;
            text-decoration: none;
            color: #495057;
            font-weight: 600;
            border-bottom: 3px solid transparent;
            transition: all 0.3s ease;
        }

        .nav-tabs a:hover, .nav-tabs a.active {
            color: #4CAF50;
            border-bottom-color: #4CAF50;
        }

        .dashboard-content {
            padding: 40px;
            min-height: 500px;
        }

        .section-title {
            color: #333;
            margin-bottom: 30px;
            font-size: 2em;
            text-align: center;
        }

        /* Booked Events Styles */
        .booked-events-grid {
            display: grid;
            gap: 25px;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        }

        .booking-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border-left: 5px solid #4CAF50;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .booking-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }

        .booking-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #4CAF50, #45a049);
        }

        .booking-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .booking-id {
            background: #4CAF50;
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.9em;
            font-weight: bold;
        }

        .booking-status {
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9em;
            font-weight: bold;
        }

        .status-confirmed {
            background: #d4edda;
            color: #155724;
        }

        .event-name {
            font-size: 1.4em;
            color: #333;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .booking-details {
            display: grid;
            gap: 10px;
            margin-bottom: 20px;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .detail-label {
            color: #666;
            font-weight: 500;
        }

        .detail-value {
            color: #333;
            font-weight: 600;
        }

        .total-amount {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            font-size: 1.3em;
            font-weight: bold;
            color: #4CAF50;
            margin: 15px 0;
        }

        .booking-actions {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            transition: all 0.3s ease;
            font-size: 0.9em;
        }

        .btn-primary {
            background: #4CAF50;
            color: white;
        }

        .btn-primary:hover {
            background: #45a049;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        .no-bookings {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }

        .no-bookings-icon {
            font-size: 4em;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .dashboard-container {
                margin: 10px;
                border-radius: 15px;
            }

            .dashboard-content {
                padding: 20px;
            }

            .nav-tabs {
                flex-direction: column;
                gap: 0;
            }

            .booked-events-grid {
                grid-template-columns: 1fr;
            }

            .booking-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .booking-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Header -->
        <div class="dashboard-header">
            <h1>User Dashboard</h1>
            <p>Welcome back, <?php echo $_SESSION['username']; ?>!</p>
        </div>

        <!-- Navigation -->
        <div class="dashboard-nav">
            <ul class="nav-tabs">
                <li><a href="user1_dashboard.php?section=booked_events" class="<?php echo $section === 'booked_events' ? 'active' : ''; ?>">📅 My Booked Events</a></li>
                <li><a href="user1_dashboard.php?section=profile" class="<?php echo $section === 'profile' ? 'active' : ''; ?>">👤 Profile</a></li>
                <li><a href="user1_dashboard.php?section=settings" class="<?php echo $section === 'settings' ? 'active' : ''; ?>">⚙️ Settings</a></li>
                <li><a href="logout.php" style="color: #dc3545;">🚪 Logout</a></li>
            </ul>
        </div>

        <!-- Content -->
        <div class="dashboard-content">
            <?php if ($section === 'booked_events'): ?>
                <h2 class="section-title">My Booked Events</h2>
                
                <?php if (count($booked_events) > 0): ?>
                    <div class="booked-events-grid">
                        <?php foreach ($booked_events as $booking): ?>
                            <div class="booking-card">
                                <div class="booking-header">
                                    <span class="booking-id">Booking #<?php echo str_pad($booking['id'], 6, '0', STR_PAD_LEFT); ?></span>
                                    <span class="booking-status status-confirmed">✅ <?php echo ucfirst($booking['status']); ?></span>
                                </div>
                                
                                <h3 class="event-name"><?php echo htmlspecialchars($booking['event_name']); ?></h3>
                                
                                <div class="booking-details">
                                    <div class="detail-item">
                                        <span class="detail-label">Date & Time:</span>
                                        <span class="detail-value">
                                            <?php echo date('M j, Y', strtotime($booking['event_date'])); ?> 
                                            at <?php echo date('g:i A', strtotime($booking['event_time'])); ?>
                                        </span>
                                    </div>
                                    
                                    <div class="detail-item">
                                        <span class="detail-label">Location:</span>
                                        <span class="detail-value"><?php echo htmlspecialchars($booking['event_location']); ?></span>
                                    </div>
                                    
                                    <div class="detail-item">
                                        <span class="detail-label">Tickets:</span>
                                        <span class="detail-value"><?php echo $booking['ticket_quantity']; ?> ticket(s)</span>
                                    </div>
                                    
                                    <div class="detail-item">
                                        <span class="detail-label">Booked on:</span>
                                        <span class="detail-value"><?php echo date('M j, Y g:i A', strtotime($booking['booking_date'])); ?></span>
                                    </div>
                                    
                                    <?php if(isset($booking['payment_method'])): ?>
                                    <div class="detail-item">
                                        <span class="detail-label">Payment Method:</span>
                                        <span class="detail-value" style="text-transform: capitalize;">
                                            <?php echo str_replace('_', ' ', $booking['payment_method']); ?>
                                        </span>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="total-amount">
                                    Total: ₹<?php echo number_format($booking['final_amount'], 2); ?>
                                    <?php if(isset($booking['discount_amount']) && $booking['discount_amount'] > 0): ?>
                                        <div style="font-size: 0.8em; color: #666;">
                                            (Saved ₹<?php echo number_format($booking['discount_amount'], 2); ?>)
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="booking-actions">
                                    <a href="#" class="btn btn-primary">View Ticket</a>
                                    <a href="#" class="btn btn-secondary">Cancel Booking</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="no-bookings">
                        <div class="no-bookings-icon">📭</div>
                        <h3>No Events Booked Yet</h3>
                        <p>You haven't booked any events yet. Start exploring our events!</p>
                        <br>
                        <a href="index_event.php" class="btn btn-primary" style="padding: 12px 30px;">Browse Events</a>
                    </div>
                <?php endif; ?>

            <?php elseif ($section === 'profile'): ?>
                <h2 class="section-title">My Profile</h2>
                <p>Profile section content goes here...</p>

            <?php elseif ($section === 'settings'): ?>
                <h2 class="section-title">Settings</h2>
                <p>Settings section content goes here...</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>