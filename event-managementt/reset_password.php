<?php
// reset_passwords.php
session_start();
include 'inc/db.php';

echo "<!DOCTYPE html>
<html>
<head>
    <title>Reset Admin Passwords</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'>
</head>
<body class='bg-light'>
    <div class='container mt-5'>
        <div class='row justify-content-center'>
            <div class='col-md-8'>
                <div class='card shadow'>
                    <div class='card-header bg-primary text-white'>
                        <h3 class='text-center'>Reset Admin Passwords</h3>
                    </div>
                    <div class='card-body'>";

// List of admin users to create/reset
$admins = [
    'admin' => 'admin123',
    'rohan' => 'rohan123',
    'varshan' => 'varshan123',
    'nisarga' => 'nisarga123',
    'sathya' => 'sathya123'
];

foreach ($admins as $username => $password) {
    // Generate proper password hash
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Check if admin exists
    $stmt = $pdo->prepare("SELECT id FROM admins WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();
    
    if ($admin) {
        // Update existing admin
        $stmt = $pdo->prepare("UPDATE admins SET password = ? WHERE username = ?");
        if ($stmt->execute([$hashed_password, $username])) {
            echo "<div class='alert alert-success'>✅ Updated password for <strong>$username</strong>: $password</div>";
        } else {
            echo "<div class='alert alert-danger'>❌ Failed to update $username</div>";
        }
    } else {
        // Insert new admin
        $stmt = $pdo->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
        if ($stmt->execute([$username, $hashed_password])) {
            echo "<div class='alert alert-success'>✅ Created admin <strong>$username</strong> with password: $password</div>";
        } else {
            echo "<div class='alert alert-danger'>❌ Failed to create $username</div>";
        }
    }
}

echo "<hr>
        <div class='alert alert-info'>
            <h5>Login Credentials:</h5>
            <ul class='list-unstyled'>";

foreach ($admins as $username => $password) {
    echo "<li><strong>Username:</strong> $username | <strong>Password:</strong> $password</li>";
}

echo "      </ul>
        </div>
        <div class='text-center'>
            <a href='admin_login.php' class='btn btn-success btn-lg'>Test Login Now</a>
        </div>
    </div>
</div>
</div>
</div>
</body>
</html>";
?>