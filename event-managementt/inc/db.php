<?php
// inc/db.php - Database connection with admin login support

try {
    $pdo = new PDO('mysql:host=localhost;dbname=event_management', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Don't redirect in included files - just show error
    die("Database connection failed: " . $e->getMessage());
}

// Admin login functions
function verifyAdminLogin($username, $password) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($admin && password_verify($password, $admin['password'])) {
            return $admin;
        }
        return false;
    } catch (PDOException $e) {
        return false;
    }
}

function getAdminById($admin_id) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE id = ?");
        $stmt->execute([$admin_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return false;
    }
}

function getAllAdmins() {
    global $pdo;
    
    try {
        $stmt = $pdo->query("SELECT id, username, created_at FROM admins ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [];
    }
}

function createAdmin($username, $password) {
    global $pdo;
    
    try {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
        return $stmt->execute([$username, $hashed_password]);
    } catch (PDOException $e) {
        return false;
    }
}

function updateAdminPassword($admin_id, $new_password) {
    global $pdo;
    
    try {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE admins SET password = ? WHERE id = ?");
        return $stmt->execute([$hashed_password, $admin_id]);
    } catch (PDOException $e) {
        return false;
    }
}

// Check if admin table exists, if not create it
function checkAdminTable() {
    global $pdo;
    
    try {
        // Check if admins table exists
        $result = $pdo->query("SHOW TABLES LIKE 'admins'");
        if ($result->rowCount() == 0) {
            // Create admins table
            $pdo->exec("CREATE TABLE IF NOT EXISTS `admins` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `username` VARCHAR(80) NOT NULL UNIQUE,
                `password` VARCHAR(255) NOT NULL,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
            
            // Insert default admin
            $default_password = password_hash('admin123', PASSWORD_DEFAULT);
            $pdo->exec("INSERT INTO `admins` (`username`, `password`) VALUES ('admin', '$default_password')");
        }
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

// Initialize admin table on include
checkAdminTable();
?>