<?php
// Profile section logic
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset_password'])) {
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    if ($new_password !== $confirm_password) {
        $error = "❌ New password and confirm password do not match!";
    } 
    elseif (!empty($current_password) && !password_verify($current_password, $user['password'])) {
        $error = "❌ Current password is incorrect!";
    }
    elseif (empty($current_password) && empty($_POST['email_verification'])) {
        $error = "❌ Please either provide your current password or check email verification!";
    }
    else {
        $hashed = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('UPDATE users SET password = ? WHERE id = ?');
        if ($stmt->execute([$hashed, $_SESSION['user_id']])) {
            $success = "✅ Password updated successfully!";
        } else {
            $error = "❌ Error updating password. Please try again.";
        }
    }
}
?>

<style>
.profile-content {text-align:left;padding:30px;}
.profile-content h1 {text-align:center;margin-bottom:30px;color:#2c3e50;font-size:32px;}
.profile-header {display:flex;align-items:center;margin-bottom:40px;padding:20px;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);border-radius:15px;color:white;}
.profile-avatar {width:100px;height:100px;background:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;margin-right:30px;font-size:40px;color:#667eea;}
.profile-info h2 {margin:0;font-size:28px;}
.profile-info p {margin:5px 0;opacity:0.9;}
.profile-details {display:grid;grid-template-columns:repeat(auto-fit, minmax(300px, 1fr));gap:25px;margin-bottom:40px;}
.detail-card {background:#f8f9fa;padding:25px;border-radius:12px;border-left:5px solid #ff6b81;}
.detail-card h3 {color:#2c3e50;margin-bottom:15px;font-size:20px;}
.detail-item {display:flex;justify-content:space-between;padding:12px 0;border-bottom:1px solid #eee;}
.detail-item:last-child {border-bottom:none;}
.detail-label {font-weight:bold;color:#555;}
.detail-value {color:#2c3e50;}
.password-reset-section {background:#fff;padding:30px;border-radius:15px;box-shadow:0 5px 20px rgba(0,0,0,0.1);margin-top:30px;}
.password-reset-section h3 {color:#2c3e50;margin-bottom:20px;font-size:24px;}
.form-group {margin-bottom:20px;position:relative;}
.form-group label {display:block;margin-bottom:8px;font-weight:bold;color:#555;}
.password-input-wrapper {position:relative;width:80%;}
.password-input-wrapper input {width:100%;padding-right:45px;padding:12px;border:1px solid #ccc;border-radius:8px;font-size:15px;}
.toggle-password {position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;color:#666;cursor:pointer;font-size:16px;}
.verification-option {display:flex;align-items:center;margin:15px 0;}
.verification-option input {margin-right:10px;}
.optional {color:#6c757d;font-size:14px;margin-left:5px;}
</style>

<div class="profile-content">
    <h1><i class="fas fa-user-circle"></i> User Profile</h1>
    
    <div class="profile-header">
        <div class="profile-avatar">
            <i class="fas fa-user"></i>
        </div>
        <div class="profile-info">
            <h2><?= htmlspecialchars($user['name']); ?></h2>
            <p><i class="fas fa-envelope"></i> <?= htmlspecialchars($user['email']); ?></p>
            <p><i class="fas fa-user-tag"></i> <?= htmlspecialchars($user['role']); ?></p>
        </div>
    </div>

    <div class="profile-details">
        <div class="detail-card">
            <h3><i class="fas fa-info-circle"></i> Personal Information</h3>
            <div class="detail-item">
                <span class="detail-label">Full Name:</span>
                <span class="detail-value"><?= htmlspecialchars($user['name']); ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Email Address:</span>
                <span class="detail-value"><?= htmlspecialchars($user['email']); ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Phone Number:</span>
                <span class="detail-value"><?= $user['phone'] ?? 'Not provided'; ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">User Role:</span>
                <span class="detail-value" style="color:#e74c3c; font-weight:bold;"><?= htmlspecialchars($user['role']); ?></span>
            </div>
        </div>

        <div class="detail-card">
            <h3><i class="fas fa-calendar-alt"></i> Account Information</h3>
            <div class="detail-item">
                <span class="detail-label">Member Since:</span>
                <span class="detail-value"><?= date('F j, Y', strtotime($user['created_at'] ?? '2024-01-01')); ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Account Status:</span>
                <span class="detail-value" style="color:#27ae60; font-weight:bold;">Active</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Last Login:</span>
                <span class="detail-value"><?= date('F j, Y g:i A'); ?></span>
            </div>
        </div>
    </div>

    <div class="password-reset-section">
        <h3><i class="fas fa-lock"></i> Password Reset</h3>
        <form method="POST">
            <div class="form-group">
                <label for="current_password">Current Password <span class="optional">(Optional)</span></label>
                <div class="password-input-wrapper">
                    <input type="password" id="current_password" name="current_password" placeholder="Enter your current password">
                    <button type="button" class="toggle-password" data-target="current_password">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <div class="verification-option">
                <input type="checkbox" id="email_verification" name="email_verification" value="1">
                <label for="email_verification">I verify this password change via email (<?= htmlspecialchars($user['email']); ?>)</label>
            </div>

            <div class="form-group">
                <label for="new_password">New Password</label>
                <div class="password-input-wrapper">
                    <input type="password" id="new_password" name="new_password" placeholder="Enter new password" required>
                    <button type="button" class="toggle-password" data-target="new_password">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm New Password</label>
                <div class="password-input-wrapper">
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm new password" required>
                    <button type="button" class="toggle-password" data-target="confirm_password">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <button type="submit" name="reset_password" style="background:#27ae60; padding:12px 25px;border:none;color:white;border-radius:8px;cursor:pointer;">
                <i class="fas fa-sync-alt"></i> Reset Password
            </button>

            <?php if (!empty($success)): ?>
                <div class="success"><?= $success ?></div>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
                <div class="error"><?= $error ?></div>
            <?php endif; ?>
        </form>
    </div>
</div>

<script>
function validatePasswordReset() {
    const currentPassword = document.getElementById('current_password');
    const emailVerification = document.getElementById('email_verification');
    const newPassword = document.getElementById('new_password');
    const confirmPassword = document.getElementById('confirm_password');

    if (!currentPassword.value && !emailVerification.checked) {
        alert('Please provide either your current password or check email verification!');
        return false;
    }

    if (newPassword.value !== confirmPassword.value) {
        alert('New password and confirm password do not match!');
        return false;
    }

    if (newPassword.value.length < 6) {
        alert('Password should be at least 6 characters long!');
        return false;
    }

    return confirm('Are you sure you want to reset your password?');
}
</script>