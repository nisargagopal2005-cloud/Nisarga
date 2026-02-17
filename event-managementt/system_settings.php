<?php
session_start();
require_once __DIR__.'/inc/db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin_login.php');
    exit();
}

// Settings file path
$settings_file = 'data/system_settings.json';

// Create data directory if it doesn't exist
if (!is_dir('data')) {
    mkdir('data', 0777, true);
}

// Load existing settings
$default_settings = [
    'site_name' => 'Event Management System',
    'site_email' => 'admin@company.com',
    'site_phone' => '+1 (555) 123-4567',
    'site_address' => '123 Business Street, City, State 12345',
    'currency' => 'USD',
    'timezone' => 'America/New_York',
    'date_format' => 'Y-m-d',
    'items_per_page' => '10',
    'user_registration' => 'enabled',
    'email_notifications' => 'enabled',
    'maintenance_mode' => 'disabled',
    'smtp_host' => '',
    'smtp_port' => '587',
    'smtp_username' => '',
    'smtp_password' => '',
    'logo_url' => '',
    'favicon_url' => '',
    'meta_description' => 'Professional Event Management System',
    'meta_keywords' => 'events, management, booking, system'
];

if (file_exists($settings_file)) {
    $saved_settings = json_decode(file_get_contents($settings_file), true);
    $settings = array_merge($default_settings, $saved_settings);
} else {
    $settings = $default_settings;
    file_put_contents($settings_file, json_encode($settings, JSON_PRETTY_PRINT));
}

$success = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_settings = [
        'site_name' => trim($_POST['site_name']),
        'site_email' => trim($_POST['site_email']),
        'site_phone' => trim($_POST['site_phone']),
        'site_address' => trim($_POST['site_address']),
        'currency' => $_POST['currency'],
        'timezone' => $_POST['timezone'],
        'date_format' => $_POST['date_format'],
        'items_per_page' => $_POST['items_per_page'],
        'user_registration' => $_POST['user_registration'],
        'email_notifications' => $_POST['email_notifications'],
        'maintenance_mode' => $_POST['maintenance_mode'],
        'smtp_host' => trim($_POST['smtp_host']),
        'smtp_port' => trim($_POST['smtp_port']),
        'smtp_username' => trim($_POST['smtp_username']),
        'smtp_password' => trim($_POST['smtp_password']),
        'logo_url' => trim($_POST['logo_url']),
        'favicon_url' => trim($_POST['favicon_url']),
        'meta_description' => trim($_POST['meta_description']),
        'meta_keywords' => trim($_POST['meta_keywords'])
    ];

    // Validate required fields
    if (empty($new_settings['site_name']) || empty($new_settings['site_email'])) {
        $error = 'Site Name and Site Email are required fields!';
    } else {
        // Save to file
        if (file_put_contents($settings_file, json_encode($new_settings, JSON_PRETTY_PRINT))) {
            $success = 'System settings updated successfully!';
            $settings = $new_settings;
        } else {
            $error = 'Failed to save settings. Please check file permissions.';
        }
    }
}

// Reset to defaults
if (isset($_GET['action']) && $_GET['action'] === 'reset') {
    file_put_contents($settings_file, json_encode($default_settings, JSON_PRETTY_PRINT));
    $success = 'Settings reset to default values!';
    $settings = $default_settings;
    header('Location: system_settings.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>System Settings - Admin Panel</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            background: #f8f9fa;
            padding: 20px;
        }
        
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .header {
            background: #2c3e50;
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .header h1 {
            font-size: 24px;
        }
        
        .back-btn {
            background: #34495e;
            color: white;
            padding: 8px 15px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
        }
        
        .back-btn:hover {
            background: #3d566e;
        }
        
        .content {
            padding: 30px;
        }
        
        .success {
            background: #d4edda;
            color: #155724;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
        }
        
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
        }
        
        .settings-form {
            display: grid;
            gap: 25px;
        }
        
        .settings-section {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 8px;
            border-left: 4px solid #3498db;
        }
        
        .section-title {
            color: #2c3e50;
            margin-bottom: 20px;
            font-size: 18px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #2c3e50;
        }
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 2px solid #e9ecef;
            border-radius: 5px;
            font-size: 14px;
        }
        
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: #3498db;
            outline: none;
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }
        
        .form-actions {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
        }
        
        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background: #3498db;
            color: white;
        }
        
        .btn-primary:hover {
            background: #2980b9;
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
        }
        
        .btn-danger {
            background: #e74c3c;
            color: white;
        }
        
        .btn-danger:hover {
            background: #c0392b;
        }
        
        .current-settings {
            background: #e8f4fd;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }
        
        .current-settings h4 {
            color: #2c3e50;
            margin-bottom: 10px;
        }
        
        .settings-info {
            font-size: 12px;
            color: #7f8c8d;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>⚙️ System Settings</h1>
            <a href="admin_dashboard.php" class="back-btn">← Back to Dashboard</a>
        </div>
        
        <div class="content">
            <?php if ($success): ?>
                <div class="success">✅ <?php echo $success; ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="error">❌ <?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" class="settings-form">
                <!-- General Settings -->
                <div class="settings-section">
                    <h3 class="section-title">🌐 General Settings</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Site Name *</label>
                            <input type="text" name="site_name" value="<?php echo htmlspecialchars($settings['site_name']); ?>" required>
                            <div class="settings-info">The name of your website/application</div>
                        </div>
                        <div class="form-group">
                            <label>Site Email *</label>
                            <input type="email" name="site_email" value="<?php echo htmlspecialchars($settings['site_email']); ?>" required>
                            <div class="settings-info">Default email for system notifications</div>
                        </div>
                        <div class="form-group">
                            <label>Site Phone</label>
                            <input type="text" name="site_phone" value="<?php echo htmlspecialchars($settings['site_phone']); ?>">
                            <div class="settings-info">Contact phone number</div>
                        </div>
                        <div class="form-group">
                            <label>Site Address</label>
                            <textarea name="site_address"><?php echo htmlspecialchars($settings['site_address']); ?></textarea>
                            <div class="settings-info">Business/office address</div>
                        </div>
                    </div>
                </div>
                
                <!-- Regional Settings -->
                <div class="settings-section">
                    <h3 class="section-title">🌍 Regional Settings</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Currency</label>
                            <select name="currency">
                                <option value="USD" <?php echo $settings['currency'] === 'USD' ? 'selected' : ''; ?>>USD ($)</option>
                                <option value="EUR" <?php echo $settings['currency'] === 'EUR' ? 'selected' : ''; ?>>EUR (€)</option>
                                <option value="GBP" <?php echo $settings['currency'] === 'GBP' ? 'selected' : ''; ?>>GBP (£)</option>
                                <option value="INR" <?php echo $settings['currency'] === 'INR' ? 'selected' : ''; ?>>INR (₹)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Timezone</label>
                            <select name="timezone">
                                <option value="America/New_York" <?php echo $settings['timezone'] === 'America/New_York' ? 'selected' : ''; ?>>Eastern Time (ET)</option>
                                <option value="America/Chicago" <?php echo $settings['timezone'] === 'America/Chicago' ? 'selected' : ''; ?>>Central Time (CT)</option>
                                <option value="America/Denver" <?php echo $settings['timezone'] === 'America/Denver' ? 'selected' : ''; ?>>Mountain Time (MT)</option>
                                <option value="America/Los_Angeles" <?php echo $settings['timezone'] === 'America/Los_Angeles' ? 'selected' : ''; ?>>Pacific Time (PT)</option>
                                <option value="Asia/Kolkata" <?php echo $settings['timezone'] === 'Asia/Kolkata' ? 'selected' : ''; ?>>India Standard Time (IST)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Date Format</label>
                            <select name="date_format">
                                <option value="Y-m-d" <?php echo $settings['date_format'] === 'Y-m-d' ? 'selected' : ''; ?>>YYYY-MM-DD (2024-01-15)</option>
                                <option value="m/d/Y" <?php echo $settings['date_format'] === 'm/d/Y' ? 'selected' : ''; ?>>MM/DD/YYYY (01/15/2024)</option>
                                <option value="d/m/Y" <?php echo $settings['date_format'] === 'd/m/Y' ? 'selected' : ''; ?>>DD/MM/YYYY (15/01/2024)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Items Per Page</label>
                            <select name="items_per_page">
                                <option value="5" <?php echo $settings['items_per_page'] === '5' ? 'selected' : ''; ?>>5 items</option>
                                <option value="10" <?php echo $settings['items_per_page'] === '10' ? 'selected' : ''; ?>>10 items</option>
                                <option value="25" <?php echo $settings['items_per_page'] === '25' ? 'selected' : ''; ?>>25 items</option>
                                <option value="50" <?php echo $settings['items_per_page'] === '50' ? 'selected' : ''; ?>>50 items</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- System Features -->
                <div class="settings-section">
                    <h3 class="section-title">🔧 System Features</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label>User Registration</label>
                            <select name="user_registration">
                                <option value="enabled" <?php echo $settings['user_registration'] === 'enabled' ? 'selected' : ''; ?>>Enabled</option>
                                <option value="disabled" <?php echo $settings['user_registration'] === 'disabled' ? 'selected' : ''; ?>>Disabled</option>
                            </select>
                            <div class="settings-info">Allow new users to register</div>
                        </div>
                        <div class="form-group">
                            <label>Email Notifications</label>
                            <select name="email_notifications">
                                <option value="enabled" <?php echo $settings['email_notifications'] === 'enabled' ? 'selected' : ''; ?>>Enabled</option>
                                <option value="disabled" <?php echo $settings['email_notifications'] === 'disabled' ? 'selected' : ''; ?>>Disabled</option>
                            </select>
                            <div class="settings-info">Send email notifications to users</div>
                        </div>
                        <div class="form-group">
                            <label>Maintenance Mode</label>
                            <select name="maintenance_mode">
                                <option value="disabled" <?php echo $settings['maintenance_mode'] === 'disabled' ? 'selected' : ''; ?>>Disabled</option>
                                <option value="enabled" <?php echo $settings['maintenance_mode'] === 'enabled' ? 'selected' : ''; ?>>Enabled</option>
                            </select>
                            <div class="settings-info">Put site in maintenance mode</div>
                        </div>
                    </div>
                </div>
                
                <!-- Email Settings -->
                <div class="settings-section">
                    <h3 class="section-title">📧 Email Settings (SMTP)</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label>SMTP Host</label>
                            <input type="text" name="smtp_host" value="<?php echo htmlspecialchars($settings['smtp_host']); ?>">
                            <div class="settings-info">e.g., smtp.gmail.com</div>
                        </div>
                        <div class="form-group">
                            <label>SMTP Port</label>
                            <input type="text" name="smtp_port" value="<?php echo htmlspecialchars($settings['smtp_port']); ?>">
                            <div class="settings-info">Usually 587 for TLS</div>
                        </div>
                        <div class="form-group">
                            <label>SMTP Username</label>
                            <input type="text" name="smtp_username" value="<?php echo htmlspecialchars($settings['smtp_username']); ?>">
                        </div>
                        <div class="form-group">
                            <label>SMTP Password</label>
                            <input type="password" name="smtp_password" value="<?php echo htmlspecialchars($settings['smtp_password']); ?>">
                        </div>
                    </div>
                </div>
                
                <!-- SEO & Appearance -->
                <div class="settings-section">
                    <h3 class="section-title">🎨 SEO & Appearance</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Logo URL</label>
                            <input type="url" name="logo_url" value="<?php echo htmlspecialchars($settings['logo_url']); ?>">
                            <div class="settings-info">URL to your site logo</div>
                        </div>
                        <div class="form-group">
                            <label>Favicon URL</label>
                            <input type="url" name="favicon_url" value="<?php echo htmlspecialchars($settings['favicon_url']); ?>">
                            <div class="settings-info">URL to your favicon</div>
                        </div>
                        <div class="form-group" style="grid-column: 1 / -1;">
                            <label>Meta Description</label>
                            <textarea name="meta_description"><?php echo htmlspecialchars($settings['meta_description']); ?></textarea>
                            <div class="settings-info">Brief description for search engines (150-160 characters)</div>
                        </div>
                        <div class="form-group" style="grid-column: 1 / -1;">
                            <label>Meta Keywords</label>
                            <textarea name="meta_keywords"><?php echo htmlspecialchars($settings['meta_keywords']); ?></textarea>
                            <div class="settings-info">Comma-separated keywords for SEO</div>
                        </div>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn btn-danger" onclick="if(confirm('Reset all settings to default?')) window.location.href='system_settings.php?action=reset'">
                        🔄 Reset to Default
                    </button>
                    <button type="reset" class="btn btn-secondary">🔄 Reset Form</button>
                    <button type="submit" class="btn btn-primary">💾 Save Settings</button>
                </div>
            </form>
            
            <div class="current-settings">
                <h4>📁 Current Settings File: data/system_settings.json</h4>
                <p><strong>Last Modified:</strong> <?php echo date('F j, Y g:i A', filemtime($settings_file)); ?></p>
                <p><strong>File Size:</strong> <?php echo round(filesize($settings_file) / 1024, 2); ?> KB</p>
            </div>
        </div>
    </div>
</body>
</html>