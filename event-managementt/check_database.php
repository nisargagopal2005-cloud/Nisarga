<?php
include 'config.php';

echo "<h2>Database Check</h2>";

// Check admins table
echo "<h3>Admins Table:</h3>";
$stmt = $pdo->query("SELECT * FROM admins");
$admins = $stmt->fetchAll();

if (count($admins) > 0) {
    echo "<table border='1' cellpadding='10'>";
    echo "<tr><th>ID</th><th>Username</th><th>Password Hash</th><th>Hash Length</th><th>Created At</th></tr>";
    foreach ($admins as $admin) {
        echo "<tr>";
        echo "<td>" . $admin['id'] . "</td>";
        echo "<td>" . $admin['username'] . "</td>";
        echo "<td style='word-break: break-all;'>" . $admin['password'] . "</td>";
        echo "<td>" . strlen($admin['password']) . "</td>";
        echo "<td>" . $admin['created_at'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No admins found in database!</p>";
}

// Check database structure
echo "<h3>Database Structure:</h3>";
$tables = $pdo->query("SHOW TABLES")->fetchAll();
foreach ($tables as $table) {
    $table_name = $table[0];
    echo "<h4>Table: $table_name</h4>";
    $columns = $pdo->query("DESCRIBE $table_name")->fetchAll();
    echo "<pre>";
    print_r($columns);
    echo "</pre>";
}
?>