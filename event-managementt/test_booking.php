<?php
session_start();
require_once 'config/database.php';

// Force login for testing
$_SESSION['user_id'] = 1;
$_SESSION['username'] = 'Test User';

// Check if we have any bookings
$sql = "SELECT COUNT(*) as count FROM bookings WHERE user_id = 1";
$result = $pdo->query($sql)->fetch();

if ($result['count'] == 0) {
    echo "<p style='color: red;'>No bookings found in database.</p>";
    echo "<p>Run the SQL in phpMyAdmin to create sample data.</p>";
} else {
    echo "<p style='color: green;'>Found {$result['count']} bookings in database.</p>";
    echo "<p><a href='user1_dashboard.php'>Go to Dashboard</a></p>";
}
?>