<?php
echo "Testing database connection...<br>";

try {
    require_once 'inc/db.php';
    echo "✅ Database connected successfully!<br>";
    
    // Test if we can query
    $stmt = $pdo->query("SELECT 1 as test");
    $result = $stmt->fetch();
    echo "✅ Database query works!<br>";
    
} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . "<br>";
}
?>