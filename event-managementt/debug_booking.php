<?php
session_start();
require_once 'config/database.php';

echo "<h2>🔧 Debugging Booked Events</h2>";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<p style='color: red;'>❌ User not logged in</p>";
} else {
    echo "<p style='color: green;'>✅ User logged in - ID: " . $_SESSION['user_id'] . "</p>";
}

// Check database connection
try {
    $pdo->query("SELECT 1");
    echo "<p style='color: green;'>✅ Database connected successfully</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Database connection failed: " . $e->getMessage() . "</p>";
}

// Check if tables exist
$tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
echo "<h3>Database Tables:</h3>";
if (empty($tables)) {
    echo "<p style='color: red;'>❌ No tables found in database</p>";
} else {
    echo "<ul>";
    foreach ($tables as $table) {
        echo "<li>$table</li>";
    }
    echo "</ul>";
}

// Check if user has any bookings
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    
    // Check bookings table structure
    try {
        $bookings_count = $pdo->query("SELECT COUNT(*) as count FROM bookings WHERE user_id = $user_id")->fetch();
        echo "<p>Bookings found: " . $bookings_count['count'] . "</p>";
        
        if ($bookings_count['count'] == 0) {
            echo "<p style='color: orange;'>⚠️ No bookings found for user ID: $user_id</p>";
            
            // Show sample data to insert
            echo "<h3>Sample booking data to test:</h3>";
            echo "<pre>";
            echo "INSERT INTO bookings (user_id, event_id, ticket_quantity, total_amount, final_amount, payment_method) VALUES \n";
            echo "($user_id, 1, 2, 5000.00, 5000.00, 'cash'),\n";
            echo "($user_id, 2, 1, 5000.00, 5000.00, 'upi');";
            echo "</pre>";
        }
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ Error checking bookings: " . $e->getMessage() . "</p>";
    }
}

// Check events table
try {
    $events_count = $pdo->query("SELECT COUNT(*) as count FROM events")->fetch();
    echo "<p>Events in database: " . $events_count['count'] . "</p>";
    
    if ($events_count['count'] == 0) {
        echo "<p style='color: orange;'>⚠️ No events found in database</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error checking events: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<h3>Next Steps:</h3>";
echo "<ol>";
echo "<li>Run the SQL below in phpMyAdmin to create tables</li>";
echo "<li>Insert sample events and bookings</li>";
echo "<li>Refresh this page to check</li>";
echo "</ol>";
?>